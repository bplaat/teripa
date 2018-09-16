<?php

abstract class Table {
    public static function select ($where = null) {
        $table = isset(static::$table) ? static::$table : strtolower(static::class);
        $primary_key = substr($table, 0, -1) . '_id';
        if (!is_null($where)) {
            if (!is_array($where)) $where = [ $primary_key => $where ];
            foreach ($where as $column => $value) $wheres[] = $column . ' = ?';
            return Database::query('SELECT * FROM ' . $table . ' WHERE ' . implode($wheres, ' AND '), array_values($where));
        } else {
            return Database::query('SELECT * FROM ' . $table);
        }
    }
    public static function insert ($fields) {
        $table = isset(static::$table) ? static::$table : strtolower(static::class);
        return Database::query('INSERT INTO ' . $table . ' (' . implode(array_keys($fields), ', ') . ') ' .
            'VALUES (' . implode(array_fill(0, count($fields), '?'), ', ') . ')', array_values($fields));
    }
    public static function update ($where, $fields) {
        $table = isset(static::$table) ? static::$table : strtolower(static::class);
        $primary_key = substr($table, 0, -1) . '_id';
        if (!is_array($where)) $where = [ $primary_key => $where ];
        foreach ($where as $column => $value) $wheres[] = $column . ' = ?';
        foreach ($fields as $column => $value) $columns[] = $column . ' = ?';
        return Database::query('UPDATE ' . $table . ' SET ' . implode($columns, ', ') . ' WHERE ' . implode($wheres, ' AND '),
            array_merge(array_values($fields), array_values($where)));
    }
    public static function delete ($where) {
        $table = isset(static::$table) ? static::$table : strtolower(static::class);
        $primary_key = substr($table, 0, -1) . '_id';
        if (!is_array($where)) $where = [ $primary_key => $where ];
        foreach ($where as $column => $value) $wheres[] = $column . ' = ?';
        return Database::query('DELETE FROM ' . $table . ' WHERE ' . implode($wheres, ' AND '), array_values($where));
    }
}