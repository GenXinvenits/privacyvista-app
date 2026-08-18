<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Flash;
use App\Core\Security;
use App\Models\User;

class SettingsController extends BaseController
{
    public function index(): void
    {
        $this->requireLogin();
        $user = $this->user();
        $model = new User();
        $account = $model->find((int)$user['id']);

        $this->view('settings/index', [
            'title' => 'Settings',
            'account' => $account ?: $user,
        ]);
    }

    public function profile(): void
    {
        $this->requireLogin();
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }

        $user = $this->user();
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($fullname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::error('Please provide a valid name and email address.');
            redirect('settings');
        }

        $model = new User();
        if (!$model->updateProfile((int)$user['id'], $fullname, $email)) {
            Flash::error('Unable to update your profile. The email address may already be in use.');
            redirect('settings');
        }

        $_SESSION['user']['fullname'] = $fullname;
        $_SESSION['user']['email'] = $email;
        Flash::success('Profile updated successfully.');
        redirect('settings');
    }

    public function password(): void
    {
        $this->requireLogin();
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new === '' || strlen($new) < 8) {
            Flash::error('New password must contain at least 8 characters.');
            redirect('settings');
        }
        if ($new !== $confirm) {
            Flash::error('New password and confirmation do not match.');
            redirect('settings');
        }

        $user = $this->user();
        $model = new User();
        $account = $model->find((int)$user['id']);

        if (!$account || !password_verify($current, $account['password'])) {
            Flash::error('Current password is incorrect.');
            redirect('settings');
        }

        $model->updatePassword((int)$user['id'], password_hash($new, PASSWORD_DEFAULT));
        Flash::success('Password changed successfully.');
        redirect('settings');
    }
}
