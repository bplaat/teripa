<?php

class BuildingsController {
    public static function list () {
        $buildings = Buildings::select()->fetchAll();
        $player_buildings = PlayerBuilding::select([ 'player_id' => Auth::player()->player_id ])->fetchAll();
        foreach ($player_buildings as $building) $buildings[array_search($building->building_id, array_column($buildings, 'building_id'))]->amount = $building->amount;
        return view('player.buildings', [ 'buildings' => $buildings ]);
    }

    public static function buy () {
        $player = Auth::player();
        $building_query = Buildings::select($_GET['building_id']);
            $amount = isset($_GET['amount']) ? $_GET['amount'] : 1;
            if ($building_query->rowCount() == 1 && (string)(int)$amount == $amount) {
                $building = $building_query->fetch();
                if ($player->money >= $building->price * $amount) {
                    Database::query('UPDATE player_building SET amount = amount + ? WHERE player_id = ? AND building_id = ?', [ $amount, $player->player_id, $_GET['building_id'] ]);
                    Database::query('UPDATE players SET money = ?, income = ?, defence = ? WHERE player_id = ?', [ $player->money -= $building->price * $amount, $player->income += $building->income * $amount, $player->defence += $building->defence * $amount, $player->player_id ]);
                    Router::redirect('/buildings');
                } else {
                    echo json_encode([ 'no_money' => false ]);
                }
            } else {
                http_response_code(400);
            }
    }
}