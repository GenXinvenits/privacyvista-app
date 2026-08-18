<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use App\Core\Flash;
use App\Models\Client;

class DepartmentsController extends BaseController
{
    private function selectableClients(): array
    {
        $db = Database::connect();
        $clientId = (int)$this->clientId();
        if ($clientId > 0) {
            $stmt = $db->prepare('SELECT * FROM clients WHERE id = ?');
            $stmt->execute([$clientId]);
            return $stmt->fetchAll() ?: [];
        }
        return $db->query('SELECT * FROM clients ORDER BY company_name')->fetchAll() ?: [];
    }

    private function selectedClientId(): int
    {
        $requested = (int)($_GET['client_id'] ?? $_POST['client_id'] ?? 0);
        $sessionClient = (int)$this->clientId();
        if ($sessionClient > 0) {
            $this->requireClientAccess($sessionClient);
            return $sessionClient;
        }
        if ($requested > 0) {
            $this->requireClientAccess($requested);
            return $requested;
        }
        return 0;
    }

    public function index(): void
    {
        $this->requireLogin();
        $clientId = $this->selectedClientId();
        $clients = $this->selectableClients();
        $client = $clientId > 0 ? (new Client())->find($clientId) : null;
        $departments = [];
        if ($client) {
            $stmt = Database::connect()->prepare('SELECT department, COUNT(*) AS activity_count FROM processing_activities WHERE client_id = ? AND department IS NOT NULL AND TRIM(department) <> "" GROUP BY department ORDER BY department');
            $stmt->execute([$clientId]);
            $departments = $stmt->fetchAll() ?: [];
        }
        $this->view('departments/index', compact('client', 'clients', 'departments'));
    }

    public function create(): void
    {
        $this->requireLogin();
        $clientId = $this->selectedClientId();
        if ($clientId <= 0) { redirect('departments'); return; }
        $client = (new Client())->find($clientId);
        if (!$client) { Flash::error('Client not found.'); redirect('departments'); return; }
        $this->view('departments/create', ['title' => 'Add Department', 'client' => $client]);
    }
}
