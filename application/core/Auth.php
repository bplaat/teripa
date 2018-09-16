<?php

class Auth {
    protected static $session, $player;
    public static function signin ($username, $password) {
        $player_query = Players::select([ 'username' => $username ]);
        if ($player_query->rowCount() == 1) {
            $player = $player_query->fetch();
            if (password_verify($password, $player->password)) {
                session_regenerate_id();
                Sessions::insert([
                    'session' => session_id(),
                    'player_id' => $player->player_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'expires_at' => date('Y-m-d H:i:s', time() + Application::config('session.duration'))
                ]);
                return true;
            }
        }
        return false;
    }
    public static function signup ($username, $email, $password) {
        $player_query = Database::query('SELECT 0 FROM players WHERE username = ? OR email = ?', [ $username, $email ]);
        if ($player_query->rowCount() == 0) {
            Players::insert([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'money' => Application::config('player.start_money'),
                'income' => 0,
                'attack' => 0,
                'defence' => 0,
                'paid_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $player_id = Database::lastInsertId();
            session_regenerate_id();
            Sessions::insert([
                'session' => session_id(),
                'player_id' => $player_id,
                'created_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', time() + Application::config('session.duration'))
            ]);
            $units = Units::select()->fetchAll();
            foreach ($units as $unit) PlayerUnit::insert([ 'player_id' => $player_id, 'unit_id' => $unit->unit_id, 'amount' => 0 ]);
            $buildings = Buildings::select()->fetchAll();
            foreach ($buildings as $building) PlayerBuilding::insert([ 'player_id' => $player_id, 'building_id' => $building->building_id, 'amount' => 0 ]);
            return true;
        }
        return false;
    }
    public static function signout () {
        Sessions::update([ 'session' => session_id() ], [ 'expires_at' => date('Y-m-d H:i:s') ]);
        session_regenerate_id();
        return true;
    }
    public static function check () {
        if (is_null(static::$session)) {
            $session_query = Sessions::select([ 'session' => session_id() ]);
            if ($session_query->rowCount() == 1) {
                $session = $session_query->fetch();
                if (strtotime($session->expires_at) > time()) {
                    static::$session = $session;
                    return true;
                }
            }
            return false;
        } else {
            return true;
        }
    }
    public static function player () {
        if (is_null(static::$player)) {
            static::$player = Players::select(static::$session->player_id)->fetch();
            Players::update(static::$player->player_id, [
                'money' => static::$player->money += static::$player->income * (time() - strtotime(static::$player->paid_at)),
                'paid_at' => static::$player->paid_at = date('Y-m-d H:i:s')
            ]);
        }
        return static::$player;
    }
}