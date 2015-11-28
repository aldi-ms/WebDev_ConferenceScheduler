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

    public function register()
    {
        if (LoginModel::isUserLoggedIn()) {
            Redirect::home();
        } else {
            $this->view->render('login/register');
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

        if ($success) {
            Redirect::to('dashboard/index');
        } else {
            LoginModel::deleteCookie();
            Redirect::to('login/index');
        }
    }

    /**
     * Show user profile
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    public function showProfile()
    {
        Auth::checkAuthentication();
        $this->view->render('login/showProfile', array(
            'user_name' => Session::get('user_name'),
            'user_email' => Session::get('user_email'),
            'user_account_type' => Session::get('user_account_type')
        ));
    }
}