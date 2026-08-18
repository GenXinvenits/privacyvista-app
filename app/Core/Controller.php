<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/layouts/sidebar.php';
        require __DIR__ . '/../Views/layouts/navbar.php';
        
        \App\Core\Flash::display();

        require $viewFile;

        require __DIR__ . '/../Views/layouts/footer.php';
    }
}