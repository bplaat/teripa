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
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'income' => $_POST['income'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                move_uploaded_file($_FILES["image"]["tmp_name"], ROOT . '/public/images/buildings/' . Database::lastInsertId() . '.jpg');
            }
            Router::redirect('/admin/buildings');
        } else {
            $building_groups = BuildingGroups::select()->fetchAll();
            view('admin/buildings/create', [ 'building_groups' => $building_groups ]);
        }
    }

    public static function edit ($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Buildings::update($id, [
                'building_group_id' => $_POST['building_group_id'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'income' => $_POST['income'],
                'defence' => $_POST['defence']
            ]);
            if (isset($_FILES['image'])) {
                move_uploaded_file($_FILES["image"]["tmp_name"], ROOT . '/public/images/buildings/' . $id . '.jpg');
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