<?php

declare(strict_types = 1);


class Auth
{
    /**
     * User authentication flow, check session if user is logged in, else redirect to
     * login page and hard-stop.
     */
    public static function checkAuthentication()
    {
        // initialize the session
        Session::init();

        if (!Session::userIsLoggedIn()) {
            Session::destroy();
            Redirect::to('login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit();
        }
    }

    /**
     * Admin authentication flow, check if user is logged in and has
     * account type of 7 (=admin), else logout the user, redirect to login page and hard-stop.
     */
    public static function checkAdminAuthentication()
    {
        // initialize the session
        Session::init();

        if (!Session::userIsLoggedIn() || Session::get("user_account_type") != 7) {
            Session::destroy();
            Redirect::to('login');
            exit();
        }
    }

    /**
     * Check if there is concurrent session, and logout if so, redirect to home and hard-stop.
     */
    public static function checkSessionConcurrency(){
        if(Session::userIsLoggedIn()){
            if(Session::concurrentSessionsExist()){
                LoginModel::logout();
                Redirect::home();
                exit();
            }
        }
    }
}