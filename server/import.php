<?php

define('ROOT', dirname(__FILE__));

require_once ROOT . '/config.php';

require_once ROOT . '/core/Database.php';
require_once ROOT . '/core/Model.php';

require_once ROOT . '/models/Users.php';
require_once ROOT . '/models/LegacyPlayers.php';

Database::connect('mysql:host=127.0.0.1;dbname=bastiaan;charset=latin1', 'bastiaan', 'bastiaan');

$players = Database::query('SELECT * FROM `bot`')->fetchAll();

Database::disconnect();

Database::connect(DATABASE_DSN, DATABASE_USER, DATABASE_PASSWORD);

Database::query('DELETE FROM `users`');

foreach ($players as $player) {
    Users::insert([
        'id' => $player->id,
        'username' => $player->username,
        'old_password' => $player->password,
        'admin' => $player->id == 1
    ]);
}

Database::query('DELETE FROM `legacy_players`');

foreach ($players as $player) {
    LegacyPlayers::insert([
        'id' => $player->id,
        'user_id' => $player->id,
        'food' => $player->food,
        'wood' => $player->wood,
        'gold' => $player->gold,
        'won' => $player->won,
        'lost' => $player->lost,
        'payed_at' => $player->last_pay,
        'map' => $player->map,
        'units' => $player->units
    ]);
}

echo 'Import successfull!';
