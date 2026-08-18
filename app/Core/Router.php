<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->routes['GET'][$this->normalize($uri)] = $action;
    }

    public function post(string $uri, callable|array $action): void
    {
        $this->routes['POST'][$this->normalize($uri)] = $action;
    }

    public function dispatch(): void
{
    $method = $_SERVER['REQUEST_METHOD'];

    $uri = $_GET['route'] ?? '/';

    $uri = trim($uri, '/');

    if ($uri === '') {
        $uri = '/';
    }

    if (!isset($this->routes[$method][$uri])) {
        http_response_code(404);

        echo "<h2>404 - Route Not Found</h2>";
        echo "<p>Requested Route: <strong>{$uri}</strong></p>";

        echo "<pre>";
        print_r(array_keys($this->routes[$method] ?? []));
        echo "</pre>";

        exit;
    }

    $action = $this->routes[$method][$uri];

    [$controller, $function] = $action;

    $controller = "App\\Controllers\\{$controller}";

    $instance = new $controller();

    $instance->$function();
}

    private function normalize(string $uri): string
    {
        $uri = trim($uri, '/');

        return $uri ?: '/';
    }
}
