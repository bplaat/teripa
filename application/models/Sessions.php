<?php

class Sessions extends Model {
    protected static $primaryKey = 'session';

    public static function create () {
        return Database::query('CREATE TABLE `sessions` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `session` VARCHAR(255) UNIQUE NOT NULL,
            `player_id` INT UNSIGNED NOT NULL,
            `expires_at` DATETIME NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )');
    }
}
