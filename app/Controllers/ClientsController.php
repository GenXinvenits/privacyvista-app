<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Flash;
use App\Core\Security;
use App\Models\Client;

class ClientsController extends BaseController
{
    private function validCsrf(): void
    {
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }
    }

    public function index()
    {
        $this->requireLogin();
        $this->view('clients/index', ['title' => 'Clients', 'clients' => (new Client())->allClients()]);
    }

    public function create()
    {
        $this->requireLogin();
        $this->view('clients/create', ['title' => 'Add Client']);
    }

    public function store()
    {
        $this->requireLogin();
        $this->validCsrf();

        $company = trim($_POST['company_name'] ?? '');
        if ($company === '') {
            http_response_code(422);
            exit('Company name is required');
        }

        (new Client())->create([
            'company_name' => $company,
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? '')
        ]);

        Flash::success('Client created successfully.');
        redirect('clients');
    }

    public function edit()
    {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $client = $id > 0 ? (new Client())->find($id) : null;
        if (!$client) {
            Flash::error('Client not found.');
            redirect('clients');
        }
        $this->view('clients/edit', ['title' => 'Edit Client', 'client' => $client]);
    }

    public function update()
    {
        $this->requireLogin();
        $this->validCsrf();

        $id = (int)($_POST['id'] ?? 0);
        if ($id < 1 || trim($_POST['company_name'] ?? '') === '') {
            http_response_code(422);
            exit('Invalid client data');
        }

        (new Client())->update([
            'id' => $id,
            'company_name' => trim($_POST['company_name']),
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'status' => !empty($_POST['status']) ? 1 : 0
        ]);

        Flash::success('Client updated successfully.');
        redirect('clients');
    }

    public function delete()
    {
        $this->requireLogin();
        $this->validCsrf();

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            (new Client())->deleteClient($id);
            Flash::success('Client deleted successfully.');
        }
        redirect('clients');
    }

    public function show()
    {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $client = $id > 0 ? (new Client())->find($id) : null;
        if (!$client) {
            Flash::error('Client not found.');
            redirect('clients');
        }
        $this->view('clients/view', ['title' => $client['company_name'], 'client' => $client]);
    }
}
