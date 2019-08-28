<?php

class BuildingsController {
    public static function index () {
        Router::redirect('/buildings/' . slug(BuildingGroups::select()->fetch()->name));
    }

    public static function list ($building_group_slug) {
        $building_group_id = 1;
        $building_groups = BuildingGroups::select()->fetchAll();
        foreach ($building_groups as $building_group) {
            if (slug($building_group->name) == $building_group_slug) {
                $building_group_id = $building_group->id;
                break;
            }
        }

        $buildings = Buildings::select([ 'building_group_id' => $building_group_id ])->fetchAll();
        foreach ($buildings as $building) {
            $player_buildings_query = PlayerBuildings::select([ 'player_id' => Auth::player()->id, 'building_id' => $building->id ]);
            if ($player_buildings_query->rowCount() == 1) {
                $building->amount = $player_buildings_query->fetch()->amount;
            } else {
                $building->amount = 0;
            }
        }
        view('player/buildings', [ 'building_group_id' => $building_group_id, 'building_groups' => $building_groups, 'buildings' => $buildings ]);
    }

    public static function buy () {
        $building_id = $_GET['id'];
        $amount = $_GET['amount'];
        $player = Auth::player();
        $building_query = Buildings::select($building_id);
        if ($building_query->rowCount() == 1) {
            $building = $building_query->fetch();
            if ($player->money >= $building->price * $amount) {

                $player_buildings_query = PlayerBuildings::select([
                    'player_id' => $player->id,
                    'building_id' => $building_id
                ]);
                if ($player_buildings_query->rowCount() == 1) {
                    PlayerBuildings::update([
                        'player_id' => $player->id,
                        'building_id' => $building_id
                    ], [
                        'amount' => $player_buildings_query->fetch()->amount + $amount
                    ]);
                } else {
                    PlayerBuildings::insert([
                        'player_id' => $player->id,
                        'building_id' => $building_id,
                        'amount' => $amount
                    ]);
                }

                Players::update($player->id, [
                    'money' => $player->money - $building->price * $amount,
                    'income' => $player->income + $building->income * $amount,
                    'defence'=> $player->defence + $building->defence * $amount
                ]);
            }
        }
        Router::back();
    }

    public static function sell () {
        $building_id = $_GET['id'];
        $amount = $_GET['amount'];
        $player = Auth::player();
        $building_query = Buildings::select($building_id);
        if ($building_query->rowCount() == 1) {
            $building = $building_query->fetch();

            $player_buildings_query = PlayerBuildings::select([
                'player_id' => $player->id,
                'building_id' => $building_id
            ]);
            if ($player_buildings_query->rowCount() == 1) {
                $player_buildings = $player_buildings_query->fetch();
                if ($player_buildings->amount - $amount >= 0) {

                    PlayerBuildings::update([
                        'player_id' => $player->id,
                        'building_id' => $building_id
                    ], [
                        'amount' => $player_buildings->amount - $amount
                    ]);

                    Players::update($player->id, [
                        'money' => $player->money + floor($building->price / 2) * $amount,
                        'income' => $player->income - $building->income * $amount,
                        'defence'=> $player->defence - $building->defence * $amount
                    ]);
                }
            }
        }
        Router::back();
    }
}
