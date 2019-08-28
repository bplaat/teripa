<?php

class AdminBuildingGroupsController {
    public static function index () {
        $building_groups = BuildingGroups::select()->fetchAll();
        view('admin/building_groups/index', [ 'building_groups' => $building_groups ]);
    }

    public static function create () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            BuildingGroups::insert([
                'name' => $_POST['name']
            ]);
            Router::redirect('/admin/building_groups');
        } else {
            view('admin/building_groups/create');
        }
    }

    public static function edit ($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            BuildingGroups::update($id, [
                'name' => $_POST['name']
            ]);
            Router::redirect('/admin/building_groups');
        } else {
            $building_group = BuildingGroups::select($id)->fetch();
            view('admin/building_groups/edit', [ 'building_group' => $building_group ]);
        }
    }

    public static function delete ($id) {
        BuildingGroups::delete($id);
        Router::back();
    }
}