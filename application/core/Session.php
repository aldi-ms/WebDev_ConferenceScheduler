<?php

declare(strict_types = 1);

class Session
{
    /**
     * Start session
     */
    public static function init()
    {
        if (session_id() == '') {
            session_start();
        }
    }

    /**
     * Sets specific value to the key
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets the value of of the specified key
     * @param $key
     * @return string
     */
    public static function get($key) : string
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return "";
    }

    /**
     * Push a value as a array element to the specified key
     * @param $key
     * @param $value
     */
    public static function push($key, $value)
    {
        $_SESSION[$key][] = $value;
    }

    /**
     * Update the session id in our database
     * @param $userId
     * @param null $sessionId
     */
    public static function updateSessionId($userId, $sessionId = null)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "UPDATE users SET session_id = :session_id WHERE user_id = :user_id";

        $query = $database->prepare($sql);
        $query->execute(array(':session_id' => $sessionId, ":user_id" => $userId));
    }

    /**
     * @return bool
     */
    public static function userIsLoggedIn() : bool
    {
        return self::get('user_logged_in') ? true : false;
    }

    /**
     * Checks for user session concurrency
     * (e.g. two users logged in with the same account)
     * @return bool
     */
    public static function concurrentSessionsExist() : bool
    {
        $sessionId = session_id();
        $userId = Session::get('user_id');

        if (isset($userId) && isset($sessionId)) {
            $database = DbFactory::getFactory()->getConnection();
            $sql = "SELECT session_id FROM users WHERE user_id = :user_id LIMIT 1";

            $query = $database->prepare($sql);
            $query->execute(array(":user_id" => $userId));

            $result = $query->fetch();
            $userSessionId = !empty($result) ? $result->session_id : null;

            return $sessionId !== $userSessionId;
        }

        return false;
    }

    /**
     * Destroy/clear the session
     */
    public static function destroy()
    {
        session_destroy();
    }
}