<?php

class Database {
    protected static $pdo, $queryCount = 0;
    public static function connect ($host, $user, $password, $dbname) {
        static::$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }
    public static function queryCount () {
        return static::$queryCount;
    }
    public static function lastInsertId () {
        return static::$pdo->lastInsertId();
    }
    public static function query ($query, $parameters = []) {
        static::$queryCount++;
        $statement = static::$pdo->prepare($query);
        $statement->execute($parameters);
        return $statement;
    }
}