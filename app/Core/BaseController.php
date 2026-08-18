<?php

namespace App\Core;

class BaseController extends Controller
{
    protected function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            redirect('login');
        }
    }

    protected function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }
}