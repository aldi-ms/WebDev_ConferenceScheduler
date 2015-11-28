<?php

declare(strict_types=1);

class Request
{
    /**
     * @param string $key
     * @param bool|false $clean Indicates whether to clean the var
     * @return string
     */
    public static function post(string $key, bool $clean = false) : string
    {
        if (isset($_POST[$key])) {
            return ($clean) ? trim(strip_tags($_POST[$key])) : $_POST[$key];
        }

        return "";
    }

    public static function get(string $key)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return null;
    }

    public static function cookie(string $key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return null;
    }
}