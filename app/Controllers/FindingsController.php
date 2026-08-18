<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Security;
use App\Models\Assessment;
use App\Models\AssessmentFinding;
use App\Models\Client;

class FindingsController extends BaseController
{
    private function assessment(): ?array
    {
        $id = (int)($_GET['assessment_id'] ?? $_POST['assessment_id'] ?? 0);

        if ($id > 0) {
            return (new Assessment())->find($id);
        }

        // Global Findings navigation supplies a client context rather than
        // an assessment context. Use the client's most recent assessment.
        $clientId = (int)($_GET['client_id'] ?? $_POST['client_id'] ?? 0);
        if ($clientId <= 0) {
            return null;
        }

        $assessments = (new Assessment())->forClient($clientId);
        return $assessments[0] ?? null;
    }

    private function client(int $id): ?array
    {
        return (new Client())->find($id);
    }

    public function index(): void
    {
        $this->requireLogin();

        $assessment = $this->assessment();
        if (!$assessment) {
            $this->view('partials/client-context-required', [
                'title' => 'Select an assessment',
                'message' => 'Findings are recorded against an assessment. Please select a client with an assessment before viewing or managing findings.',
                'backRoute' => 'clients',
                'backLabel' => 'Select Client',
            ]);
            return;
        }

        $client = $this->client((int)$assessment['client_id']);
        $findings = (new AssessmentFinding())->forAssessment((int)$assessment['id']);
        $this->view('findings/index', compact('assessment', 'client', 'findings'));
    }

    public function create(): void
    {
        $this->requireLogin();
        $assessment = $this->assessment();

        if (!$assessment) {
            redirect('findings');
            return;
        }

        $client = $this->client((int)$assessment['client_id']);
        $this->view('findings/form', [
            'assessment' => $assessment,
            'client' => $client,
            'finding' => null
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }

        $assessment = $this->assessment();
        if (!$assessment || trim($_POST['title'] ?? '') === '') {
            http_response_code(422);
            exit('Finding title is required');
        }

        $_POST['assessment_id'] = $assessment['id'];
        (new AssessmentFinding())->create($_POST);
        redirect('findings', ['assessment_id' => (int)$assessment['id']]);
    }

    public function status(): void
    {
        $this->requireLogin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Invalid security token');
        }

        $assessment = $this->assessment();
        if ($assessment) {
            $status = $_POST['status'] ?? 'open';
            if (in_array($status, ['open', 'accepted', 'mitigated', 'closed'], true)) {
                (new AssessmentFinding())->updateStatus((int)$_POST['id'], $status);
            }
        }

        redirect('findings', ['assessment_id' => (int)($assessment['id'] ?? 0)]);
    }
}
