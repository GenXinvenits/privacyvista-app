<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Flash;
use App\Models\Client;

class ClientsController extends BaseController
{
    public function index()
    {
        $this->requireLogin();

        $model = new Client();

        $clients = $model->allClients();

        $this->view('clients/index', [
            'title'   => 'Clients',
            'clients' => $clients
        ]);
    }

    public function create()
    {
        $this->requireLogin();

        $this->view('clients/create', [
            'title' => 'Add Client'
        ]);
    }

    public function store()
    {
        $this->requireLogin();

        $model = new Client();

        $model->create([
            'company_name'   => trim($_POST['company_name']),
            'contact_person' => trim($_POST['contact_person']),
            'email'          => trim($_POST['email']),
            'phone'          => trim($_POST['phone'])
        ]);

        Flash::success('Client created successfully.');

        redirect('clients');
    }

    public function edit()
    {
        $this->requireLogin();

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            redirect('clients');
        }

        $model = new Client();

        $client = $model->find((int)$_GET['id']);

        if (!$client) {
            Flash::error('Client not found.');
            redirect('clients');
        }

        $this->view('clients/edit', [
            'title'  => 'Edit Client',
            'client' => $client
        ]);
    }

    public function update()
    {
        $this->requireLogin();

        $model = new Client();

        $model->update([
            'id'             => (int)$_POST['id'],
            'company_name'   => trim($_POST['company_name']),
            'contact_person' => trim($_POST['contact_person']),
            'email'          => trim($_POST['email']),
            'phone'          => trim($_POST['phone']),
            'status'         => (int)$_POST['status']
        ]);

        Flash::success('Client updated successfully.');

        redirect('clients');
    }

    public function delete()
    {
        $this->requireLogin();

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            redirect('clients');
        }

        $model = new Client();

        $client = $model->find((int)$_GET['id']);

        if (!$client) {
            Flash::error('Client not found.');
            redirect('clients');
        }

        $model->deleteClient((int)$_GET['id']);

        Flash::success('Client deleted successfully.');

        redirect('clients');
    }
    
    public function show()
{
    $this->requireLogin();

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        redirect('clients');
    }

    $model = new Client();

    $client = $model->find((int)$_GET['id']);

    if (!$client) {
        Flash::error('Client not found.');
        redirect('clients');
    }

    $this->view('clients/view', [
        'title'  => $client['company_name'],
        'client' => $client
    ]);
}



    
}