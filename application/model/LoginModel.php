<?php

declare(strict_types = 1);

class LoginModel
{
    public static function login(string $userName, string $userPassword, bool $setRememberMeCookie = null) : bool
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
            self::setRememberMeInDBAndCookie($result->user_id);
        }

        // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
        self::setSuccessfulLoginIntoSession(
            $result->user_id, $result->user_name, $result->user_email, $result->user_account_type
        );

        return true;
    }

    public static function setSuccessfulLoginIntoSession($user_id, $user_name, $user_email, $user_account_type)
    {
        Session::init();

        // remove old and regenerate session ID.
        session_regenerate_id(true);
        $_SESSION = array();

        Session::set('user_id', $user_id);
        Session::set('user_name', $user_name);
        Session::set('user_email', $user_email);
        Session::set('user_account_type', $user_account_type);
        Session::set('user_logged_in', true);

        Session::updateSessionId($user_id, session_id());

        // set session cookie
        setcookie(session_name(), session_id(), time() + Config::get('SESSION_RUNTIME'), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }

        public static function setRememberMeInDBAndCookie($user_id)
    {
        $database = DbFactory::getFactory()->getConnection();

        // generate 64 char random string and write it to database
        $random_token_string = hash('sha256', mt_rand());
        $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_remember_me_token' => $random_token_string, ':user_id' => $user_id));

        $cookie_string_first_part = Encryption::encrypt($user_id) . ':' . $random_token_string;
        $cookie_string_hash = hash('sha256', $user_id . ':' . $random_token_string);
        $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

        setcookie('remember_me', $cookie_string, time() + Config::get('COOKIE_RUNTIME'), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }


    public static function saveUserLoginTimestamp($user_name)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "UPDATE users SET user_last_login_timestamp = :user_last_login_timestamp
                WHERE user_name = :user_name LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $user_name, ':user_last_login_timestamp' => time()));
    }


    public static function logout()
    {
        $user_id = Session::get('user_id');
        self::deleteCookie($user_id);
        Session::destroy();
        Session::updateSessionId($user_id);
    }

    public static function deleteCookie($user_id = null)
    {
        // is $user_id was set, then clear remember_me token in database
        if(isset($user_id)){
            $database = DbFactory::getFactory()->getConnection();
            $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
            $query = $database->prepare($sql);
            $query->execute(array(':user_remember_me_token' => NULL, ':user_id' => $user_id));
        }
        
        // delete remember_me cookie in browser
        setcookie('remember_me', false, time() - (3600 * 24 * 3650), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }

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

    private static function validateUser($userName, $userPassword) : bool
    {
        if (Session::get('failed-login-count') >= 3 && Session::get('last-failed-login') > (time() - 30)) {
            Session::push('feedback_negative', Text::get("LOGIN_FAILED_ATTEMPTS"));
            return false;
        }

        $result = UserModel::getUserByName($userName);

        if (!$result) {
            self::incrementUserNotFoundCounter();
            Session::push("feedback-negative", Text::get("USERNAME_OR_PASSWORD_WRONG"));
            return false;
        }

        if (($result->user_failed_logins >= 3) && $result->user_last_failed_login > (time() - 30)) {
            Session::push('feedback_negative', Text::get('PASSWORD_WRONG_ATTEMPTS'));
            return false;
        }

        if (!password_verify($userPassword, $result->user_password_hash)) {
            self::incrementUserFailedLoginCounter($result->user_name);
            Session::push('feedback_negative', Text::get('USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        self::resetUserNotFoundCounter();
        return $result;
    }

    private static function incrementUserNotFoundCounter()
    {
        Session::set('failed-login-count', Session::get('failed-login-count') + 1);
        Session::set('last-failed-login', time());
    }

    private static function resetUserNotFoundCounter()
    {
        Session::set('failed-login-count', 0);
        Session::set('last-failed-login', '');
    }

    private static function incrementUserFailedLoginCounter($userName)
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