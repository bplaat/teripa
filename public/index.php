<?php

define('ROOT', dirname(__FILE__) . '/../');
define('APP', ROOT . 'application/');

spl_autoload_register(function ($class_name) {
    foreach (['controllers/', 'core/', 'tables/'] as $path) {
        $file = APP . $path . $class_name . '.php';
        if (is_file($file)) {
            require_once $file;
            break;
        }
    }
});

Application::start();