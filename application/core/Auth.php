<?php

class Auth {
    protected static $session, $player;

    private static function createSession ($player_id) {
        session_regenerate_id();
        Sessions::insert([
            'session' => session_id(),
            'player_id' => $player_id,
            'expires_at' => date('Y-m-d H:i:s', time() + SESSION_DURATION)
        ]);
    }

    public static function revokeSession ($session) {
        Sessions::update($session, [ 'expires_at' => date('Y-m-d H:i:s') ]);
    }

    public static function signin ($login, $password) {
        $player_query = Database::query('SELECT * FROM players WHERE `username` = ? OR `email` = ?', [ $login, $login ]);
        if ($player_query->rowCount() == 1) {
            $player = $player_query->fetch();
            if (password_verify($password, $player->password)) {
                static::createSession($player->id);
                return true;
            }
        }
        return false;
    }

    public static function signup ($first_name, $last_name, $username, $email, $password, $role = 0) {
        $player_query = Database::query('SELECT * FROM players WHERE `username` = ? OR `email` = ?', [ $username, $email ]);
        if ($player_query->rowCount() == 0) {
            Players::insert([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
                'money' => PLAYER_START_MONEY,
                'income' => 0,
                'attack' => 0,
                'defence' => 0,
                'paid_at' => date('Y-m-d H:i:s'),
                'won' => 0,
                'lost' => 0
            ]);
            $player_id = Database::lastInsertId();
            static::createSession($player_id);
            return true;
        }
        return false;
    }

    public static function check () {
        if (is_null(static::$session)) {
            $session_query = Sessions::select(session_id());
            if ($session_query->rowCount() == 1) {
                $session = $session_query->fetch();
                if (strtotime($session->expires_at) > time()) {
                    static::$session = $session;
                    static::$player = Players::select($session->player_id)->fetch();
                    static::$player->money += static::$player->income * (time() - strtotime(static::$player->paid_at));
                    static::$player->paid_at = date('Y-m-d H:i:s');
                    Players::update(static::$player->id, [
                        'money' => static::$player->money,
                        'paid_at' => static::$player->paid_at
                    ]);
                    return true;
                }
            }
            return false;
        } else {
            return true;
        }
    }

    public static function player () {
        return static::$player;
    }
}
