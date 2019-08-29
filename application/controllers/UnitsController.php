<?php

class UnitsController {
    public static function index () {
        Router::redirect('/units/' . slug(UnitGroups::select()->fetch()->name));
    }

    public static function show ($unit_group_slug) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['id'];
            $amount = $_POST['amount'];
            $player = Auth::player();
            $unit_query = Units::select($unit_id);

            if ($unit_query->rowCount() == 1) {
                $unit = $unit_query->fetch();

                $player_units_query = PlayerUnits::select([
                    'player_id' => $player->id,
                    'unit_id' => $unit_id
                ]);

                if ($_POST['action'] == 'buy') {
                    if ($player->money < $unit->price * $amount) {
                        $amount = floor($player->money / $unit->price);
                    }
                    if ($amount != 0) {
                        if ($player_units_query->rowCount() == 1) {
                            PlayerUnits::update([
                                'player_id' => $player->id,
                                'unit_id' => $unit_id
                            ], [
                                'amount' => $player_units_query->fetch()->amount + $amount
                            ]);
                        } else {
                            PlayerUnits::insert([
                                'player_id' => $player->id,
                                'unit_id' => $unit_id,
                                'amount' => $amount
                            ]);
                        }
                        Players::update($player->id, [
                            'money' => $player->money - $unit->price * $amount,
                            'attack' => $player->attack + $unit->attack * $amount,
                            'defence'=> $player->defence + $unit->defence * $amount
                        ]);
                    }
                }

                if ($_POST['action'] == 'sell') {
                    if ($player_units_query->rowCount() == 1) {
                        $player_units = $player_units_query->fetch();
                        if ($amount > $player_units->amount) {
                            $amount = $player_units->amount;
                        }
                        if ($amount != 0) {
                            PlayerUnits::update([
                                'player_id' => $player->id,
                                'unit_id' => $unit_id
                            ], [
                                'amount' => $player_units->amount - $amount
                            ]);
                            Players::update($player->id, [
                                'money' => $player->money + floor($unit->price / 2) * $amount,
                                'attack' => $player->attack - $unit->attack * $amount,
                                'defence'=> $player->defence - $unit->defence * $amount
                            ]);
                        }
                    }
                }
            }

            Router::back();
        } else {
            $unit_group_id = 1;
            $unit_groups = UnitGroups::select()->fetchAll();
            foreach ($unit_groups as $unit_group) {
                if (slug($unit_group->name) == $unit_group_slug) {
                    $unit_group_id = $unit_group->id;
                    break;
                }
            }

            $units = Units::select([ 'unit_group_id' => $unit_group_id ])->fetchAll();
            foreach ($units as $unit) {
                $player_units_query = PlayerUnits::select([ 'player_id' => Auth::player()->id, 'unit_id' => $unit->id ]);
                if ($player_units_query->rowCount() == 1) {
                    $unit->amount = $player_units_query->fetch()->amount;
                } else {
                    $unit->amount = 0;
                }
            }
            view('player/units', [ 'unit_group_id' => $unit_group_id, 'unit_groups' => $unit_groups, 'units' => $units ]);
        }
    }
}
