<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Flash;
use App\Models\Client;
use App\Models\Department;

class DepartmentsController extends BaseController
{
    public function index()
    {
        $this->requireLogin();

        if (!isset($_GET['client_id']) || !is_numeric($_GET['client_id'])) {
            redirect('clients');
        }

        $clientId = (int)$_GET['client_id'];

        $clientModel = new Client();
        $client = $clientModel->find($clientId);

        if (!$client) {
            Flash::error('Client not found.');
            redirect('clients');
        }

        $departmentModel = new Department();

        $departments = $departmentModel->allByClient($clientId);

        $this->view('departments/index', [
            'title' => 'Departments',
            'client' => $client,
            'departments' => $departments
        ]);
    }

    public function create()
    {
        $this->requireLogin();

        if (!isset($_GET['client_id']) || !is_numeric($_GET['client_id'])) {
            redirect('clients');
        }

        $clientModel = new Client();

        $client = $clientModel->find((int)$_GET['client_id']);

        $this->view('departments/create', [
            'title' => 'Add Department',
            'client' => $client
        ]);
    }

    public function store()
    {
        $this->requireLogin();

        $model = new Department();

        $model->create([
            'client_id' => (int)$_POST['client_id'],
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description'])
        ]);

        Flash::success('Department created successfully.');

        redirect('departments&client_id=' . (int)$_POST['client_id']);
    }
}
