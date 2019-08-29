<?php

class AdminPlayersController {
    public static function index () {
        $players = Players::select()->fetchAll();
        view('admin/players/index', [ 'players' => $players ]);
    }

    public static function edit ($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Players::update($id, [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
                'background' => $_POST['background'],
                'money' => $_POST['money']
            ]);
            Router::redirect('/admin/players');
        } else {
            $player = Players::select($id)->fetch();
            view('admin/players/edit', [ 'player' => $player ]);
        }
    }

    public static function delete ($id) {
        Players::delete($id);
        Sessions::delete([ 'player_id' => $id]);
        PlayerBuildings::delete([ 'player_id' => $id]);
        PlayerUnits::delete([ 'player_id' => $id]);
        Router::back();
    }
}
