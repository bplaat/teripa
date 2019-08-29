<?php

class AuthController {
    public static function signin () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (Auth::signin($_POST['login'], $_POST['password'])) {
                Router::redirect('/');
            }
            Router::back();
        } else {
            view('auth/signin');
        }
    }

    public static function signup () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (
                filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
                $_POST['password'] == $_POST['confirm_password']
            ) {
                if (Auth::signup($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['email'], $_POST['password'])) {
                   Router::redirect('/');
                }
            }
            Router::back();
        } else {
            view('auth/signup');
        }
    }

    public static function signout () {
        Auth::revokeSession($_COOKIE[SESSION_COOKIE_NAME]);
        Router::redirect('/');
    }
}
