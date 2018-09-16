<?php

class Application {
    protected static $config;
    public static function start () {
        static::$config = require APP . 'config.php';
        session_name(static::config('session.name'));
        session_start();
        Database::connect(
            static::config('database.host'),
            static::config('database.user'),
            static::config('database.password'),
            static::config('database.dbname')
        );
        require APP . 'routes.php';
        unset($_SESSION['errors']);
    }
    public static function config ($key) {
        $target = static::$config;
        foreach (explode('.', $key) as $part) $target = $target[$part];
        return $target;
    }
}

function view ($name, $data = null) {
    if (!is_null($data)) extract($data);
    ob_start();
    require APP . 'views/' . str_replace('.', '/', $name) . '.phtml';
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

require APP . 'utils.php';