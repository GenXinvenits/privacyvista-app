<?php

namespace App\Core;

class Flash
{
    public static function success(string $message): void
    {
        $_SESSION['success'] = $message;
    }

    public static function error(string $message): void
    {
        $_SESSION['error'] = $message;
    }

    public static function display(): void
    {
        if (!empty($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['success']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div>';

            unset($_SESSION['success']);
        }

        if (!empty($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['error']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div>';

            unset($_SESSION['error']);
        }
    }
}