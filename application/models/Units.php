<?php

class Units extends Model {
    public static function create () {
        $query = Database::query('CREATE TABLE `units` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `unit_group_id` INT UNSIGNED NOT NULL,
            `position` INT UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `price` BIGINT UNSIGNED NOT NULL,
            `attack` BIGINT UNSIGNED NOT NULL,
            `defence` BIGINT UNSIGNED NOT NULL
        )');

        static::insert([ 'unit_group_id' => 1, 'position' => 1, 'name' => 'Minigunner', 'price' => 500, 'attack' => 3, 'defence' => 3 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 2, 'name' => 'Military Car', 'price' => 2500, 'attack' => 6, 'defence' => 6 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 3, 'name' => 'Humvee', 'price' => 4500, 'attack' => 9, 'defence' => 3 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 4, 'name' => 'Truck', 'price' => 12500, 'attack' => 18, 'defence' => 12 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 5, 'name' => 'Light Artillery', 'price' => 25000, 'attack' => 27, 'defence' => 18 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 6, 'name' => 'Calvary Carrier', 'price' => 60000, 'attack' => 15, 'defence' => 54 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 7, 'name' => 'MGM-51 Shillelagh', 'price' => 160000, 'attack' => 60, 'defence' => 39 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 8, 'name' => 'Calvary Transporter', 'price' => 300000, 'attack' => 30, 'defence' => 105 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 9, 'name' => 'Assault Vehicle', 'price' => 510000, 'attack' => 84, 'defence' => 72 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 10, 'name' => 'SSM Launcher', 'price' => 700000, 'attack' => 126, 'defence' => 51 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 11, 'name' => 'Light Hovercraft', 'price' => 850000, 'attack' => 57, 'defence' => 114 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 12, 'name' => 'M1 Abrams Tank', 'price' => 1100000, 'attack' => 135, 'defence' => 90 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 13, 'name' => 'Calvary Vehicle', 'price' => 2000000, 'attack' => 108, 'defence' => 150 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 14, 'name' => 'Anti-ambush Vehicle', 'price' => 2800000, 'attack' => 138, 'defence' => 153 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 15, 'name' => 'Heavy Tank', 'price' => 3700000, 'attack' => 162, 'defence' => 162 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 16, 'name' => 'Leopard 1A1', 'price' => 4500000, 'attack' => 213, 'defence' => 147 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 17, 'name' => 'HG-544 Vehicle', 'price' => 5700000, 'attack' => 249, 'defence' => 171 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 18, 'name' => 'Tracked Carrier', 'price' => 7000000, 'attack' => 270, 'defence' => 180 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 19, 'name' => 'Combat Vehicle', 'price' => 8500000, 'attack' => 345, 'defence' => 150 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 20, 'name' => 'Hellfire Tank', 'price' => 10000000, 'attack' => 324, 'defence' => 216 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 21, 'name' => 'Battle Tank', 'price' => 13000000, 'attack' => 375, 'defence' => 234 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 22, 'name' => 'Mounted Combat System', 'price' => 15000000, 'attack' => 390, 'defence' => 246 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 23, 'name' => 'Self Propelled Artillery', 'price' => 17000000, 'attack' => 420, 'defence' => 255 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 24, 'name' => 'Chaparral Tank', 'price' => 19000000, 'attack' => 411, 'defence' => 282 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 25, 'name' => 'M198 Howitzer', 'price' => 21000000, 'attack' => 468, 'defence' => 288 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 26, 'name' => 'Heavy Artillery', 'price' => 23000000, 'attack' => 429, 'defence' => 315 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 27, 'name' => 'Missile Launcher', 'price' => 25000000, 'attack' => 549, 'defence' => 450 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 28, 'name' => 'Mobile Anti Aircraft', 'price' => 28000000, 'attack' => 607, 'defence' => 550 ]);
        static::insert([ 'unit_group_id' => 1, 'position' => 29, 'name' => 'ICBM Launcher', 'price' => 30000000, 'attack' => 671, 'defence' => 575 ]);

        static::insert([ 'unit_group_id' => 2, 'position' => 1, 'name' => 'Rubber boat', 'price' => 2000, 'attack' => 12, 'defence' => 6 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 2, 'name' => 'Transport', 'price' => 10000, 'attack' => 6, 'defence' => 21 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 3, 'name' => 'Frigate', 'price' => 30000, 'attack' => 33, 'defence' => 15 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 4, 'name' => 'Sea Fighter', 'price' => 40000, 'attack' => 90, 'defence' => 80 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 5, 'name' => 'Naval Tanker', 'price' => 90000, 'attack' => 24, 'defence' => 60 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 6, 'name' => 'Naval Destroyer', 'price' => 200000, 'attack' => 81, 'defence' => 45 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 7, 'name' => 'M80 Stiletto', 'price' => 490000, 'attack' => 45, 'defence' => 105 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 8, 'name' => 'Submarine', 'price' => 620000, 'attack' => 135, 'defence' => 30 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 9, 'name' => 'Helicopter Carrier', 'price' => 920000, 'attack' => 84, 'defence' => 102 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 10, 'name' => 'Battleship', 'price' => 1100000, 'attack' => 126, 'defence' => 84 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 11, 'name' => 'Houbei Missile ship', 'price' => 2000000, 'attack' => 120, 'defence' => 180 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 12, 'name' => 'Horizon Frigate', 'price' => 2800000, 'attack' => 135, 'defence' => 195 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 13, 'name' => 'Type 13 Frigate', 'price' => 3800000, 'attack' => 186, 'defence' => 186 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 14, 'name' => 'Destroyer', 'price' => 4800000, 'attack' => 240, 'defence' => 150 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 15, 'name' => 'Scorpene Submarine', 'price' => 5700000, 'attack' => 222, 'defence' => 222 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 16, 'name' => 'DDG 1000 Destroyer', 'price' => 6900000, 'attack' => 270, 'defence' => 210 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 17, 'name' => 'Elite Dreadnought', 'price' => 8600000, 'attack' => 282, 'defence' => 234 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 18, 'name' => 'SPY-1D Destroyer', 'price' => 11000000, 'attack' => 285, 'defence' => 279 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 19, 'name' => 'HSV-2 Swift', 'price' => 16000000, 'attack' => 330, 'defence' => 276 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 20, 'name' => 'Missile Cruiser', 'price' => 19000000, 'attack' => 285, 'defence' => 345 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 21, 'name' => 'UXV Combatant', 'price' => 22000000, 'attack' => 390, 'defence' => 270 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 22, 'name' => 'Anti Submarine ship', 'price' => 14000000, 'attack' => 321, 'defence' => 363 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 23, 'name' => 'Kanimbla AT ship', 'price' => 25000000, 'attack' => 354, 'defence' => 375 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 24, 'name' => 'Aircraft Carrier', 'price' => 27000000, 'attack' => 420, 'defence' => 395 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 25, 'name' => 'Type 23 Frigate', 'price' => 30000000, 'attack' => 360, 'defence' => 402 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 26, 'name' => 'Landing Platform Dock', 'price' => 33000000, 'attack' => 650, 'defence' => 420 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 27, 'name' => 'Type 28 Frigate', 'price' => 36000000, 'attack' => 565, 'defence' => 600 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 28, 'name' => 'Nuclear Cruiser', 'price' => 45000000, 'attack' => 742, 'defence' => 700 ]);
        static::insert([ 'unit_group_id' => 2, 'position' => 29, 'name' => 'Ohio ICBM Submarine', 'price' => 50000000, 'attack' => 797, 'defence' => 725 ]);

        static::insert([ 'unit_group_id' => 3, 'position' => 1, 'name' => 'Cessna 172', 'price' => 7500, 'attack' => 18, 'defence' => 3 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 2, 'name' => 'C-17 Cargo plane', 'price' => 14000, 'attack' => 18, 'defence' => 12 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 3, 'name' => 'V-22A Osprey Gunship', 'price' => 32000, 'attack' => 36, 'defence' => 9 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 4, 'name' => 'AV-8B Harrier Jet', 'price' => 100000, 'attack' => 72, 'defence' => 24 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 5, 'name' => 'Comanche Helicopter', 'price' => 210000, 'attack' => 75, 'defence' => 39 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 6, 'name' => 'Havoc Helicopter', 'price' => 670000, 'attack' => 100, 'defence' => 6 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 7, 'name' => 'Saab JA37 Viggen', 'price' => 1000000, 'attack' => 145, 'defence' => 18 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 8, 'name' => 'SR-71 Blackbird', 'price' => 1400000, 'attack' => 156, 'defence' => 90 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 9, 'name' => 'Apache Helicopter', 'price' => 1700000, 'attack' => 190, 'defence' => 5 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 10, 'name' => 'B-2 Spirit Bomber', 'price' => 2800000, 'attack' => 330, 'defence' => 115 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 11, 'name' => 'F-18 Super Hornet', 'price' => 4700000, 'attack' => 351, 'defence' => 30 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 12, 'name' => 'F-15 Silent Eagle', 'price' => 6800000, 'attack' => 426, 'defence' => 36 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 13, 'name' => 'B-1 Lancer', 'price' => 4800000, 'attack' => 306, 'defence' => 195 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 14, 'name' => 'F3 Interceptor', 'price' => 8800000, 'attack' => 480, 'defence' => 90 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 15, 'name' => 'F-16 Falcon', 'price' => 10000000, 'attack' => 546, 'defence' => 48 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 16, 'name' => 'PAK-FA Fighter', 'price' => 14000000, 'attack' => 570, 'defence' => 54 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 17, 'name' => 'F-117 Nighthawk', 'price' => 26000000, 'attack' => 480, 'defence' => 210 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 18, 'name' => 'Boeing NGB Bomber', 'price' => 28000000, 'attack' => 175, 'defence' => 350 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 19, 'name' => 'F-14 Tomcat', 'price' => 29000000, 'attack' => 552, 'defence' => 216 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 20, 'name' => 'F-35 Lightning II', 'price' => 30000000, 'attack' => 495, 'defence' => 300 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 21, 'name' => 'B-52 Stratofortress', 'price' => 35000000, 'attack' => 699, 'defence' => 500 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 22, 'name' => 'F-22 Raptor', 'price' => 40000000, 'attack' => 735, 'defence' => 600 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 23, 'name' => 'Trident II Missile', 'price' => 42000000, 'attack' => 833, 'defence' => 800 ]);
        static::insert([ 'unit_group_id' => 3, 'position' => 24, 'name' => 'Titan ICBM Missile', 'price' => 4400000, 'attack' => 882, 'defence' => 825 ]);

        return $query;
    }
}
