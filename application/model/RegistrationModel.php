<?php

declare(strict_types = 1);


class RegistrationModel
{
    /**
     * User registration process
     * @return bool Shows whether the registration was successful
     */
    public static function registerUser() : bool
    {
        $userName = Request::post("user_name", true);
        $userEmail = Request::post("user_email", true);
        $userPassword = Request::post("user_password");
        $userPasswordRepeat = Request::post("user_password_repeat");

        $validationResult = self::registrationInputValidation($userName, $userPassword, $userPasswordRepeat, $userEmail);
        if (!$validationResult) {
            return false;
        }

        // hash password
        $userPasswordHash = password_hash($userPassword, PASSWORD_DEFAULT);

        $success = true;

        if (UserModel::usernameExists($userName)) {
            Session::push('feedback_negative', Text::get('USERNAME_ALREADY_TAKEN'));
            $success = false;
        }

        if (UserModel::emailExists($userName)) {
            Session::push('feedback_negative', Text::get('EMAIL_ALREADY_TAKEN'));
            $success = false;
        }

        if (!$success) {
            return false;
        }

        if (!self::createUserInDb($userName, $userPasswordHash, $userEmail, time())) {
            Session::push('feedback_negative', Text::get('ACCOUNT_CREATION_FAILED'));
            return false;
        }

        $userId = UserModel::getUserIdByName($userName);
        if (!$userId) {
            Session::push('feedback_negative', Text::get('UNKNOWN_ERROR'));
            return false;
        }

        return true;
    }

    /**
     * Validate registration input
     * @param $userName
     * @param $userPassword
     * @param $userPasswordRepeat
     * @param $userEmail
     * @return bool
     */
    public static function registrationInputValidation(string $userName, string $userPassword, string $userPasswordRepeat, string $userEmail) : bool
    {
        // perform all necessary checks
        if (self::validateUserName($userName) && self::validateUserEmail($userEmail) && self::validateUserPassword($userPassword, $userPasswordRepeat)) {
            return true;
        }

        return false;
    }

    /**
     * Validate the given user name
     * @param $userName
     * @return bool
     */
    public static function validateUserName(string $userName) : bool
    {
        if (empty($userName)) {
            Session::push('feedback_negative', Text::get('USERNAME_FIELD_EMPTY'));
            return false;
        }

        // if username is too short (2), too long (64) or does not fit the pattern (aZ09)
        if (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $userName)) {
            Session::push('feedback_negative', Text::get('USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        return true;
    }

    /**
     * Validate the given user email
     * @param $userEmail
     * @return bool
     */
    public static function validateUserEmail(string $userEmail) : bool
    {
        if (empty($userEmail)) {
            Session::push('feedback_negative', Text::get('EMAIL_FIELD_EMPTY'));
            return false;
        }

        // use internal php validation filter
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            Session::push('feedback_negative', Text::get('EMAIL_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        return true;
    }

    /**
     * Validates the given user password / repeat password
     * @param $userPassword
     * @param $userPasswordRepeat
     * @return bool
     */
    public static function validateUserPassword(string $userPassword, string $userPasswordRepeat) : bool
    {
        if (empty($userPassword) OR empty($userPasswordRepeat)) {
            Session::push('feedback_negative', Text::get('PASSWORD_FIELD_EMPTY'));
            return false;
        }

        if ($userPassword !== $userPasswordRepeat) {
            Session::push('feedback_negative', Text::get('PASSWORD_REPEAT_WRONG'));
            return false;
        }

        if (strlen($userPassword) < 6) {
            Session::push('feedback_negative', Text::get('PASSWORD_TOO_SHORT'));
            return false;
        }

        return true;
    }

    /**
     * Create user in the database
     * @param $userName
     * @param $userPasswordHash
     * @param $userEmail
     * @param $userCreationTimestamp
     * @return bool
     */
    public static function createUserInDb(string $userName, string $userPasswordHash, string $userEmail, string $userCreationTimestamp)
    {
        $database = DbFactory::getFactory()->getConnection();

        // write new users data into database
        $sql = "INSERT INTO users (user_name, user_password_hash, user_email, user_creation_timestamp, user_activation_hash, user_provider_type)
                    VALUES (:user_name, :user_password_hash, :user_email, :user_creation_timestamp)";
        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $userName,
            ':user_password_hash' => $userPasswordHash,
            ':user_email' => $userEmail,
            ':user_creation_timestamp' => $userCreationTimestamp));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }

        return false;
    }
}