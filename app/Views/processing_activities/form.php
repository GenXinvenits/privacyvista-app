<?php use App\Core\Security; ?>
<div class="container py-4">
    <div class="mb-4"><div class="text-muted small"><?= htmlspecialchars($client['company_name']) ?></div><h1 class="h3 mb-0"><?= htmlspecialchars($title) ?></h1></div>
    <form method="post" action="<?= $activity ? 'processing-activities/update' : 'processing-activities/store' ?>" class="card border-0 shadow-sm"><div class="card-body">
        <?php if ($activity): ?><input type="hidden" name="id" value="<?= (int)$activity['id'] ?>">
        <?php endif; ?><input type="hidden" name="client_id" value="<?= (int)$client['id'] ?>"><?php include __DIR__.'/../partials/csrf.php'; ?>
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Activity name *</label><input required name="name" class="form-control" value="<?= Security::e($activity['name'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">Department</label><select name="department_id" class="form-select"><option value="">— None —</option><?php foreach($departments as $d): ?><option value="<?= (int)$d['id'] ?>" <?= (($activity['department_id'] ?? '') == $d['id'])?'selected':'' ?>><?= Security::e($d['name']) ?></option><?php endforeach; ?></select></div>
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"><?= Security::e($activity['description'] ?? '') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Purpose</label><textarea name="purpose" class="form-control" rows="3"><?= Security::e($activity['purpose'] ?? '') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Legal basis</label><input name="legal_basis" class="form-control" placeholder="e.g. Contract, Consent, Legal obligation" value="<?= Security::e($activity['legal_basis'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Data subjects</label><textarea name="data_subjects" class="form-control" rows="3"><?= Security::e($activity['data_subjects'] ?? '') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Personal data categories</label><textarea name="personal_data_categories" class="form-control" rows="3"><?= Security::e($activity['personal_data_categories'] ?? '') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Recipients</label><textarea name="recipients" class="form-control" rows="3"><?= Security::e($activity['recipients'] ?? '') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Retention period</label><input name="retention_period" class="form-control" value="<?= Security::e($activity['retention_period'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">Risk level</label><select name="risk_level" class="form-select"><?php foreach(['low','medium','high','critical'] as $v): ?><option value="<?= $v ?>" <?= (($activity['risk_level'] ?? 'medium')===$v)?'selected':'' ?>><?= ucfirst($v) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach(['draft','active','under_review','archived'] as $v): ?><option value="<?= $v ?>" <?= (($activity['status'] ?? 'draft')===$v)?'selected':'' ?>><?= ucwords(str_replace('_',' ',$v)) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="special_category_data" value="1" id="special" <?= !empty($activity['special_category_data'])?'checked':'' ?>><label class="form-check-label" for="special">Special-category data involved</label></div></div>
            <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="international_transfer" value="1" id="transfer" <?= !empty($activity['international_transfer'])?'checked':'' ?>><label class="form-check-label" for="transfer">International transfer</label></div></div>
            <div class="col-12"><label class="form-label">Transfer details</label><textarea name="transfer_details" class="form-control" rows="2"><?= Security::e($activity['transfer_details'] ?? '') ?></textarea></div>
            <div class="col-12"><label class="form-label">Security measures</label><textarea name="security_measures" class="form-control" rows="3" placeholder="Technical and organisational measures..."><?= Security::e($activity['security_measures'] ?? '') ?></textarea></div>
        </div>
    </div><div class="card-footer bg-white d-flex justify-content-between"><a class="btn btn-outline-secondary" href="processing-activities?client_id=<?= (int)$client['id'] ?>">Cancel</a><button class="btn btn-primary">Save activity</button></div></form>
</div>
