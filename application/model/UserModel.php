<?php

declare(strict_types = 1);

class UserModel
{
    /**
     * Get user data from the database
     * @param $userName
     * @return mixed false if the user does not exist, otherwise the user's data
     */
    public static function getUserByName(string $userName) : mixed
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "SELECT user_id, user_name, user_email, user_password_hash, user_deleted,
                  user_account_type, user_failed_logins, user_last_failed_login
                  FROM users
                 WHERE user_name = :user_name
                 LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $userName));

        return $query->fetch();
    }

    /**
     * Check if the given username already exists
     * @param string $userName
     * @return bool
     */
    public static function usernameExists(string $userName) : bool
    {
        $database = DbFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
        $query->execute(array(':user_name' => $userName));
        if ($query->rowCount() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given email already exists
     * @param string $userEmail
     * @return bool
     */
    public static function emailExists(string $userEmail) : bool
    {
        $database = DbFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_email = :user_email LIMIT 1");
        $query->execute(array(':user_email' => $userEmail));
        if ($query->rowCount() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the user id by name
     * @param $user_name
     * @return int user id
     */
    public static function getUserIdByName(string $user_name) : int
    {
        $database = DbFactory::getFactory()->getConnection();

        $sql = "SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $user_name));

        return $query->fetch()->user_id;
    }

    /**
     * @param $userId
     * @param $token
     * @return mixed
     */
    public static function getUserDataByUserIdAndToken(int $userId, string $token) : mixed
    {
        $database = DbFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_id, user_name, user_email, user_password_hash,
                                          user_account_type, user_failed_logins, user_last_failed_login
                                     FROM users
                                     WHERE user_id = :user_id
                                       AND user_remember_me_token = :user_remember_me_token
                                       AND user_remember_me_token IS NOT NULL
                                     LIMIT 1");
        $query->execute(array(':user_id' => $userId, ':user_remember_me_token' => $token));

        return $query->fetch();
    }
}