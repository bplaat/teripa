<?php

class Router {
    protected static $otherRouteMatch = false;
    public static function get ($route, $callback) {
        static::match(['get'], $route, $callback);
    }
    public static function post ($route, $callback) {
        static::match(['post'], $route, $callback);
    }
    public static function any ($route, $callback) {
        static::match(['get', 'post'], $route, $callback);
    }
    public static function path () {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    public static function match ($methods, $route, $callback) {
        if (!static::$otherRouteMatch && in_array(strtolower($_SERVER['REQUEST_METHOD']), $methods) &&
          preg_match("#^" . $route . "$#", static::path(), $matches)) {
            static::$otherRouteMatch = true;
            $data = call_user_func_array($callback, array_slice($matches, 1));
            if (is_array($data)) {
                header('Content-Type: application/json');
                echo json_encode($data);
            }
            if (is_string($data)) {
                echo $data;
            }
        }
    }
    public static function redirect ($path) {
        header('Location: ' . $path);
        exit;
    }
    public static function back () {
        Router::redirect($_SERVER['HTTP_REFERER']);
    }
}