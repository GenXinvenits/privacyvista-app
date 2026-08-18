<?php

namespace App\Core;

class BaseController extends Controller
{
    protected function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            redirect('login');
            return;
        }

        $role = strtolower((string)($_SESSION['user']['role'] ?? ''));
        $route = trim((string)($_GET['route'] ?? ''), '/');

        if ($role === 'superuser' || $route === '' || $route === 'dashboard') {
            return;
        }

        $adminRoutes = [
            'clients', 'clients/show', 'processing-activities', 'processing-activities/create',
            'processing-activities/edit', 'assessments', 'assessments/create', 'assessments/edit',
            'findings', 'findings/create', 'tasks', 'tasks/create', 'departments', 'departments/create',
            'departments/edit', 'users', 'users/create', 'users/edit', 'forms',
            'forms/privacy-risk-assessment', 'reports', 'privacy-dashboard', 'settings'
        ];

        $clientRoutes = [
            'forms', 'forms/privacy-risk-assessment', 'settings'
        ];

        $allowed = $role === 'admin' ? $adminRoutes : ($role === 'client' ? $clientRoutes : []);

        foreach ($allowed as $prefix) {
            if ($route === $prefix || str_starts_with($route, $prefix . '/')) {
                return;
            }
        }

        http_response_code(403);
        $this->view('errors/403', [
            'title' => 'Access denied',
            'message' => 'Your account does not have permission to access this section.'
        ]);
        exit;
    }

    protected function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function role(): string
    {
        return strtolower((string)($this->user()['role'] ?? ''));
    }

    protected function isSuperuser(): bool
    {
        return $this->role() === 'superuser';
    }

    protected function isAdmin(): bool
    {
        return $this->role() === 'admin';
    }

    protected function isClientUser(): bool
    {
        return $this->role() === 'client';
    }

    protected function clientId(): ?int
    {
        $id = (int)($this->user()['client_id'] ?? 0);
        return $id > 0 ? $id : null;
    }

    protected function canAccessClient(int $clientId): bool
    {
        return $this->isSuperuser() || ($clientId > 0 && $this->clientId() === $clientId);
    }

    protected function requireClientAccess(int $clientId): void
    {
        if (!$this->canAccessClient($clientId)) {
            http_response_code(403);
            $this->view('errors/403', [
                'title' => 'Access denied',
                'message' => 'You do not have access to this client or its data.'
            ]);
            exit;
        }
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }
}