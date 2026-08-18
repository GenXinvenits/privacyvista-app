<?php
$title = 'Privacy Risk Assessment';
$clients = $clients ?? [];
?>

<div class="page-header mb-4">
    <div>
        <div class="page-title">Privacy Risk Assessment</div>
        <div class="page-subtitle mb-0">Create a privacy risk assessment for a client and processing activity.</div>
    </div>
</div>

<div class="card">
    <form method="post" action="<?= url('assessments/store') ?>">
        <?php include __DIR__ . '/../partials/csrf.php'; ?>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="client_id">Client</label>
                    <select class="form-select" id="client_id" name="client_id" required>
                        <option value="">Select client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= (int) $client['id'] ?>"><?= e($client['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="title">Assessment title</label>
                    <input class="form-control" id="title" name="title" type="text" required placeholder="e.g. Customer portal privacy risk assessment">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="assessment_type">Assessment type</label>
                    <select class="form-select" id="assessment_type" name="assessment_type">
                        <option value="privacy_review">Privacy Review</option>
                        <option value="dpia">DPIA</option>
                        <option value="vendor_review">Vendor Review</option>
                        <option value="transfer_review">Transfer Review</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="risk_score">Initial risk score (0–100)</label>
                    <input class="form-control" id="risk_score" name="risk_score" type="number" min="0" max="100" step="0.01" placeholder="Optional">
                </div>
                <div class="col-12">
                    <label class="form-label" for="findings">Initial findings</label>
                    <textarea class="form-control" id="findings" name="findings" rows="4" placeholder="Describe identified privacy risks or observations."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label" for="recommendations">Recommendations</label>
                    <textarea class="form-control" id="recommendations" name="recommendations" rows="4" placeholder="Describe recommended safeguards or mitigation measures."></textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="due_date">Due date</label>
                    <input class="form-control" id="due_date" name="due_date" type="date">
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a class="btn btn-outline-secondary" href="<?= url('forms') ?>">Cancel</a>
            <button class="btn btn-primary" type="submit">Start assessment</button>
        </div>
    </form>
</div>
