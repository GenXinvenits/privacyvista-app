<?php $title = 'Departments'; ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Departments</h1>
            <div class="text-muted">Manage departments for each client.</div>
        </div>
        <?php if ($client): ?>
            <a class="btn btn-primary" href="<?= url('processing-activities/create', ['client_id' => (int)$client['id']]) ?>">+ Add department</a>
        <?php endif; ?>
    </div>

    <?php if ($clients): ?>
    <div class="card mb-4">
        <div class="card-header">
            <strong>Select client</strong>
            <div class="small text-muted">Choose the organisation whose departments you want to view.</div>
        </div>
        <div class="card-body">
            <select class="form-select" id="departments-client" aria-label="Select client">
                <option value="">Select a client...</option>
                <?php foreach ($clients as $item): ?>
                    <option value="<?= (int)$item['id'] ?>" <?= $client && (int)$client['id'] === (int)$item['id'] ? 'selected' : '' ?>><?= e($item['company_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($client): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Departments for <?= e($client['company_name']) ?></strong>
            <span class="badge bg-secondary"><?= count($departments) ?> <?= count($departments) === 1 ? 'department' : 'departments' ?></span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Department</th><th>Activities</th></tr></thead>
                <tbody>
                <?php foreach ($departments as $department): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($department['department']) ?></td>
                        <td><?= (int)$department['activity_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$departments): ?>
                    <tr><td colspan="2" class="text-center text-muted py-5">No departments recorded for this client.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if ($clients): ?>
<script>
document.getElementById('departments-client')?.addEventListener('change', function () {
    if (!this.value) return;
    const u = new URL(window.location.href);
    u.searchParams.set('route', 'departments');
    u.searchParams.set('client_id', this.value);
    window.location.href = u.toString();
});
</script>
<?php endif; ?>
