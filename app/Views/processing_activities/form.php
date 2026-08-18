<?php use App\Core\Security; ?>

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="page-title"><?= htmlspecialchars($activity ? 'Edit activity' : 'New activity') ?></div>
        <div class="page-subtitle mb-0"><?= htmlspecialchars($client['company_name']) ?></div>
    </div>
    <a class="btn btn-outline-secondary" href="<?= url('processing-activities', ['client_id' => (int)$client['id']]) ?>">Back to Activities</a>
</div>

<form method="post" action="<?= url($activity ? 'processing-activities/update' : 'processing-activities/store') ?>" class="card">
    <div class="card-body">
        <?php if ($activity): ?><input type="hidden" name="id" value="<?= (int)$activity['id'] ?>"><?php endif; ?>
        <input type="hidden" name="client_id" value="<?= (int)$client['id'] ?>">
        <?php include __DIR__.'/../partials/csrf.php'; ?>

        <div class="row g-4">
            <div class="col-12">
                <div class="fw-semibold mb-3">Activity details</div>
            </div>

            <div class="col-md-8">
                <label class="form-label">Activity name <span class="text-danger">*</span></label>
                <input required name="name" class="form-control" value="<?= Security::e($activity['name'] ?? '') ?>" placeholder="e.g. Customer account management">
            </div>

            <div class="col-md-4">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">— None —</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= (int)$d['id'] ?>" <?= (($activity['department'] ?? '') === $d['name']) ? 'selected' : '' ?>><?= Security::e($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Purpose</label>
                <textarea name="purpose" class="form-control" rows="3" placeholder="Why is this processing carried out?"><?= Security::e($activity['purpose'] ?? '') ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Legal basis</label>
                <input name="legal_basis" class="form-control" placeholder="Contract, Consent, Legal obligation…" value="<?= Security::e($activity['legal_basis'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Data subjects</label>
                <textarea name="data_subjects" class="form-control" rows="2" placeholder="Customers, employees, visitors…"><?= Security::e($activity['data_subjects'] ?? '') ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Personal data</label>
                <textarea name="personal_data_categories" class="form-control" rows="3" placeholder="Names, contact details, identifiers…"><?= Security::e($activity['personal_data'] ?? '') ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Recipients</label>
                <textarea name="recipients" class="form-control" rows="3" placeholder="Internal teams, processors, authorities…"><?= Security::e($activity['recipients'] ?? '') ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Retention period</label>
                <input name="retention_period" class="form-control" value="<?= Security::e($activity['retention_period'] ?? '') ?>" placeholder="e.g. 7 years">
            </div>

            <div class="col-md-3">
                <label class="form-label">Risk level</label>
                <?php $currentRisk = strtolower((string)($activity['risk_level'] ?? 'low')); ?>
                <select name="risk_level" class="form-select">
                    <?php foreach (['low','medium','high'] as $v): ?>
                        <option value="<?= $v ?>" <?= $currentRisk === $v ? 'selected' : '' ?>><?= ucfirst($v) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <?php $currentStatus = (int)($activity['status'] ?? 0); ?>
                <select name="status" class="form-select">
                    <option value="draft" <?= !$currentStatus ? 'selected' : '' ?>>Draft</option>
                    <option value="active" <?= $currentStatus ? 'selected' : '' ?>>Active</option>
                </select>
            </div>

            <div class="col-12">
                <div class="p-3 rounded border" style="background:var(--pv-surface-2);border-color:var(--pv-border)!important">
                    <div class="fw-semibold mb-3">Additional controls</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="special_category_data" value="1" id="special" <?= (!empty($activity['personal_data']) && str_contains((string)$activity['personal_data'], 'Special-category data involved')) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="special">Special-category data involved</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="international_transfer" value="1" id="transfer" <?= !empty($activity['international_transfer']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="transfer">International transfer</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Security measures</label>
                <textarea name="security_measures" class="form-control" rows="4" placeholder="Technical and organisational measures…"><?= Security::e($activity['security_measures'] ?? '') ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Transfer details</label>
                <textarea name="transfer_details" class="form-control" rows="4" placeholder="If applicable, describe the international transfer…"><?= Security::e($activity['transfer_details'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <a class="btn btn-outline-secondary" href="<?= url('processing-activities', ['client_id' => (int)$client['id']]) ?>">Cancel</a>
        <button class="btn btn-primary"><?= $activity ? 'Save changes' : 'Create activity' ?></button>
    </div>
</form>
