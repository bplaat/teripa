<?php

class UnitGroups extends Model {
    protected static $table = 'unit_groups';

    public static function create () {
        $query = Database::query('CREATE TABLE `unit_groups` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL
        )');

        static::insert(['name' => 'Calvery' ]);
        static::insert(['name' => 'Navy' ]);
        static::insert(['name' => 'Airforce' ]);

        return $query;
    }
}
