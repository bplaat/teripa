<?php

class BattleController {
    public static function list () {
        $players = Players::select()->fetchAll();
        view('player/battle', [ 'players' => $players ]);
    }

    public static function attack () {
        $other_player_id = $_GET['id'];
        $player = Auth::player();

        $other_player_query = Players::select($other_player_id);
        if ($other_player_id != $player->id && $other_player_query->rowCount() == 1) {
            $other_player = $other_player_query->fetch();

            if ($player->attack > $other_player->defence) {
                $stolen_money = rand(0, floor($other_player->money / BATTLE_BOUNTY_FACTOR));
                Players::update($player->id, [
                    'won' => $player->won + 1,
                    'money' => $player->money + $stolen_money
                ]);
                Players::update($other_player->id, [
                    'lost' => $other_player->lost + 1,
                    'money' => $other_player->money - $stolen_money
                ]);
            }

            else {
                $recovery_money = rand(0, floor($player->money / BATTLE_BOUNTY_FACTOR));
                Players::update($player->id, [
                    'lost' => $player->lost + 1,
                    'money' => $player->money - $recovery_money
                ]);
                Players::update($other_player->id, [
                    'won' => $other_player->won + 1,
                    'money' => $other_player->money + $recovery_money
                ]);
            }
        }

        Router::back();
    }
}
