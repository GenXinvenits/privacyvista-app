<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use App\Core\Security;
use App\Models\Client;
use App\Models\ProcessingActivity;

class ProcessingActivitiesController extends BaseController
{
    private function client(): ?array
    {
        $id = (int)($_GET['client_id'] ?? $_POST['client_id'] ?? 0);
        if ($id <= 0 && $this->clientId()) $id = $this->clientId();
        return $id > 0 ? (new Client())->find($id) : null;
    }

    private function requireClient(): array
    {
        $client = $this->client();
        if (!$client) return [];
        $this->requireClientAccess((int)$client['id']);
        return $client;
    }

    private function canSelectClient(): bool
    {
        $role = strtolower((string)($_SESSION['user']['role'] ?? ''));
        return in_array($role, ['superuser', 'admin'], true);
    }

    private function resolveDepartment(array &$data, int $clientId): void
    {
        $departmentId = (int)($data['department_id'] ?? 0);
        if ($departmentId <= 0) {
            $data['department'] = trim((string)($data['department'] ?? ''));
            return;
        }

        $s = Database::connect()->prepare('SELECT name FROM departments WHERE id=? AND client_id=? LIMIT 1');
        $s->execute([$departmentId, $clientId]);
        $data['department'] = (string)($s->fetchColumn() ?: '');
    }

    public function index(): void
    {
        $this->requireLogin();

        $client = $this->requireClient();
        $clients = [];
        $activities = [];

        // Superusers and admins always get the client selector on this page.
        // Client users remain restricted to their authenticated client.
        if ($this->canSelectClient()) {
            $clients = (new Client())->allClients();
        }

        if ($client) {
            $activities = (new ProcessingActivity())->forClient((int)$client['id']);
        }

        $this->view('processing_activities/index', compact('client', 'clients', 'activities'));
    }

    public function create(): void
    {
        $this->requireLogin();
        $client = $this->requireClient();
        if (!$client) { redirect('processing-activities'); return; }
        $departments = Database::connect()->query('SELECT * FROM departments WHERE client_id='.(int)$client['id'].' ORDER BY name')->fetchAll();
        $this->view('processing_activities/form', [
            'client'=>$client,
            'departments'=>$departments,
            'activity'=>null,
            'title'=>'New activity'
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) { http_response_code(419); exit('Invalid security token'); }
        $client = $this->requireClient();
        if (!$client) { redirect('processing-activities'); return; }
        if (trim($_POST['name'] ?? '') === '') { http_response_code(422); exit('Activity name is required'); }
        $data = $_POST;
        $data['client_id'] = (int)$client['id'];
        $this->resolveDepartment($data, (int)$client['id']);
        (new ProcessingActivity())->create($data);
        redirect('processing-activities?client_id='.$client['id']);
    }

    public function edit(): void
    {
        $this->requireLogin();
        $client = $this->requireClient();
        if (!$client) { redirect('processing-activities'); return; }
        $id = (int)($_GET['id'] ?? 0);
        $activity = (new ProcessingActivity())->find($id);
        if (!$activity || (int)$activity['client_id'] !== (int)$client['id']) { http_response_code(404); exit('Activity not found'); }
        $departments = Database::connect()->query('SELECT * FROM departments WHERE client_id='.(int)$client['id'].' ORDER BY name')->fetchAll();
        $this->view('processing_activities/form', [
            'client'=>$client,
            'departments'=>$departments,
            'activity'=>$activity,
            'title'=>'Edit activity'
        ]);
    }

    public function update(): void
    {
        $this->requireLogin();
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) { http_response_code(419); exit('Invalid security token'); }
        $client = $this->requireClient();
        if (!$client) { redirect('processing-activities'); return; }
        $_POST['client_id'] = (int)$client['id'];
        $this->resolveDepartment($_POST, (int)$client['id']);
        (new ProcessingActivity())->update($_POST);
        redirect('processing-activities?client_id='.$client['id']);
    }

    public function delete(): void
    {
        $this->requireLogin();
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) { http_response_code(419); exit('Invalid security token'); }
        $client = $this->requireClient();
        if (!$client) { redirect('processing-activities'); return; }
        (new ProcessingActivity())->deleteForClient((int)($_POST['id'] ?? 0), (int)$client['id']);
        redirect('processing-activities?client_id='.$client['id']);
    }
}
