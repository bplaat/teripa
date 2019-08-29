<?php

class SettingsController {
    public static function settings () {
        $sessions = Sessions::select([ 'player_id' => Auth::player()->id ])->fetchAll();
        $active_sessions = [];
        foreach ($sessions as $session) {
            if (strtotime($session->expires_at) > time()) {
                $active_sessions[] = $session;
            }
        }
        view('player/settings', [ 'sessions' => $active_sessions ]);
    }

    public static function change_details () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (
                filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
            ) {
                Players::update(Auth::player()->id, [
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'background' => $_POST['background']
                ]);
                Router::redirect('/settings');
            }
        }
        Router::back();
    }

    public static function change_password () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (
                $_POST['new_password'] == $_POST['confirm_new_password']
            ) {
                if (password_verify($_POST['old_password'], Auth::player()->password)) {
                    Players::update(Auth::player()->id, [
                        'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
                    ]);
                    Router::redirect('/settings');
                }
            }
        }
        Router::back();
    }

    public static function revoke_session () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (password_verify($_POST['password'], Auth::player()->password)) {
                if (Sessions::select($_GET['session'])->fetch()->player_id == Auth::player()->id) {
                    Auth::revokeSession($_GET['session']);
                    Router::redirect('/settings');
                }
            }
            Router::back();
        } else {
            view('auth/confirm_password');
        }
    }
}
