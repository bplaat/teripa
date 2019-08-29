<?php

class Buildings extends Model {
    public static function create () {
        $query = Database::query('CREATE TABLE `buildings` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `building_group_id` INT UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `price` BIGINT UNSIGNED NOT NULL,
            `income` BIGINT UNSIGNED NOT NULL,
            `defence` INT UNSIGNED NOT NULL
        )');

        static::insert([ 'building_group_id' => 1, 'name' => 'Supply Depot', 'price' => 10000, 'income' => 3, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'name' => 'Refinery', 'price' => 30000, 'income' => 10, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'name' => 'Weapons Factory', 'price' => 150000, 'income' => 30, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'name' => 'Communications Centre', 'price' => 550000, 'income' => 90, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'name' => 'Oil Rig', 'price' => 1400000, 'income' => 180, 'defence' => 0 ]);

        static::insert([ 'building_group_id' => 2, 'name' => 'Bunker', 'price' => 5000, 'income' => 0, 'defence' => 20 ]);
        static::insert([ 'building_group_id' => 2, 'name' => 'Tank Wall', 'price' => 20000, 'income' => 0, 'defence' => 40 ]);
        static::insert([ 'building_group_id' => 2, 'name' => 'Guard Tower', 'price' => 100000, 'income' => 0, 'defence' => 80 ]);
        static::insert([ 'building_group_id' => 2, 'name' => 'Land Minefield', 'price' => 400000, 'income' => 0, 'defence' => 160 ]);
        static::insert([ 'building_group_id' => 2, 'name' => 'Nuclear Bomb Bunker', 'price' => 1600000, 'income' => 0, 'defence' => 320 ]);

        static::insert([ 'building_group_id' => 1, 'name' => 'Mega Bomb', 'price' => 1000, 'income' => 1, 'defence' => 3 ]);

        return $query;
    }
}
