<?php

class Router {
    public static function get(string $route, callable $callback): void {
        static::match([ 'get' ], $route, $callback);
    }

    public static function post(string $route, callable $callback): void {
        static::match([ 'post' ], $route, $callback);
    }

    public static function any(string $route, callable $callback): void {
        static::match([ 'get', 'post' ], $route, $callback);
    }

    public static function handleResponse($response): void {
        if (is_string($response)) {
            echo $response;
            exit;
        }

        if (is_array($response) || is_object($response)) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    public static function match(array $methods, string $route, callable $callback): void {
        $path = rtrim(preg_replace('#/+#', '/', strtok($_SERVER['REQUEST_URI'], '?')), '/');
        if ($path == '') $path = '/';

        if (
            in_array(strtolower($_SERVER['REQUEST_METHOD']), $methods) &&
            preg_match('#^' . preg_replace('/{.*}/U', '([^/]*)', $route) . '$#', $path, $values)
        ) {
            array_shift($values);

            preg_match('/{(.*)}/U', $route, $names);
            array_shift($names);
            foreach ($names as $index => $name) {
                if (class_exists($name)) {
                    $query = ($name . '::select')($values[$index]);
                    if ($query->rowCount() == 1) {
                        $values[$index] = $query->fetch();
                    } else {
                        return;
                    }
                }
            }

            static::handleResponse($callback(...$values));
        }
    }

    public static function fallback(callable $callback): void {
        static::handleResponse($callback());
    }

    public static function redirect(string $route): void {
        header('Location: ' . $route);
        exit;
    }

    public static function back(): void {
        static::redirect($_SERVER['HTTP_REFERER']);
    }
}
