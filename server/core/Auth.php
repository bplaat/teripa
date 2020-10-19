<?php

use Jenssegers\Agent\Agent;

class Auth {
    protected static ?object $user = null;

    public static function createSession ($userId): string {
        static::$user = null;

        $session = Sessions::generateSession();

        $ip = getIP();
        $ipInfo = json_decode(file_get_contents('https://ipinfo.io/' . $ip . '/json'));
        $ipLocation = explode(',', $ipInfo->loc ?? '');

        $agent = new Agent();

        $browser = $agent->browser();
        if ($browser == false) $browser = '?';

        $browser_version = $agent->version($browser);
        if ($browser_version == false) $browser_version = '?';

        $platform = $agent->platform();
        if ($platform == false) $platform = '?';

        $platform_version = $agent->version($platform);
        if ($platform_version == false) $platform_version = '?';

        Sessions::insert([
            'session' => $session,
            'user_id' => $userId,
            'ip' => $ip,
            'ip_country' => $ipInfo->country ?? '??',
            'ip_city' => $ipInfo->city ?? '?',
            'ip_lat' => $ipLocation[0] ?? 0,
            'ip_lng' => $ipLocation[1] ?? 0,
            'browser' => $browser,
            'browser_version' => $browser_version,
            'platform' => $platform,
            'platform_version' =>  $platform_version,
            'expires_at' => date('Y-m-d H:i:s', time() + SESSION_DURATION)
        ]);

        $_COOKIE[SESSION_COOKIE_NAME] = $session;
        setcookie(SESSION_COOKIE_NAME, $session, time() + SESSION_DURATION, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), true);

        return $session;
    }

    public static function updateSession(string $session): void {
        $ip = getIP();
        $ipInfo = json_decode(file_get_contents('https://ipinfo.io/' . $ip . '/json'));
        $ipLocation = explode(',', $ipInfo->loc ?? '');

        $agent = new Agent();

        $browser = $agent->browser();
        if ($browser == false) $browser = '?';

        $browser_version = $agent->version($browser);
        if ($browser_version == false) $browser_version = '?';

        $platform = $agent->platform();
        if ($platform == false) $platform = '?';

        $platform_version = $agent->version($platform);
        if ($platform_version == false) $platform_version = '?';

        Sessions::update([
            'session' => $session
        ], [
            'ip' => $ip,
            'ip_country' => $ipInfo->country ?? '??',
            'ip_city' => $ipInfo->city ?? '?',
            'ip_lat' => $ipLocation[0] ?? 0,
            'ip_lng' => $ipLocation[1] ?? 0,
            'browser' => $browser,
            'browser_version' => $browser_version,
            'platform' => $platform,
            'platform_version' =>  $platform_version,

            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function revokeSession(string $session): void {
        static::$user = null;

        Sessions::update([
            'session' => $session
        ], [
            'expires_at' => date('Y-m-d H:i:s')
        ]);

        if ($_COOKIE[SESSION_COOKIE_NAME] == $session) {
            unset($_COOKIE[SESSION_COOKIE_NAME]);
            setcookie(SESSION_COOKIE_NAME, '', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), true);
        }
    }

    public static function login(string $login, string $password): bool {
        $userQuery = Users::selectByLogin($login, $login);
        if ($userQuery->rowCount() == 1) {
            $user = $userQuery->fetch();

            if ($user->old_password != null && hash_equals(md5($password), $user->old_password)) {
                Users::update($user->id, [
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'old_password' => null
                ]);
                static::createSession($user->id);
                return true;
            }

            if ($user->password != null && password_verify($password, $user->password)) {
                static::createSession($user->id);
                return true;
            }
        }

        return false;
    }

    public static function user(): ?object {
        if (static::$user == null && isset($_COOKIE[SESSION_COOKIE_NAME])) {
            $sessionQuery = Sessions::select([ 'session' => $_COOKIE[SESSION_COOKIE_NAME] ]);
            if ($sessionQuery->rowCount() == 1) {
                $session = $sessionQuery->fetch();
                if (strtotime($session->expires_at) >= time()) {
                    static::$user = Users::select($session->user_id)->fetch();

                    if (strtotime($session->updated_at) + SESSION_UPDATE_DURATION <= time()) {
                        Auth::updateSession($session->session);
                    }
                } else {
                    static::revokeSession($session->session);
                }
            }
        }
        return static::$user;
    }

    public static function check(): bool {
        return static::user() != null;
    }

    public static function id(): int {
        return static::user()->id;
    }
}
