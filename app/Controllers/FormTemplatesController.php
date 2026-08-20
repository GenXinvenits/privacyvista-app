<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Security;
use App\Models\FormTemplate;

class FormTemplatesController extends BaseController
{
    private function requireSuperuser(): void
    {
        $this->requireLogin();
        if (strtolower((string)($_SESSION['user']['role'] ?? '')) !== 'superuser') {
            http_response_code(403);
            exit('Superuser access required');
        }
    }

    public function index(): void
    {
        $this->requireSuperuser();
        $this->view('form-templates/index', ['title' => 'Form Templates', 'templates' => (new FormTemplate())->all()]);
    }

    public function edit(): void
    {
        $this->requireSuperuser();
        $slug = trim((string)($_GET['slug'] ?? ''));
        $model = new FormTemplate();
        $template = $model->active($slug);
        if (!$template) { http_response_code(404); exit('Form template not found'); }
        $this->view('form-templates/edit', ['title' => 'Edit Form — '.$template['name'], 'template' => $template]);
    }

    public function versions(): void
    {
        $this->requireSuperuser();
        $slug = trim((string)($_GET['slug'] ?? ''));
        $model = new FormTemplate();
        $template = $model->findBySlug($slug);
        if (!$template) { http_response_code(404); exit('Form template not found'); }
        $this->view('form-templates/versions', ['title' => 'Version History — '.$template['name'], 'template' => $template, 'versions' => $model->versions($slug)]);
    }

    public function viewVersion(): void
    {
        $this->requireSuperuser();
        $id = (int)($_GET['id'] ?? 0);
        $version = (new FormTemplate())->version($id);
        if (!$version) { http_response_code(404); exit('Form version not found'); }
        $this->view('form-templates/view-version', [
            'title' => 'View Version — '.$version['name'].' v'.$version['version_number'],
            'version' => $version,
        ]);
    }

    public function cloneVersion(): void
    {
        $this->requireSuperuser();
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) { http_response_code(419); exit('Invalid security token'); }
        $id = (int)($_POST['version_id'] ?? 0);
        $version = (new FormTemplate())->version($id);
        if (!$version) { http_response_code(404); exit('Form version not found'); }
        $newId = (new FormTemplate())->createVersion(
            (string)$version['slug'],
            $version['definition'],
            (int)($_SESSION['user']['id'] ?? 0),
            'Cloned from version '.$version['version_number']
        );
        redirect('form-templates/edit?slug='.rawurlencode((string)$version['slug']));
    }

    public function saveVersion(): void
    {
        $this->requireSuperuser();
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) { http_response_code(419); exit('Invalid security token'); }
        $slug = trim((string)($_POST['slug'] ?? ''));
        $definition = json_decode((string)($_POST['definition'] ?? ''), true);
        if (!is_array($definition) || !isset($definition['sections']) || !is_array($definition['sections'])) {
            http_response_code(422); exit('Invalid form definition');
        }
        (new FormTemplate())->createVersion($slug, $definition, (int)($_SESSION['user']['id'] ?? 0), (string)($_POST['change_summary'] ?? ''));
        redirect('form-templates/versions?slug='.rawurlencode($slug));
    }
}
