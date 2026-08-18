<?php
$title = 'Privacy Risk Assessment';
$clients = $clients ?? [];
$clientId = (int) ($_GET['client_id'] ?? 0);
?>

<div class="page-header mb-4">
    <div>
        <div class="page-title">Privacy Risk Assessment</div>
        <div class="page-subtitle mb-0">Create a privacy risk assessment for a client and processing activity.</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="post" action="/app/public/index.php?route=assessments/create">
            <input type="hidden" name="form_type" value="privacy-risk-assessment">

            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="client_id">Client</label>
                    <select class="form-select" id="client_id" name="client_id" required>
                        <option value="">Select client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= (int) $client['id'] ?>" <?= $clientId === (int) $client['id'] ? 'selected' : '' ?>>
                                <?= e($client['company_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label" for="title">Assessment title</label>
                    <input class="form-control" id="title" name="title" type="text" required placeholder="e.g. Customer portal privacy risk assessment">
                </div>

                <div class="col-12">
                    <label class="form-label" for="description">Assessment scope</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the processing, project, system, or change being assessed."></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="/app/public/index.php?route=forms">Cancel</a>
                <button class="btn btn-primary" type="submit">Start assessment</button>
            </div>
        </form>
    </div>
</div>
