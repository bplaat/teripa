<?php

abstract class Model {
    protected static string $table = '';

    protected static string $primaryKey = 'id';

    public static function table(): string {
        return static::$table == '' ? strtolower(static::class) : static::$table;
    }

    public static function primaryKey(): string {
        return static::$primaryKey;
    }

    public static function select($where = null): PDOStatement {
        if (is_null($where)) {
            return Database::query('SELECT * FROM `' . static::table() . '`');
        } else {
            if (!is_array($where)) $where = [ static::primaryKey() => $where ];
            foreach ($where as $key => $value) $wheres[] = '`' . $key . '` = ?';
            return Database::query('SELECT * FROM `' . static::table() . '` WHERE ' . implode(' AND ', $wheres), ...array_values($where));
        }
    }

    public static function insert(array $values): PDOStatement {
        foreach ($values as $key => $value) $keys[] = '`' . $key . '`';
        return Database::query('INSERT INTO `' . static::table() . '` (' . implode(', ', $keys) . ') ' .
            'VALUES (' . implode(', ', array_fill(0, count($values), '?')) . ')', ...array_values($values));
    }

    public static function update($where, array $values): PDOStatement {
        if (!is_array($where)) $where = [ static::primaryKey() => $where ];
        foreach ($values as $key => $value) $sets[] = '`' . $key . '` = ?';
        foreach ($where as $key => $value) $wheres[] = '`' . $key . '` = ?';
        return Database::query('UPDATE `' . static::table() . '` SET ' . implode(', ', $sets) . ' ' .
            'WHERE ' . implode(' AND ', $wheres), ...array_values($values), ...array_values($where));
    }

    public static function delete($where): PDOStatement {
        if (!is_array($where)) $where = [ static::primaryKey() => $where ];
        foreach ($where as $key => $value) $wheres[] = '`' . $key . '` = ?';
        return Database::query('DELETE FROM `' . static::table() . '` WHERE ' . implode(' AND ', $wheres), ...array_values($where));
    }
}
