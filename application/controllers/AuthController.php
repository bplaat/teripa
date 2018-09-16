<?php

class AuthController {
    public static function signin () {
        return view('auth.signin');
    }
    public static function do_signin () {
        if (Auth::signin($_POST['username'], $_POST['password'])) {
            Router::redirect('/');
        }
        $_SESSION['errors'] = [ 'Wrong username or password!' ];
        Router::back();
    }
    public static function signup () {
        return view('auth.signup');
    }
    public static function do_signup () {
        if (Auth::signup($_POST['username'], $_POST['email'], $_POST['password'])) {
            Router::redirect('/');
        }
        $_SESSION['errors'] = [ 'Username or email not unique!' ];
        Router::back();
    }
    public static function signout () {
        Auth::signout();
        Router::redirect('/');
    }
}