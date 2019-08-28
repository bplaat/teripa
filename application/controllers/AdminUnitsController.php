<?php

class AdminUnitsController {
    public static function index () {
        $unit_groups = UnitGroups::select()->fetchAll();
        $units = Units::select()->fetchAll();
        view('admin/units/index', [ 'unit_groups' => $unit_groups, 'units' => $units ]);
    }

    public static function create () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Units::insert([
                'unit_group_id' => $_POST['unit_group_id'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'attack' => $_POST['attack'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                move_uploaded_file($_FILES["image"]["tmp_name"], ROOT . '/public/images/units/' . Database::lastInsertId() . '.jpg');
            }
            Router::redirect('/admin/units');
        } else {
            $unit_groups = UnitGroups::select()->fetchAll();
            view('admin/units/create', [ 'unit_groups' => $unit_groups ]);
        }
    }

    public static function edit ($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Units::update($id, [
                'unit_group_id' => $_POST['unit_group_id'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'attack' => $_POST['attack'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                move_uploaded_file($_FILES["image"]["tmp_name"], ROOT . '/public/images/units/' . $id . '.jpg');
            }
            Router::redirect('/admin/units');
        } else {
            $unit_groups = UnitGroups::select()->fetchAll();
            $unit = Units::select($id)->fetch();
            view('admin/units/edit', [ 'unit_groups' => $unit_groups, 'unit' => $unit ]);
        }
    }

    public static function delete ($id) {
        Units::delete($id);
        Router::back();
    }
}