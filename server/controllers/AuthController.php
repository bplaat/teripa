<?php

class AuthController {
    public static function showLoginForm(): string {
        return view('auth.login');
    }

    public static function login(): void {
        if (Auth::login(request('login'), request('password'))) {
            Router::redirect('/');
        } else {
            Session::flash('errors', [
                'Incorrect username, email or password'
            ]);
            Router::back();
        }
    }

    public static function showRegisterForm(): string {
        return view('auth.register');
    }

    public static function register(): void {
        validate([
            'username' => Users::USERNAME_VALIDATION,
            'email' => Users::EMAIL_VALIDATION,
            'password' => Users::PASSWORD_VALIDATION
        ]);

        $userId = Users::insert([
            'username' => request('username'),
            'email' => request('email'),
            'password' => password_hash(request('password'), PASSWORD_DEFAULT)
        ]);

        Auth::createSession($userId);

        Router::redirect('/');
    }

    public static function logout(): void {
        Auth::revokeSession($_COOKIE[SESSION_COOKIE_NAME]);

        Session::flash('messages', [
            'You have logout successfully'
        ]);

        Router::redirect('/auth/login');
    }
}
