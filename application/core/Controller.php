<?php

declare(strict_types = 1);

class Controller
{
    public $view;

    function __construct()
    {
        Session::init();

        if (!Session::userIsLoggedIn()) {
            Redirect::to("login/login");
        }

        $this->view = new View();
    }
}