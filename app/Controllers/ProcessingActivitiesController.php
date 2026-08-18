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
        return $id > 0 ? (new Client())->find($id) : null;
    }

    private function requireClient(): array
    {
        $client = $this->client();
        if (!$client) {
            return [];
        }
        return $client;
    }

    public function index(): void
    {
        $this->requireLogin();

        $client = $this->requireClient();

        if (!$client) {
            $this->view('partials/client-context-required', [
                'title' => 'Select a client',
                'message' => 'Processing Activities belong to a specific client. Please select a client before viewing or managing processing activities.',
                'backRoute' => 'clients',
                'backLabel' => 'Select Client',
            ]);
            return;
        }

        $activities = (new ProcessingActivity())->forClient((int)$client['id']);
        $this->view('processing_activities/index', compact('client', 'activities'));
    }

    public function create(): void
    {
        $this->requireLogin();
        $client = $this->requireClient();

        if (!$client) {
            redirect('processing-activities');
            return;
        }

        $departments = Database::connect()->query('SELECT * FROM departments ORDER BY name')->fetchAll();
        $this->view('processing_activities/form', [
            'client' => $client,
            'departments' => $departments,
            'activity' => null,
            'title' => 'New processing activity'
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }

        $client = $this->requireClient();
        if (!$client) {
            redirect('processing-activities');
            return;
        }

        if (trim($_POST['name'] ?? '') === '') {
            http_response_code(422);
            exit('Activity name is required');
        }

        $data = $_POST;
        $data['client_id'] = (int)$client['id'];
        $data['owner_user_id'] = (int)($_SESSION['user']['id'] ?? 0);

        (new ProcessingActivity())->create($data);
        redirect('processing-activities?client_id=' . $client['id']);
    }

    public function edit(): void
    {
        $this->requireLogin();
        $client = $this->requireClient();

        if (!$client) {
            redirect('processing-activities');
            return;
        }

        $id = (int)($_GET['id'] ?? 0);
        $activity = (new ProcessingActivity())->find($id);

        if (!$activity || (int)$activity['client_id'] !== (int)$client['id']) {
            http_response_code(404);
            exit('Processing activity not found');
        }

        $departments = Database::connect()->query('SELECT * FROM departments ORDER BY name')->fetchAll();
        $this->view('processing_activities/form', [
            'client' => $client,
            'departments' => $departments,
            'activity' => $activity,
            'title' => 'Edit processing activity'
        ]);
    }

    public function update(): void
    {
        $this->requireLogin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }

        $client = $this->requireClient();
        if (!$client) {
            redirect('processing-activities');
            return;
        }

        $_POST['client_id'] = (int)$client['id'];
        (new ProcessingActivity())->update($_POST);
        redirect('processing-activities?client_id=' . $client['id']);
    }

    public function delete(): void
    {
        $this->requireLogin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }

        $client = $this->requireClient();
        if (!$client) {
            redirect('processing-activities');
            return;
        }

        (new ProcessingActivity())->deleteForClient(
            (int)($_POST['id'] ?? 0),
            (int)$client['id']
        );

        redirect('processing-activities?client_id=' . $client['id']);
    }
}
