<?php

function config(string $key)
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../../config/app.php';
    }

    return $config[$key] ?? null;
}

function url(string $route = '', array $params = []): string
{
    $base = rtrim(config('url'), '/');

    $url = $base . '/index.php';

    if ($route !== '') {
        $params = array_merge(['route' => $route], $params);
    }

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    return $url;
}

function redirect(string $route): void
{
    header('Location: ' . url($route));
    exit;
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function auth()
{
    return $_SESSION['user'] ?? null;
}

function component(string $name, array $data = []): void
{
    extract($data);

    require __DIR__ . "/../Views/components/{$name}.php";
}

