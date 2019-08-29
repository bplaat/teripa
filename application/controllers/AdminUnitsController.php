<?php

class AdminUnitsController {
    public static function index () {
        $unit_groups = UnitGroups::select()->fetchAll();
        $units = Units::select()->fetchAll();
        view('admin/units/index', [ 'unit_groups' => $unit_groups, 'units' => $units ]);
    }

    public static function create () {
        $unit_groups = UnitGroups::select()->fetchAll();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Units::insert([
                'unit_group_id' => $_POST['unit_group_id'],
                'position' => $_POST['position'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'attack' => $_POST['attack'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                $folder = ROOT . '/public/images/units/' . slug(findById($unit_groups, $_POST['unit_group_id'])->name);
                if (!is_dir($folder)) mkdir($folder);
                move_uploaded_file($_FILES['image']['tmp_name'],  $folder . '/' . $_POST['position'] . '.jpg');
            }
            Router::redirect('/admin/units');
        } else {
            view('admin/units/create', [ 'unit_groups' => $unit_groups ]);
        }
    }

    public static function edit ($id) {
        $unit_groups = UnitGroups::select()->fetchAll();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Units::update($id, [
                'unit_group_id' => $_POST['unit_group_id'],
                'position' => $_POST['position'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'attack' => $_POST['attack'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                $folder = ROOT . '/public/images/units/' . slug(findById($unit_groups, $_POST['unit_group_id'])->name);
                if (!is_dir($folder)) mkdir($folder);
                move_uploaded_file($_FILES['image']['tmp_name'],  $folder . '/' . $_POST['position'] . '.jpg');
            }

            $players = Players::select()->fetchAll();
            foreach ($players as $player) {
                $attack = 0;
                $defence = 0;
                $player_units = PlayerUnits::select([ 'player_id' => $player->id ])->fetchAll();
                foreach ($player_units as $player_unit) {
                    $unit = Units::select($player_unit->unit_id)->fetch();
                    $attack += $unit->attack * $player_unit->amount;
                    $defence += $unit->defence * $player_unit->amount;
                }
                Players::update($player->id, [ 'attack' => $attack, 'defence' => $defence ]);
            }

            Router::redirect('/admin/units');
        } else {
            $unit = Units::select($id)->fetch();
            view('admin/units/edit', [ 'unit_groups' => $unit_groups, 'unit' => $unit ]);
        }
    }

    public static function delete ($id) {
        Units::delete($id);
        Router::back();
    }
}
