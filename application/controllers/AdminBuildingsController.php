<?php

class AdminBuildingsController {
    public static function index () {
        $building_groups = BuildingGroups::select()->fetchAll();
        $buildings = Buildings::select()->fetchAll();
        view('admin/buildings/index', [ 'building_groups' => $building_groups, 'buildings' => $buildings ]);
    }

    public static function create () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Buildings::insert([
                'building_group_id' => $_POST['building_group_id'],
                'position' => $_POST['position'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'income' => $_POST['income'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/images/buildings/' . $_POST['position'] . '.jpg');
            }
            Router::redirect('/admin/buildings');
        } else {
            $building_groups = BuildingGroups::select()->fetchAll();
            view('admin/buildings/create', [ 'building_groups' => $building_groups ]);
        }
    }

    public static function edit ($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $building = Buildings::select($id)->fetch();
            Buildings::update($id, [
                'building_group_id' => $_POST['building_group_id'],
                'position' => $_POST['position'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'income' => $_POST['income'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/images/buildings/' . slug(findById($building_groups, $building_group_id)->name) . '/' . $building->position . '.jpg');
            }

            $players = Players::select()->fetchAll();
            foreach ($players as $player) {
                $income = 0;
                $defence = 0;
                $player_buildings = PlayerBuildings::select([ 'player_id' => $player->id ])->fetchAll();
                foreach ($player_buildings as $player_building) {
                    $building = Buildings::select($player_building->building_id)->fetch();
                    $income += $building->income * $player_building->amount;
                    $defence += $building->defence * $player_building->amount;
                }
                Players::update($player->id, [ 'income' => $income, 'defence' => $defence ]);
            }

            Router::redirect('/admin/buildings');
        } else {
            $building_groups = BuildingGroups::select()->fetchAll();
            $building = Buildings::select($id)->fetch();
            view('admin/buildings/edit', [ 'building_groups' => $building_groups, 'building' => $building ]);
        }
    }

    public static function delete ($id) {
        Buildings::delete($id);
        Router::back();
    }
}
