<?php

class Router {
    public static function route ($route, $callback) {
        if (preg_match('#^' . $route . '/*$#', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $matches)) {
            call_user_func_array($callback, array_slice($matches, 1));
            exit;
        }
    }

    public static function path () {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function redirect ($route) {
        header('Location: ' . $route);
        exit;
    }

    public static function back () {
        Router::redirect($_SERVER['HTTP_REFERER']);
    }
}
