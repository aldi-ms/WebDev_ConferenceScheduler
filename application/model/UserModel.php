<?php

declare(strict_types = 1);

class UserModel
{
    /**
     * Get user data from the database
     * @param $userName
     * @return mixed false if the user does not exist, otherwise the user's data
     */
    public static function getUserByName($userName) : mixed
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "SELECT user_id, user_name, user_email, user_password_hash, user_active,user_deleted, user_suspension_timestamp, user_account_type,
                       user_failed_logins, user_last_failed_login
                  FROM users
                 WHERE user_name = :user_name
                 LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $userName));

        return $query->fetch();
    }
}