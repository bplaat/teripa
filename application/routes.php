<?php

// Api routes
Router::get('/api/players', 'ApiController::players');
Router::get('/api/units', 'ApiController::units');
Router::any('/api/buildings', 'ApiController::buildings');

// Auth routes
if (!Auth::check()) {
    Router::get('/auth/signin', 'AuthController::signin');
    Router::post('/auth/signin', 'AuthController::do_signin');
    Router::get('/auth/signup', 'AuthController::signup');
    Router::post('/auth/signup', 'AuthController::do_signup');
} else {
    Router::get('/auth/signout', 'AuthController::signout');

    // Unit routes
    Router::get('/units', 'UnitsController::list');
    Router::get('/buy_units', 'UnitsController::buy');

    // Building routes
    Router::get('/buildings', 'BuildingsController::list');
    Router::get('/buy_buildings', 'BuildingsController::buy');
}

// Default pages routes
Router::get('/', 'PagesController::index');
Router::get('/about', 'PagesController::about');
Router::any('(.*)', 'PagesController::notfound');