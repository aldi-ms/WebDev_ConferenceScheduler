<?php

declare(strict_types = 1);

class Redirect
{
    /**
     * Go to the homepage
     */
    public static function home()
    {
        header("location: " . Config::get('URL'));
    }

    /**
     * Redirect to the defined path
     * @param $path
     */
    public static function to($path)
    {
        header("location: " . Config::get('URL') . $path);
    }
}