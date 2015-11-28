<?php

declare(strict_types = 1);

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (LoginModel::isUserLoggedIn()) {
            Redirect::home();
        }
        else {
            $data = array('redirect' => Request::get('redirect') ? Request::get('redirect') : NULL);
            $this->view->render('login/index', $data);
        }
    }

    public function login()
    {
        if (!Csrf::isTokenValid()) {
            self::logout();
        }

        $success = LoginModel::login(
            Request::post('user_name'), Request::post('user_password'), Request::post('set_remember_me_cookie'));

        // check login status: if true, then redirect user login/showProfile, if false, then to login form again
        if ($success) {
            if (Request::post('redirect')) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            }
            else {
                Redirect::to('login/showProfile');
            }
        }
        else {
            Redirect::to('login/index');
        }
    }

    public function register_action()
    {
        $success = RegistrationModel::registerUser();

        if ($success) {
            Redirect::to('login/index');
        }
        else {
            Redirect::to('login/register');
        }
    }

    public function logout()
    {
        LoginModel::logout();
        Redirect::home();
        exit();
    }

    public function loginWithCookie()
    {
        $success = LoginModel::loginWithCookie(Request::cookie('remember_me'));

        // if login successful, redirect to dashboard/index ...
        if ($success) {
            Redirect::to('dashboard/index');
        } else {
            // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
            LoginModel::deleteCookie();
            Redirect::to('login/index');
        }
    }
}