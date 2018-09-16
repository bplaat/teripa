<?php

class UnitsController {
    public static function list () {
        $units = Units::select()->fetchAll();
        $player_units = PlayerUnit::select([ 'player_id' => Auth::player()->player_id ])->fetchAll();
        foreach ($player_units as $unit) $units[array_search($unit->unit_id, array_column($units, 'unit_id'))]->amount = $unit->amount;
        return view('player.units', [ 'units' => $units ]);
    }


    public static function buy () {
        $player = Auth::player();
        $unit_query = Units::select($_GET['unit_id']);
            $amount = isset($_GET['amount']) ? $_GET['amount'] : 1;
            if ($unit_query->rowCount() == 1 && (string)(int)$amount == $amount) {
                $unit = $unit_query->fetch();
                if ($player->money >= $unit->price * $amount) {
                    Database::query('UPDATE player_unit SET amount = amount + ? WHERE player_id = ? AND unit_id = ?', [ $amount, $player->player_id, $_GET['unit_id'] ]);
                    Database::query('UPDATE players SET money = ?, attack = ?, defence = ? WHERE player_id = ?', [ $player->money -= $unit->price * $amount, $player->attack += $unit->attack * $amount, $player->defence += $unit->defence * $amount, $player->player_id ]);
                    Router::redirect('/units');
                } else {
                    echo json_encode([ 'no_money' => false ]);
                }
            } else {
                http_response_code(400);
            }
    }
}