<?php

Router::route('/', 'PagesController::home');
Router::route('/about', 'PagesController::about');

if (!is_null(Auth::player())) {
    Router::route('/buildings', 'BuildingsController::index');
    Router::route('/buildings/(.*)', 'BuildingsController::show');

    Router::route('/units', 'UnitsController::index');
    Router::route('/units/(.*)', 'UnitsController::show');

    Router::route('/battle', 'BattleController::list');
    Router::route('/attack', 'BattleController::attack');

    Router::route('/settings', 'SettingsController::settings');
    Router::route('/change_details', 'SettingsController::change_details');
    Router::route('/change_password', 'SettingsController::change_password');
    Router::route('/revoke_session', 'SettingsController::revoke_session');

    Router::route('/signout', 'AuthController::signout');

    if (Auth::player()->role == ROLE_ADMIN) {
        Router::route('/admin', 'AdminController::index');
        Router::route('/admin/migrate', 'AdminController::migrate');

        Router::route('/admin/players', 'AdminPlayersController::index');
        Router::route('/admin/players/(.*)/steal', 'AdminPlayersController::steal');
        Router::route('/admin/players/(.*)/edit', 'AdminPlayersController::edit');
        Router::route('/admin/players/(.*)/delete', 'AdminPlayersController::delete');

        Router::route('/admin/building_groups', 'AdminBuildingGroupsController::index');
        Router::route('/admin/building_groups/create', 'AdminBuildingGroupsController::create');
        Router::route('/admin/building_groups/(.*)/edit', 'AdminBuildingGroupsController::edit');
        Router::route('/admin/building_groups/(.*)/delete', 'AdminBuildingGroupsController::delete');

        Router::route('/admin/buildings', 'AdminBuildingsController::index');
        Router::route('/admin/buildings/create', 'AdminBuildingsController::create');
        Router::route('/admin/buildings/(.*)/edit', 'AdminBuildingsController::edit');
        Router::route('/admin/buildings/(.*)/delete', 'AdminBuildingsController::delete');

        Router::route('/admin/unit_groups', 'AdminUnitGroupsController::index');
        Router::route('/admin/unit_groups/create', 'AdminUnitGroupsController::create');
        Router::route('/admin/unit_groups/(.*)/edit', 'AdminUnitGroupsController::edit');
        Router::route('/admin/unit_groups/(.*)/delete', 'AdminUnitGroupsController::delete');

        Router::route('/admin/units', 'AdminUnitsController::index');
        Router::route('/admin/units/create', 'AdminUnitsController::create');
        Router::route('/admin/units/(.*)/edit', 'AdminUnitsController::edit');
        Router::route('/admin/units/(.*)/delete', 'AdminUnitsController::delete');
    }
} else {
    Router::route('/signin', 'AuthController::signin');
    Router::route('/signup', 'AuthController::signup');
}

Router::route('(.*)', 'PagesController::notfound');
