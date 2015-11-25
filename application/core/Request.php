<?php

declare(strict_types=1);

class Request
{
    /**
     * @param string $key
     * @param bool|false $clean indicates whether to clean the var
     * @return mixed the value of the key, or null if it doesn't exist
     */
    public static function post(string $key, bool $clean = false) : mixed
    {
        if (isset($_POST[$key])) {
            return ($clean) ? trim(strip_tags($_POST[$key])) : $_POST[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @return mixed the value of the key, or null if it doesn't exist
     */
    public static function get(string $key) : mixed
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @return mixed the value of the key, or null if it doesn't exist
     */
    public static function cookie(string $key) : mixed
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return null;
    }
}