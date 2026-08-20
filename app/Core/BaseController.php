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
        $roleId = (int)($_SESSION['user']['role_id'] ?? 0);
        $route = trim((string)($_GET['route'] ?? ''), '/');

        // Role ID 1 is the canonical Superuser role. Keep the name check for
        // compatibility with existing sessions created before role_id was stored.
        if ($role === 'superuser' || $roleId === 1 || $route === '' || $route === 'dashboard') {
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
        return $this->role() === 'superuser' || (int)($this->user()['role_id'] ?? 0) === 1;
    }

    protected function isAdmin(): bool
    {
        return $this->role() === 'admin' || (int)($this->user()['role_id'] ?? 0) === 2;
    }

    protected function isClientUser(): bool
    {
        return $this->role() === 'client' || (int)($this->user()['role_id'] ?? 0) === 3;
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