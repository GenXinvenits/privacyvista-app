<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class AuthController extends Controller
{
    public function login()
    {
        $this->view('auth/login');
    }

    public function authenticate()
    {
        $db = Database::connect();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $db->prepare("
            SELECT u.*, r.name AS role
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: /app/public/index.php?route=login');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'fullname' => $user['fullname'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        header('Location: /app/public/index.php?route=dashboard');
        exit;
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        header('Location: /app/public/index.php?route=login');
        exit;
    }
}