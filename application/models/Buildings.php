<?php

class Buildings extends Model {
    public static function create () {
        $query = Database::query('CREATE TABLE `buildings` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `building_group_id` INT UNSIGNED NOT NULL,
            `position` INT UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `price` BIGINT UNSIGNED NOT NULL,
            `income` BIGINT UNSIGNED NOT NULL,
            `defence` BIGINT UNSIGNED NOT NULL
        )');

        static::insert([ 'building_group_id' => 1, 'position' => 1, 'name' => 'Supply Depot', 'price' => 2500, 'income' => 1, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 2, 'name' => 'Refinery', 'price' => 10000, 'income' => 5, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 3, 'name' => 'Weapons Factory', 'price' => 40000, 'income' => 10, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 4, 'name' => 'Communications Centre', 'price' => 160000, 'income' => 20, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 5, 'name' => 'Bio Sciene Factory', 'price' => 500000, 'income' => 40, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 6, 'name' => 'Oil Rig', 'price' => 1000000, 'income' => 80, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 7, 'name' => 'Military Research Lab', 'price' => 2000000, 'income' => 160, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 8, 'name' => 'Nuclear Testing Facility', 'price' => 4000000, 'income' => 300, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 9, 'name' => 'Water Treatment Facility', 'price' => 10000000, 'income' => 500, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 10, 'name' => 'Solar Satellite Network', 'price' => 1200000, 'income' => 1250, 'defence' => 0 ]);
        static::insert([ 'building_group_id' => 1, 'position' => 11, 'name' => 'Chip Laboratory', 'price' => 14000000, 'income' => 2500, 'defence' => 0 ]);

        static::insert([ 'building_group_id' => 2, 'position' => 1, 'name' => 'Bunker', 'price' => 1000, 'income' => 0, 'defence' => 10 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 2, 'name' => 'Coast Bunker', 'price' => 1000, 'income' => 0, 'defence' => 10 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 3, 'name' => 'Anti Aircraft gun', 'price' => 1000, 'income' => 0, 'defence' => 10 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 4, 'name' => 'Tank Wall', 'price' => 10000, 'income' => 0, 'defence' => 20 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 5, 'name' => 'Torpedo Launcher', 'price' => 10000, 'income' => 0, 'defence' => 20 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 6, 'name' => 'Short Range Launcher', 'price' => 10000, 'income' => 0, 'defence' => 20 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 7, 'name' => 'Gaurd Tower', 'price' => 100000, 'income' => 0, 'defence' => 50 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 8, 'name' => 'Sea Minefield', 'price' => 100000, 'income' => 0, 'defence' => 50 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 9, 'name' => 'Long Range Launcher', 'price' => 100000, 'income' => 0, 'defence' => 50 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 10, 'name' => 'Land Minefield', 'price' => 1000000, 'income' => 0, 'defence' => 100 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 11, 'name' => 'Navy Shipyard', 'price' => 1000000, 'income' => 0, 'defence' => 100 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 12, 'name' => 'Military Airfield', 'price' => 1000000, 'income' => 0, 'defence' => 100 ]);
        static::insert([ 'building_group_id' => 2, 'position' => 13, 'name' => 'Nuclear Bomb Bunker', 'price' => 10000000, 'income' => 0, 'defence' => 150 ]);

        return $query;
    }
}
