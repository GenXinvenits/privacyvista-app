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
        $uri = (string)($_GET['route'] ?? '/');

        // Be defensive if route and query parameters arrive together in the
        // route value (for example, route=processing-activities?client_id=3).
        // The query string belongs to $_GET, not to the route name.
        if (str_contains($uri, '?')) {
            [$uri] = explode('?', $uri, 2);
        }

        $uri = trim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "<h2>404 - Route Not Found</h2>";
            echo "<p>Requested Route: <strong>" . htmlspecialchars($uri, ENT_QUOTES, 'UTF-8') . "</strong></p>";
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
