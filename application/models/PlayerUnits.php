<?php

class PlayerUnits extends Model {
    protected static $table = 'player_units';

    public static function create () {
        return Database::query('CREATE TABLE `player_units` (
            `player_id` INT UNSIGNED NOT NULL,
            `unit_id` INT UNSIGNED NOT NULL,
            `amount` INT UNSIGNED NOT NULL
        )');
    }
}
