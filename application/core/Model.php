<?php

abstract class Model {
    protected static $primaryKey = 'id';

    abstract public static function create ();

    public static function drop () {
        return Database::query('DROP TABLE IF EXISTS `' . (isset(static::$table) ? static::$table : strtolower(static::class)) . '`');
    }

    public static function select ($where = null) {
        if (is_null($where)) {
            return Database::query('SELECT * FROM `' . (isset(static::$table) ? static::$table : strtolower(static::class)) . '`');
        } else {
            if (!is_array($where)) $where = [ static::$primaryKey => $where ];
            foreach ($where as $key => $value) $wheres[] = '`' . $key . '` = ?';
            return Database::query('SELECT * FROM `' . (isset(static::$table) ? static::$table : strtolower(static::class)) . '` WHERE ' . implode($wheres, ' AND '), array_values($where));
        }
    }

    public static function insert ($values) {
        foreach ($values as $key => $value) $keys[] = '`' . $key . '`';
        return Database::query('INSERT INTO `' . (isset(static::$table) ? static::$table : strtolower(static::class)). '` (' . implode($keys, ', ') . ') ' .
            'VALUES (' . implode(array_fill(0, count($values), '?'), ', ') . ')', array_values($values));
    }

    public static function update ($where, $values) {
        if (!is_array($where)) $where = [ static::$primaryKey => $where ];
        foreach ($values as $key => $value) $sets[] = '`' . $key . '` = ?';
        foreach ($where as $key => $value) $wheres[] = '`' . $key . '` = ?';
        return Database::query('UPDATE `' . (isset(static::$table) ? static::$table : strtolower(static::class)) . '` SET ' . implode($sets, ', ') . ' ' .
            'WHERE ' . implode($wheres, ' AND '), array_merge(array_values($values), array_values($where)));
    }

    public static function delete ($where) {
        if (!is_array($where)) $where = [ static::$primaryKey => $where ];
        foreach ($where as $key => $value) $wheres[] = '`' . $key . '` = ?';
        return Database::query('DELETE FROM `' . (isset(static::$table) ? static::$table : strtolower(static::class)) . '` WHERE ' . implode($wheres, ' AND '), array_values($where));
    }
}
