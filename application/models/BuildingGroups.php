<?php

class BuildingGroups extends Model {
    protected static $table = 'building_groups';

    public static function create () {
        $query = Database::query('CREATE TABLE `building_groups` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL
        )');

        static::insert(['name' => 'Income' ]);
        static::insert(['name' => 'Defence' ]);

        return $query;
    }
}
