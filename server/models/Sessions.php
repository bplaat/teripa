<?php

class Sessions extends Model {
    public static function generateSession(): string {
        $session = bin2hex(random_bytes(16));
        if (static::select($session)->rowCount() == 1) {
            return static::generateSession();
        }
        return $session;
    }

    public static function selectActiveByUser(int $userId): PDOStatement {
        return Database::query('SELECT * FROM `sessions` WHERE `user_id` = ? AND `expires_at` > NOW() ORDER BY `updated_at` DESC', $userId);
    }
}
