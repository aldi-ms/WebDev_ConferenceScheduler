<?php

declare(strict_types = 1);

class LoginModel
{
    /**
     * @param string $userName
     * @param string $userPassword
     * @param string|null $setRememberMeCookie
     * @return bool
     */
    public static function login(string $userName, string $userPassword, string $setRememberMeCookie = null) : bool
    {
        if (empty($userName) || empty($userPassword)) {
            Session::push("feedback_negative", Text::get('USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        // checks if user exists, if login is not blocked (due to failed login attempts) and if password fits the hash
        $result = self::validateUser($userName, $userPassword);

        if (!$result) {
            return false;
        }

        // stop the user's login if account has been soft deleted
        if ($result->user_deleted == 1) {
            Session::push('feedback_negative', Text::get('USER_DELETED'));
            return false;
        }

        // reset the failed login counter for that user (if necessary)
        if ($result->user_last_failed_login > 0) {
            self::resetUserFailedLoginCounter($result->user_name);
        }

        // save timestamp of this login in the database line of that user
        self::saveUserLoginTimestamp($result->user_name);
        // if user has checked the "remember me" checkbox, then write token into database and into cookie
        if ($setRememberMeCookie) {
            self::setRememberMeDbAndCookie($result->user_id);
        }

        // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
        self::setSuccessfulLoginIntoSession(
            $result->user_id, $result->user_name, $result->user_email, $result->user_account_type
        );

        return true;
    }

    /**
     * Pass the login parameters to the session
     * @param $userId
     * @param $userName
     * @param $userEmail
     * @param $userAccountType
     */
    public static function setSuccessfulLoginIntoSession($userId, $userName, $userEmail, $userAccountType)
    {
        Session::init();

        // remove old and regenerate session ID.
        session_regenerate_id(true);
        $_SESSION = array();

        Session::set('user_id', $userId);
        Session::set('user_name', $userName);
        Session::set('user_email', $userEmail);
        Session::set('user_account_type', $userAccountType);
        Session::set('user_logged_in', true);

        Session::updateSessionId($userId, session_id());

        // set session cookie
        setcookie(session_name(), session_id(), time() + Config::get('SESSION_RUNTIME'), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }

    /**
     * Set the remember_me option in the database and cookie
     * @param $userId
     * @throws Exception
     */
    public static function setRememberMeDbAndCookie($userId)
    {
        $database = DbFactory::getFactory()->getConnection();

        // generate 64 char random string and write it to database
        $randomToken = hash('sha256', mt_rand());
        $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_remember_me_token' => $randomToken, ':user_id' => $userId));

        $cookieStringFirst = Encryption::encrypt($userId) . ':' . $randomToken;
        $hashString = hash('sha256', $userId . ':' . $randomToken);
        $cookieString = $cookieStringFirst . ':' . $hashString;

        setcookie('remember_me', $cookieString, time() + Config::get('COOKIE_RUNTIME'), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }

    public static function saveUserLoginTimestamp($userName)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "UPDATE users SET user_last_login_timestamp = :user_last_login_timestamp
                WHERE user_name = :user_name LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $userName, ':user_last_login_timestamp' => time()));
    }


    /**
     * Logout the user, clear session
     */
    public static function logout()
    {
        $userId = Session::get('user_id');
        self::deleteCookie($userId);
        Session::destroy();
        Session::updateSessionId($userId);
    }

    /**
     * Clear remember_me cookie and update database if $userId was provided
     * @param null $userId
     */
    public static function deleteCookie($userId = null)
    {
        if(isset($userId)){
            $database = DbFactory::getFactory()->getConnection();
            $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
            $query = $database->prepare($sql);
            $query->execute(array(':user_remember_me_token' => NULL, ':user_id' => $userId));
        }
        
        // delete remember_me cookie in browser
        setcookie('remember_me', '', time() - (3600 * 24 * 3650), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }

    /**
     * Reset the failed login attempts counter (once the user has been validated)
     * @param $userName
     */
    public static function resetUserFailedLoginCounter($userName)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "UPDATE users
                   SET user_failed_logins = 0, user_last_failed_login = NULL
                 WHERE user_name = :user_name AND user_failed_logins != 0
                 LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $userName));
    }

    /**
     * @return bool
     */
    public static function isUserLoggedIn() : bool
    {
        return Session::userIsLoggedIn();
    }

    /**
     * Login the user with previously set cookie
     * @param string $cookie
     * @return bool
     * @throws Exception
     */
    public static function loginWithCookie(string $cookie) : bool
    {
        if (!$cookie) {
            Session::push('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // check if cookie can be split into 3 parts
        if(count(explode(':', $cookie)) !== 3){
            Session::push('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        list($user_id, $token, $hash) = explode(':', $cookie);

        // decrypt user id
        $user_id = Encryption::decrypt($user_id);

        if ($hash !== hash('sha256', $user_id . ':' . $token) OR empty($token) OR empty($user_id)) {
            Session::push('feedback_negative', Text::get('COOKIE_INVALID'));
            return false;
        }

        // get data of user that has this id and this token
        $result = UserModel::getUserDataByUserIdAndToken($user_id, $token);

        if ($result) {
            // successfully logged in
            self::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type);
            // save timestamp of this login
            self::saveTimestampOfLoginOfUser($result->user_name);

            Session::push('feedback_positive', Text::get('COOKIE_LOGIN_SUCCESSFUL'));
            return true;
        } else {
            Session::push('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }
    }

    /**
     * @param $userName
     */
    public static function saveTimestampOfLoginOfUser($userName)
    {
        $database = DbFactory::getFactory()->getConnection();

        $sql = "UPDATE users SET user_last_login_timestamp = :user_last_login_timestamp
                    WHERE user_name = :user_name LIMIT 1";
        $sth = $database->prepare($sql);
        $sth->execute(array(':user_name' => $userName, ':user_last_login_timestamp' => time()));
    }

    /**
     * Validate the user against the DB, or increment the "failed-login-count" to prevent
     * bruteforce password/user attacks
     * @param string $userName
     * @param string $userPassword
     * @return mixed
     */
    private static function validateUser(string $userName, string $userPassword)
    {
        if (Session::get('failed-login-count') >= 3 && Session::get('last-failed-login') > (time() - 30)) {
            Session::push('feedback_negative', Text::get("LOGIN_FAILED_ATTEMPTS"));
            return false;
        }

        $result = UserModel::getUserByName($userName);

        if (!$result) {
            self::incrementUserFailedLoginCount();
            Session::push("feedback-negative", Text::get("USERNAME_OR_PASSWORD_WRONG"));
            return false;
        }

        if (($result->user_failed_logins >= 3) && $result->user_last_failed_login > (time() - 30)) {
            Session::push('feedback_negative', Text::get('PASSWORD_WRONG_ATTEMPTS'));
            return false;
        }

        if (!password_verify($userPassword, $result->user_password_hash)) {
            self::incrementUserFailedLoginCountInDb($result->user_name);
            Session::push('feedback_negative', Text::get('USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        self::resetUserFailedLoginCount();
        return $result;
    }

    /**
     * Increment the session failed-login-count
     */
    private static function incrementUserFailedLoginCount()
    {
        Session::set('failed-login-count', Session::get('failed-login-count') + 1);
        Session::set('last-failed-login', time());
    }

    /**
     * Resets session failed-login-count
     */
    private static function resetUserFailedLoginCount()
    {
        Session::set('failed-login-count', 0);
        Session::set('last-failed-login', '');
    }

    /**
     * Increment the user failed-login-count in the database
     * @param $userName
     */
    private static function incrementUserFailedLoginCountInDb($userName)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "UPDATE users
                   SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                 WHERE user_name = :user_name OR user_email = :user_name
                 LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $userName, ':user_last_failed_login' => time() ));
    }
}