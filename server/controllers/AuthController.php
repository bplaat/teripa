<?php

class AuthController {
    public static function showLoginForm(): string {
        return view('auth.login');
    }

    public static function login(): void {

    }

    public static function showRegisterForm(): string {
        return view('auth.register');
    }

    public static function register(): void {

    }
}
