<?php

class AdminUnitGroupsController {
    public static function index () {
        $unit_groups = UnitGroups::select()->fetchAll();
        view('admin/unit_groups/index', [ 'unit_groups' => $unit_groups ]);
    }

    public static function create () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            UnitGroups::insert([
                'name' => $_POST['name']
            ]);
            Router::redirect('/admin/unit_groups');
        } else {
            view('admin/unit_groups/create');
        }
    }

    public static function edit ($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $old_name = UnitGroups::select($id)->fetch()->name;
            UnitGroups::update($id, [
                'name' => $_POST['name']
            ]);
            if (is_dir(ROOT . '/public/images/units/' . slug($old_name)) && $old_name != $_POST['name']) {
                rename(
                    ROOT . '/public/images/units/' . slug($old_name),
                    ROOT . '/public/images/units/' . slug($_POST['name'])
                );
            }
            Router::redirect('/admin/unit_groups');
        } else {
            $unit_group = UnitGroups::select($id)->fetch();
            view('admin/unit_groups/edit', [ 'unit_group' => $unit_group ]);
        }
    }

    public static function delete ($id) {
        UnitGroups::delete($id);
        Router::back();
    }
}
