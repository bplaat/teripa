<?php

Router::any('/', 'PagesController::home');

Router::get('/auth/login', 'AuthController::showLoginForm');
Router::post('/auth/login', 'AuthController::login');
Router::get('/auth/register', 'AuthController::showRegisterForm');
Router::post('/auth/register', 'AuthController::register');

Router::fallback('PagesController::notFound');
