<?php

class Units extends Model {
    public static function create () {
        $query = Database::query('CREATE TABLE `units` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `unit_group_id` INT UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `price` BIGINT UNSIGNED NOT NULL,
            `attack` INT UNSIGNED NOT NULL,
            `defence` INT UNSIGNED NOT NULL
        )');

        static::insert([ 'unit_group_id' => 1, 'name' => 'Minigunner', 'price' => 500, 'attack' => 3, 'defence' => 3 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'Military Car', 'price' => 2500, 'attack' => 6, 'defence' => 6 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'Humvee', 'price' => 4500, 'attack' => 9, 'defence' => 3 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'Truck', 'price' => 12500, 'attack' => 18, 'defence' => 12 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'Light Artillery', 'price' => 25000, 'attack' => 27, 'defence' => 18 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'Calvary Carrier', 'price' => 60000, 'attack' => 15, 'defence' => 54 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'MGM-51 Shillelagh', 'price' => 160000, 'attack' => 60, 'defence' => 39 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'Calvary Transporter', 'price' => 300000, 'attack' => 30, 'defence' => 105 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'Assault Vehicle', 'price' => 510000, 'attack' => 84, 'defence' => 72 ]);
        static::insert([ 'unit_group_id' => 1, 'name' => 'SSM Launcher', 'price' => 700000, 'attack' => 126, 'defence' => 51 ]);

        static::insert([ 'unit_group_id' => 2, 'name' => 'Cessna 172', 'price' => 2000, 'attack' => 12, 'defence' => 6 ]);

        static::insert([ 'unit_group_id' => 3, 'name' => 'Cessna 172', 'price' => 7500, 'attack' => 18, 'defence' => 3 ]);

        return $query;
    }
}
