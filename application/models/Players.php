<?php

class Players extends Model {
    public static function create () {
        return Database::query('CREATE TABLE `players` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `first_name` VARCHAR(255) NOT NULL,
            `last_name` VARCHAR(255) NOT NULL,
            `username` VARCHAR(255) UNIQUE NOT NULL,
            `email` VARCHAR(255) UNIQUE NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` TINYINT UNSIGNED NOT NULL,
            `money` BIGINT UNSIGNED NOT NULL,
            `income` BIGINT UNSIGNED NOT NULL,
            `attack` INT UNSIGNED NOT NULL,
            `defence` INT UNSIGNED NOT NULL,
            `paid_at` DATETIME NOT NULL,
            `won` INT UNSIGNED NOT NULL,
            `lost` INT UNSIGNED NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )');
    }
}
