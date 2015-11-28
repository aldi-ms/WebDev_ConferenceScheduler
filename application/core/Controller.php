<?php

declare(strict_types = 1);

class Controller
{
    public $view;

    function __construct()
    {
        Session::init();

        Auth::checkSessionConcurrency();

        if (!Session::userIsLoggedIn() && Request::cookie('remember_me')) {
            Redirect::to("login/loginWithCookie");
        }

        $this->view = new View();
    }
}