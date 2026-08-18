<?php

namespace App\Controllers;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function index()
{
    if ($this->isLoggedIn()) {
        redirect('dashboard');
    }

    redirect('login');
}

public function dashboard()
{
    $this->requireLogin();

    $db = \App\Core\Database::connect();

    $stats = [

        'users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),

        'clients' => $db->query("SELECT COUNT(*) FROM clients")->fetchColumn(),

        'reports' => 0,

        'tasks' => 0
    ];

    $recentClients = $db->query("
        SELECT *
        FROM clients
        ORDER BY id DESC
        LIMIT 5
    ")->fetchAll();

    $this->view('dashboard/index', [

        'title' => 'Dashboard',

        'user' => $_SESSION['user'],

        'stats' => $stats,

        'recentClients' => $recentClients
    ]);
}
}