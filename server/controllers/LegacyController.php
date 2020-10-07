<?php

class LegacyController {
    public static function showLegacyEmbed(): string {
        return view('legacy');
    }

    public static function embed(): string {
        $p = '<!DOCTYPE html>';
        $p .= '<html lang="en">';
        $p .= '<head>';
        $p .= '<meta charset="UTF-8">';
        $p .= '<title>Battle of Teripa</title>';
        $p .= '<link rel="stylesheet" href="/css/legacy.css">';
        $p .= '</head>';
        $p .= '<body>';

        $tileInfo = [
            '00' => [
                'name' => 'Grass'
            ],

            '01' => [
                'name' => 'Forest'
            ],

            '02' => [
                'name' => 'Mountain'
            ],

            '20' => [
                'name' => 'Castle - Battle'
            ],

            '10' => [
                'name' => 'Farm',
                'description' => 'Increase your food income with 50',
                'food' => 0,
                'wood' => 250,
                'gold' => 250
            ],

            '11' => [
                'name' => 'Woodcutter',
                'description' => 'Increase your wood income with 50',
                'food' => 250,
                'wood' => 0,
                'gold' => 250
            ],

            '12' => [
                'name' => 'Gold Mine',
                'description' => 'Increase your gold income with 50',
                'food' => 250,
                'wood' => 250,
                'gold' => 0
            ],

            '21' => [
                'name' => 'Baracks',
                'description' => 'Recruit an army to attack and defend',
                'food' => 500,
                'wood' => 500,
                'gold' => 500
            ],

            '22' => [
                'name' => 'Church',
                'description' => 'Increase your defence with 500',
                'food' => 500,
                'wood' => 500,
                'gold' => 500
            ]
        ];

        $unitsInfo = [
            [
                'name' => 'Swordsman',
                'attack' => 10,
                'defence' => 10,
                'food' => 50,
                'wood' => 0,
                'gold' => 50
            ],

            [
                'name' => 'Archer',
                'attack' => 10,
                'defence' => 25,
                'food' => 50,
                'wood' => 25,
                'gold' => 50
            ],

            [
                'name' => 'Spearman',
                'attack' => 25,
                'defence' => 10,
                'food' => 50,
                'wood' => 50,
                'gold' => 25
            ],

            [
                'name' => 'Knight',
                'attack' => 50,
                'defence' => 50,
                'food' => 100,
                'wood' => 50,
                'gold' => 100
            ]
        ];

        $user = Users::select(Auth::id())->fetch();
        $player = LegacyPlayers::select([ 'user_id' => Auth::id() ])->fetch();
        $player->map = json_decode($player->map);
        $player->units = json_decode($player->units);

        $player->farms_count = 0;
        $player->woodcutters_count = 0;
        $player->mines_count = 0;
        $player->barrackes_count = 0;
        $player->churches_count = 0;
        for ($y = 0; $y < 5; $y++) {
            for ($x = 0; $x < 5; $x++) {
                if ($player->map[$y][$x] == '10') $player->farms_count++;
                if ($player->map[$y][$x] == '11') $player->woodcutters_count++;
                if ($player->map[$y][$x] == '12') $player->mines_count++;
                if ($player->map[$y][$x] == '21') $player->barrackes_count++;
                if ($player->map[$y][$x] == '22') $player->churches_count++;
            }
        }

        $player->until_payment = time() - strtotime($player->payed_at);
        if ($player->until_payment > 60 * 60) {
            $player->food += $player->farms_count * 50 * floor($player->until_payment / 60 * 60);
            $player->wood += $player->woodcutters_count * 50 * floor($player->until_payment / 60 * 60);
            $player->gold += $player->mines_count * 50 * floor($player->until_payment / 60 * 60);

            LegacyPlayers::update($player->id, [
                'food' => $player->food,
                'wood' => $player->wood,
                'gold' => $player->gold,
                'payed_at' => date('Y-m-d H:i:s', time() - $player->until_payment % 60 * 60)
            ]);
        }

        if (request('player') != '') {
            $otherUserQuery = Users::select(request('player'));
            if ($otherUserQuery->rowCount() == 1) {
                $otherUser = $otherUserQuery->fetch();
                $otherPlayer = LegacyPlayers::select([ 'user_id' => request('player') ])->fetch();
                $otherPlayer->map = json_decode($otherPlayer->map);
                $otherPlayer->units = json_decode($otherPlayer->units);

                $otherPlayer->churches_count = 0;
                for ($y = 0; $y < 5; $y++) {
                    for ($x = 0; $x < 5; $x++) {
                        if ($otherPlayer->map[$y][$x] == '22') {
                            $otherPlayer->churches_count++;
                        }
                    }
                }
            }

            else {
                Router::redirect('embed');
            }
        }

        $page = request('page');

        $mapX = is_numeric(request('x')) ? (int)request('x') : null;
        if ($mapX != null && ($mapX < 0 || $mapX >= 5)) {
            Router::redirect('embed');
        }

        $mapY = is_numeric(request('y')) ? (int)request('y') : null;
        if ($mapY != null && ($mapY < 0 || $mapY >= 5)) {
            Router::redirect('embed');
        }

        $tile = request('tile');
        $unit = request('unit');

        if ($page == 'battle') {
            $player->attack = 0;
            for ($i = 0; $i < count($unitsInfo); $i++) {
                $player->attack += $player->units[$i] * $unitsInfo[$i]['attack'];
            }

            $otherPlayer->defence = $otherPlayer->churches_count * 500;
            for ($i = 0; $i < count($unitsInfo); $i++) {
                $otherPlayer->defence += $otherPlayer->units[$i] * $unitsInfo[$i]['defence'];
            }

            if ($player->attack > $otherPlayer->defence) {
                for ($i = 0; $i < count($unitsInfo); $i++) {
                    $lost = rand(0, rand(0, floor(($player->attack - $otherPlayer->defence) / 500)));
                    if ($otherPlayer->units[$i] > $lost) {
                        $otherPlayer->units[$i] -= $lost;
                    } else {
                        $otherPlayer->units[$i] = 0;
                    }
                }

                $otherPlayer->defence = $otherPlayer->churches_count * 500;
                for ($i = 0; $i < count($unitsInfo); $i++) {
                    $otherPlayer->defence += $otherPlayer->units[$i] * $unitsInfo[$i]['defence'];
                }

                $player->battleFood = rand(0, rand(0, $otherPlayer->food / 4));
                $player->battleWood = rand(0, rand(0, $otherPlayer->wood / 4));
                $player->battleGold = rand(0, rand(0, $otherPlayer->gold / 2));

                $player->food += $player->battleFood;
                $player->wood += $player->battleWood;
                $player->gold += $player->battleGold;

                LegacyPlayers::update($otherPlayer->id, [
                    'food' => $otherPlayer->food - $player->battleFood,
                    'wood' => $otherPlayer->wood - $player->battleWood,
                    'gold' => $otherPlayer->gold - $player->battleGold,
                    'lost' => $otherPlayer->lost + 1,
                    'units' => json_encode($otherPlayer->units)
                ]);

                LegacyPlayers::update($player->id, [
                    'food' => $player->food,
                    'wood' => $player->wood,
                    'gold' => $player->gold,
                    'won' => $player->won + 1
                ]);
            }

            else {
                for ($i = 0; $i < count($unitsInfo); $i++) {
                    $lost = rand(0, rand(0, floor(($otherPlayer->defence - $player->attack) / 1000)));
                    if ($player->units[$i] > $lost) {
                        $player->units[$i] -= $lost;
                    } else {
                        $player->units[$i] = 0;
                    }
                }

                $player->attack = 0;
                for ($i = 0; $i < count($unitsInfo); $i++) {
                    $player->attack += $player->units[$i] * $unitsInfo[$i]['attack'];
                }

                LegacyPlayers::update($otherPlayer->id, [
                    'won' => $otherPlayer->won + 1
                ]);

                LegacyPlayers::update($player->id, [
                    'lost' => $player->lost + 1,
                    'units' => json_encode($player->units)
                ]);
            }
        }

        if ($page == 'visit') {
            $p .= '<div class="bar">City of ' . $otherUser->username . '<a class="right" href="?page=map&x=2&y=2">Back to your castle</a></div>';
        } else {
            $p .= '<div class="bar">';
            $p .= 'Food: ' . $player->food . ' (+' . $player->farms_count * 50 . '/h) ';
            $p .= 'Wood: ' . $player->wood . ' (+' . $player->woodcutters_count * 50 . '/h) ';
            $p .= 'Gold: ' . $player->gold . ' (+' . $player->mines_count * 50 . '/h)';
            $p .= '<span class="right">' . date('i:s', 60 * 60 - 1 - $player->until_payment) . '</span>';
            $p .= '</div>';
        }

        if ($page == 'map') {
            $tile = $player->map[$mapY][$mapX];

            $p .= '<div class="page">';

            $p .= '<div class="title">';
            $p .= $tileInfo[$tile]['name'];
            $p .= '<span class="right">';
            if ($tile == '20') {
                $search = request('q');

                $p .= '<form class="inline">';
                $p .= '<input type="hidden" name="page" value="map">';
                $p .= '<input type="hidden" name="x" value="2">';
                $p .= '<input type="hidden" name="y" value="2">';
                $p .= '<input type="hidden" name="sort" value="name">';
                $p .= '<input type="text" name="q" value="' . $search . '" placeholder="Search name">';
                $p .= '<input type="submit" value="Search">';
                $p .= '</form>';
            }
            $p .= '<a class="close" href="/legacy/embed">&times;</a>';
            $p .= '</span>';
            $p .= '</div>';

            $buyTitle = function (string $tile) use($player, $tileInfo, $mapX, $mapY): string {
                $p = '<table>';
                $p .= '<tr>';
                $p .= '<td width="144"><div class="tile" style="background-position:-' . $tile[0] * 128  . 'px -' . $tile[1] * 92 . 'px"></div></td>';
                $p .= '<td width="279">';
                $p .= '<b>' . $tileInfo[$tile]['name'] . '</b><br>';
                $p .= $tileInfo[$tile]['description'] . '<br>';
                $p .= 'Cost: ' . ($tileInfo[$tile]['food'] > 0 ? $tileInfo[$tile]['food'] . ' food' : '');
                $p .= ($tileInfo[$tile]['wood'] > 0 ? ($tileInfo[$tile]['food'] > 0 ? ', ' : '') . $tileInfo[$tile]['wood'] . ' wood' : '');
                $p .= ($tileInfo[$tile]['gold'] > 0 ? ', ' . $tileInfo[$tile]['gold'] . ' gold' : '');
                $p .= '</td>';

                $p .= '<td>';
                if ($player->food >= $tileInfo[$tile]['food'] && $player->wood >= $tileInfo[$tile]['wood'] && $player->gold >= $tileInfo[$tile]['gold']) {
                    $p .= '<a href="?page=build&tile=' . $tile . '&x=' . $mapX . '&y=' . $mapY . '">Build</a>';
                } else {
                    $p .= 'Not enough';
                }
                $p .= '</td>';
                $p .= '</tr>';
                $p .= '</table>';

                return $p;
            };

            if ($tile == '00') {
                $p .= $buyTitle('10');
                $p .= $buyTitle('21');
                $p .= $buyTitle('22');
            }

            if ($tile == '01') {
                $p .= $buyTitle('11');
            }

            if ($tile == '02') {
                $p .= $buyTitle('12');
            }

            if ($tile == '10') {
                $p .= '<p>All your farms produce ' . $player->farms_count * 50 . ' food per hour</p>';
            }

            if ($tile == '11') {
                $p .= '<p>All your woodcutters produce ' . $player->woodcutters_count * 50 . ' wood per hour</p>';
            }

            if ($tile == '12') {
                $p .= '<p>All your gold mines produce ' . $player->mines_count * 50 . ' gold per hour</p>';
            }

            if ($tile == '20') {
                $sort = request('sort');

                $sortTable = $sort == 'name' ? '`users`.`username` ASC' : ($sort == 'lost' ? '`legacy_players`.`lost` DESC' : '`legacy_players`.`won` DESC');
                if ($search != '') {
                    $players = Database::query('SELECT `users`.`id` AS `user_id`, `users`.`username`, `legacy_players`.`won`, `legacy_players`.`lost` FROM `legacy_players`INNER JOIN `users` ON `users`.`id` = `legacy_players`.`user_id` WHERE `username` LIKE ? ORDER BY ' . $sortTable, '%' . $search . '%')->fetchAll();
                } else {
                    $players = Database::query('SELECT `users`.`id` AS `user_id`, `users`.`username`, `legacy_players`.`won`, `legacy_players`.`lost` FROM `legacy_players`INNER JOIN `users` ON `users`.`id` = `legacy_players`.`user_id` ORDER BY ' . $sortTable)->fetchAll();
                }

                $p .= '<table>';
                $p .= '<tr>';
                $p .= '<th><a href="?page=map&x=2&y=2&sort=name">Name</a>' . ($sort == 'name' ? ' &darr;' : '') . '</th>';
                $p .= '<th><a href="?page=map&x=2&y=2">Won</a>' . ($sort != 'name' && $sort != 'lost' ? ' &uarr;' : '') . '</th>';
                $p .= '<th><a href="?page=map&x=2&y=2&sort=lost">Lost</a>' . ($sort == 'lost' ? ' &uarr;' : '') . '</th>';
                $p .= '<th>Actions</th>';
                $p .= '</tr>';

                if (count($players) > 0) {
                    foreach ($players as $player) {
                        $p .= '<tr>';
                        $p .= '<td><a href="?page=visit&player=' . $player->user_id . '">' . $player->username . '</a></td>';
                        $p .= '<td>' . $player->won . '</td>';
                        $p .= '<td>' . $player->lost . '</td>';
                        $p .= '<td>' . ($player->user_id == Auth::id() ? 'You' : '<a href="?page=battle&player=' . $player->user_id . '">Attack</a>') . '</td>';
                        $p .= '</tr>';
                    }

                } else {
                    $p .= '<tr><td colspan="5">No search results</td></tr>';
                }
                $p .= '</table>';
            }

            if ($tile == '21') {
                for ($i = 0; $i < count($unitsInfo); $i++) {
                    $p .= '<table>';
                    $p .= '<tr>';
                    $p .= '<td width="48"><div class="unit" style="background-position:-384px -' . $i * 32 . 'px"></div></td>';
                    $p .= '<td width="300">';
                    $p .= '<b>' . $unitsInfo[$i]['name'] . '</b><br>';
                    $p .= 'Atack ' . $unitsInfo[$i]['attack'] . ' Defence ' . $unitsInfo[$i]['defence'] . '<br>';
                    $p .= 'Cost: ' . ($unitsInfo[$i]['food'] > 0 ? $unitsInfo[$i]['food'] . ' food' : '');
                    $p .= ($unitsInfo[$i]['wood'] > 0 ? ', ' . $unitsInfo[$i]['wood'] . ' wood' : '');
                    $p .= ($unitsInfo[$i]['gold'] > 0 ? ', ' . $unitsInfo[$i]['gold'] . ' gold' : '');
                    $p .= '</td>';
                    $p .= '<td width="75">' . $player->units[$i] . ' / ' . $player->barrackes_count * 10 . '</td>';
                    if ($player->units[$i] < $player->barrackes_count * 10) {
                        if ($player->food >= $unitsInfo[$i]['food'] && $player->wood >= $unitsInfo[$i]['wood'] && $player->gold >= $unitsInfo[$i]['gold']) {
                            $p .= '<td><a href="?page=recruit&unit=' . $i . '&x=' . $mapX . '&y=' . $mapY . '">Recruit</a></td>';
                        } else {
                            $p .= '<td>Not enough</td>';
                        }
                    } else {
                        $p .= '<td>Not enough place</td>';
                    }
                    $p .= '</tr>';
                    $p .= '</table>';
                }
            }

            if ($tile == '22') {
                $p .= '<p>All your churches prays for extra ' . $player->churches_count * 500 . ' defence</p>';
            }

            if ($tile[0] != '0' && $tile != '20') {
                $p .= '<p><a href="?page=destroy&x=' . $mapX . '&y=' . $mapY . '">Destroy' . ($tile == '21' ? ' extra soldiers will be removed' : '') . ' (get 50% back)</a></p>';
            }

            $p .= '</div>';
        }

        else if ($page == 'battle') {
            $p .= '<div class="page">';
            $p .= '<div class="title">You ' . ($player->attack > $otherPlayer->defence ? 'won' : 'lost') . ' the battle <a class="right close" href="?page=map&x=2&y=2">&times;</a></div>';

            $p .= '<p>';
            $p .= $user->username . ' (' . $player->attack . '):';
            for ($i = 0; $i < count($unitsInfo); $i++) {
                $p .= ' <div class="unit" style="background-position:-384px -' . $i * 32 . 'px"></div> ' . $player->units[$i];
            }
            $p .= '</p>';

            $p .= '<p>';
            $p .= $otherUser->username . ' (' . $otherPlayer->defence .'):';
            for ($i = 0; $i < count($unitsInfo); $i++) {
                $p .= ' <div class="unit" style="background-position:-384px -' . $i * 32 . 'px"></div> ' . $otherPlayer->units[$i];
            }
            $p .= ' <div class="unit" style="background-position:-384px -128px;"></div> ' . $otherPlayer->churches_count;
            $p .= '</p>';

            if ($player->attack > $otherPlayer->defence) {
                $p .= '<p>You have stolen: ' . $player->battleFood . ' food, ' . $player->battleWood . ' wood, ' . $player->battleGold . ' gold!</p>';
            } else {
                $p .= '<p>You have stolen: nothing, because you lose!</p>';
            }

            $p .= '<p><a href="?page=battle&player=' . $otherUser->id . '">Attack again</a>';

            $p .= '</div>';
        }

        else if ($page == 'recruit') {
            if (
                $player->units[$unit] < $player->barrackes_count * 10 &&
                $player->food >= $unitsInfo[$unit]['food'] &&
                $player->wood >= $unitsInfo[$unit]['wood'] &&
                $player->gold >= $unitsInfo[$unit]['gold']
            ) {
                $player->food -= $unitsInfo[$unit]['food'];
                $player->wood -= $unitsInfo[$unit]['wood'];
                $player->gold -= $unitsInfo[$unit]['gold'];
                $player->units[$unit]++;

                LegacyPlayers::update($player->id, [
                    'food' => $player->food,
                    'wood' => $player->wood,
                    'gold' => $player->gold,
                    'units' => json_encode($player->units)
                ]);
            }

            Router::redirect('?page=map&x=' . $mapX . '&y=' . $mapY);
        }

        else if ($page == 'build') {
            if (
                $player->food >= $tileInfo[$tile]['food'] &&
                $player->wood >= $tileInfo[$tile]['wood'] &&
                $player->gold >= $tileInfo[$tile]['gold']
            ) {
                $player->food -= $tileInfo[$tile]['food'];
                $player->wood -= $tileInfo[$tile]['wood'];
                $player->gold -= $tileInfo[$tile]['gold'];
                $player->map[$mapY][$mapX] = $tile;

                LegacyPlayers::update($player->id, [
                    'food' => $player->food,
                    'wood' => $player->wood,
                    'gold' => $player->gold,
                    'map' => json_encode($player->map)
                ]);
            }

            Router::redirect('embed');
        }

        else if ($page == 'destroy') {
            $tile = $player->map[$mapY][$mapX];

            $player->food += $tileInfo[$tile]['food'] / 2;
            $player->wood += $tileInfo[$tile]['wood'] / 2;
            $player->gold += $tileInfo[$tile]['gold'] / 2;

            if ($player->map[$mapY][$mapX] == '21') {
                $player->barrackes_count--;
                for ($i = 0; $i < count($unitsInfo); $i++) {
                    if ($player->units[$i] > $player->barrackes_count * 10) {
                        $player->units[$i] = $player->barrackes_count * 10;
                    }
                }
            }

            if ($tile == '11') {
                $player->map[$mapY][$mapX] = '01';
            }
            else if ($tile == '12') {
                $player->map[$mapY][$mapX] = '02';
            }
            else {
                $player->map[$mapY][$mapX] = '00';
            }

            LegacyPlayers::update($player->id, [
                'food' => $player->food,
                'wood' => $player->wood,
                'gold' => $player->gold,
                'map' => json_encode($player->map),
                'units' => json_encode($player->units)
            ]);

            Router::redirect('embed');
        }

        else if ($page == 'visit') {
            for ($y = 0; $y < 5; $y++) {
                for ($x = 0; $x < 5; $x++) {
                    $p .= '<div class="tile" style="background-position: -' . $otherPlayer->map[$y][$x][0] * 128 . 'px -' . $otherPlayer->map[$y][$x][1] * 92 . 'px" ' .
                        'title="' . $tileInfo[$otherPlayer->map[$y][$x]]['name'] . '"></div>';
                }
            }
        }

        else {
            for ($y = 0; $y < 5; $y++) {
                for ($x = 0; $x < 5; $x++) {
                    $p .= '<a class="tile" href="?page=map&x=' . $x . '&y=' . $y . '" style="background-position: -' . $player->map[$y][$x][0] * 128  . 'px ' .
                        '-' . $player->map[$y][$x][1] * 92 . 'px" title="' . $tileInfo[$player->map[$y][$x]]['name'] . '"></a>';
                }
            }
        }

        $p .= '</body>';
        $p .= '</html>';

        return $p;
    }
}
