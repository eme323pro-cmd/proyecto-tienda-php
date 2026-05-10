<?php

namespace Core;

class Router {
    private static $routes = [];

    public static function add($method, $action, $callback) {
        self::$routes[] = [
            'method' => $method,
            'action' => $action,
            'callback' => $callback
        ];
    }

    public static function dispatch() {
        // Esto limpia la URL (quita los parámetros ?id=1 etc para comparar)
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $uri = explode('/public', $uri);
        $uri = end($uri);
        if ($uri === '') $uri = '/';

        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && $route['action'] === $uri) {
                
                return call_user_func($route['callback']);
            }
        }

        echo "404 - Página no encontrada";
    }
}