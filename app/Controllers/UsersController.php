<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Flash;
use App\Models\User;

class UsersController extends BaseController
{
    public function index()
    {
        $this->requireLogin();

        $model = new User();

        $users = $model->getAllUsers();

        $this->view('users/index', [
            'title' => 'Users',
            'users' => $users
        ]);
    }

    public function create()
    {
        $this->requireLogin();

        $this->view('users/create', [
            'title' => 'Add User'
        ]);
    }

    public function store()
    {
        $this->requireLogin();

        $model = new User();

        $model->create([
            'fullname' => trim($_POST['fullname']),
            'email'    => trim($_POST['email']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role_id'  => (int) $_POST['role_id']
        ]);

        Flash::success('User created successfully.');

        redirect('users');
    }

    public function edit()
    {
        $this->requireLogin();

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            redirect('users');
        }

        $model = new User();

        $user = $model->find((int) $_GET['id']);

        if (!$user) {
            Flash::error('User not found.');
            redirect('users');
        }

        $this->view('users/edit', [
            'title' => 'Edit User',
            'user'  => $user
        ]);
    }

    public function update()
    {
        $this->requireLogin();

        $model = new User();

        $model->update([
            'id'       => (int) $_POST['id'],
            'fullname' => trim($_POST['fullname']),
            'email'    => trim($_POST['email']),
            'role_id'  => (int) $_POST['role_id'],
            'status'   => (int) $_POST['status']
        ]);

        Flash::success('User updated successfully.');

        redirect('users');
    }

    public function delete()
    {
        $this->requireLogin();

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            redirect('users');
        }

        $id = (int) $_GET['id'];

        // Prevent deleting your own account
        if ($this->user()['id'] == $id) {
            Flash::error('You cannot delete your own account.');
            redirect('users');
        }

        $model = new User();

        $user = $model->find($id);

        if (!$user) {
            Flash::error('User not found.');
            redirect('users');
        }

        // Prevent deleting the last Superuser
        if ($user['role_id'] == 1 && $model->countSuperusers() <= 1) {
            Flash::error('Cannot delete the last Superuser.');
            redirect('users');
        }

        $model->deleteUser($id);

        Flash::success('User deleted successfully.');

        redirect('users');
    }
}