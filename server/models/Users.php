<?php

class Users extends Model {
    const USERNAME_VALIDATION = 'required|min:3|max:32|unique:Users';
    const USERNAME_EDIT_VALIDATION = 'required|min:3|max:32';
    const EMAIL_VALIDATION = 'required|email|max:191|unique:Users';
    const EMAIL_EDIT_VALIDATION = 'required|email|max:191';
    const OLD_PASSWORD_VALIDATION = '@Users::VERIFY_PASSWORD_VALIDATION';
    const PASSWORD_VALIDATION = 'required|min:6|confirmed';

    public static function create(array $user): int {
        static::insert($user);

        $userId = Database::lastInsertId();

        LegacyPlayers::insert([
            'user_id' => $userId,
            'food' => LEGACY_FOOD_DEFAULT,
            'wood' => LEGACY_WOOD_DEFAULT,
            'gold' => LEGACY_GOLD_DEFAULT,
            'map' => LEGACY_MAP_DEFAULT,
            'units' => LEGACY_UNITS_DEFAULT
        ]);

        return $userId;
    }

    public static function VERIFY_PASSWORD_VALIDATION(string $key, string $value): ?string {
        if (!password_verify($value, Auth::user()->password)) {
            return 'The field ' . $key . ' must contain your current password';
        }

        return null;
    }

    public static function selectByLogin(string $username, string $email): PDOStatement {
        return Database::query('SELECT * FROM `users` WHERE `username` = ? OR `email` = ?', $username, $email);
    }
}
