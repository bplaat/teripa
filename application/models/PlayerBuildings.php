<?php

class PlayerBuildings extends Model {
    protected static $table = 'player_buildings';

    public static function create () {
        return Database::query('CREATE TABLE `player_buildings` (
            `player_id` INT UNSIGNED NOT NULL,
            `building_id` INT UNSIGNED NOT NULL,
            `amount` INT UNSIGNED NOT NULL
        )');
    }
}
