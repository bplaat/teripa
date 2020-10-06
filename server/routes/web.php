<?php

Router::any('/', 'PagesController::home');

if (Auth::check()) {
    Router::get('/auth/settings', 'SettingsController::showSettingsForm');
    Router::post('/auth/settings/change_details', 'SettingsController::changeDetails');
    Router::post('/auth/settings/change_password', 'SettingsController::changePassword');
    Router::get('/auth/sessions/{Sessions}/revoke', 'SettingsController::revokeSession');

    Router::get('/auth/logout', 'AuthController::logout');
} else {
    Router::get('/auth/login', 'AuthController::showLoginForm');
    Router::post('/auth/login', 'AuthController::login');
    Router::get('/auth/register', 'AuthController::showRegisterForm');
    Router::post('/auth/register', 'AuthController::register');
}

Router::fallback('PagesController::notFound');
