<?php

class Auth {
    protected static function generateSession () {
        $session = md5(microtime() . $_SERVER['REMOTE_ADDR']);
        if (Sessions::select($session)->rowCount() == 1) {
            return static::generateSession();
        }
        return $session;
    }

    public static function createSession ($player_id) {
        $session = static::generateSession();
        setcookie(SESSION_COOKIE_NAME, $session, time() + SESSION_DURATION, '/');
        $_COOKIE[SESSION_COOKIE_NAME] = $session;
        Sessions::insert([
            'session' => $session,
            'player_id' => $player_id,
            'expires_at' => date('Y-m-d H:i:s', time() + SESSION_DURATION)
        ]);
    }

    public static function revokeSession ($session) {
        Sessions::update($session, [ 'expires_at' => date('Y-m-d H:i:s') ]);
        if ($_COOKIE[SESSION_COOKIE_NAME] == $session) {
            setcookie(SESSION_COOKIE_NAME, '', time() - 3600, '/');
            unset($_COOKIE[SESSION_COOKIE_NAME]);
        }
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

    protected static $player;

    public static function check () {
        if (isset($_COOKIE[SESSION_COOKIE_NAME])) {
            $session_query = Sessions::select($_COOKIE[SESSION_COOKIE_NAME]);
            if ($session_query->rowCount() == 1) {
                $session = $session_query->fetch();
                if (strtotime($session->expires_at) > time()) {
                    $player = Players::select($session->player_id)->fetch();
                    $player->money += $player->income * (time() - strtotime($player->paid_at));
                    $player->paid_at = date('Y-m-d H:i:s');
                    Players::update($player->id, [
                        'money' => $player->money,
                        'paid_at' => $player->paid_at
                    ]);
                    static::$player = $player;
                } else {
                    setcookie(SESSION_COOKIE_NAME, '', time() - 3600, '/');
                    unset($_COOKIE[SESSION_COOKIE_NAME]);
                }
            }
        }
    }

    public static function player () {
        return static::$player;
    }
}
