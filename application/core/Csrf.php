<?php

declare(strict_types = 1);


class Csrf
{
    /**
     * Get CSRF token and generate a new one if expired
     * @access public
     * @static static method
     * @return string
     */
    public static function makeToken() : string
    {
        $maxTime    = 60 * 60 * 24; // token is valid for 1 day
        $storedTime = Session::get('csrf_token_time');
        $csrfToken  = Session::get('csrf_token');

        if($maxTime + $storedTime <= time() || empty($csrfToken)){
            Session::set('csrf_token', md5(uniqid(rand(), true)));
            Session::set('csrf_token_time', time());
        }

        return Session::get('csrf_token');
    }

    /**
     * Checks if CSRF token in session is same as in the form submitted
     * @access public
     * @static static method
     * @return bool
     */
    public static function isTokenValid() : bool
    {
        $token = Request::post('csrf_token');
        return $token === Session::get('csrf_token') && !empty($token);
    }
}