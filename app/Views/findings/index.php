<?php $title = 'Findings'; ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Findings</h1>
            <div class="text-muted">Manage findings identified during privacy assessments.</div>
        </div>
        <?php if ($assessment): ?>
            <a class="btn btn-primary" href="<?= url('findings/create', ['client_id' => (int)$client['id'], 'assessment_id' => (int)$assessment['id']]) ?>">New finding</a>
        <?php endif; ?>
    </div>

    <?php if ($clients): ?>
    <div class="card mb-4">
        <div class="card-header">
            <strong>Select client</strong>
            <div class="small text-muted">Choose the organisation whose findings you want to view.</div>
        </div>
        <div class="card-body">
            <select class="form-select" id="findings-client" aria-label="Select client">
                <option value="">Select a client...</option>
                <?php foreach ($clients as $item): ?>
                    <?php $selected = $client && (int)$client['id'] === (int)$item['id']; ?>
                    <option value="<?= (int)$item['id'] ?>" <?= $selected ? 'selected' : '' ?>><?= e($item['company_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($client): ?>
    <div class="card mb-4">
        <div class="card-header">
            <strong>Select assessment</strong>
            <div class="small text-muted">Choose the assessment whose findings you want to view.</div>
        </div>
        <div class="card-body">
            <select class="form-select" id="findings-assessment" aria-label="Select assessment">
                <option value="">Select an assessment...</option>
                <?php foreach ($assessments as $item): ?>
                    <?php $selected = $assessment && (int)$assessment['id'] === (int)$item['id']; ?>
                    <option value="<?= (int)$item['id'] ?>" <?= $selected ? 'selected' : '' ?>><?= e($item['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($assessment): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Findings for <?= e($assessment['title']) ?></strong>
                <div class="small text-muted"><?= e($client['company_name']) ?></div>
            </div>
            <span class="badge bg-primary"><?= count($findings) ?> <?= count($findings) === 1 ? 'finding' : 'findings' ?></span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Finding</th><th>Severity</th><th>Risk</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($findings as $f): ?>
                    <tr>
                        <td><div class="fw-semibold"><?= e($f['title']) ?></div><small class="text-muted"><?= e($f['recommendation'] ?? '') ?></small></td>
                        <td><?= e(ucfirst($f['severity'])) ?></td>
                        <td><?= ((int)$f['likelihood']) * ((int)$f['impact']) ?>/25</td>
                        <td><?= e(ucfirst($f['status'])) ?></td>
                        <td>
                            <form method="post" action="<?= url('findings/status', ['client_id' => (int)$client['id'], 'assessment_id' => (int)$assessment['id']]) ?>" class="d-flex gap-1">
                                <?php include __DIR__ . '/../partials/csrf.php'; ?>
                                <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="open">Open</option>
                                    <option value="accepted">Accepted</option>
                                    <option value="mitigated">Mitigated</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$findings): ?>
                    <tr><td colspan="5" class="text-center text-muted py-5">No findings recorded.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const findingsClient = document.getElementById('findings-client');
const findingsAssessment = document.getElementById('findings-assessment');

findingsClient?.addEventListener('change', function () {
    if (!this.value) return;
    const u = new URL(window.location.href);
    u.searchParams.set('route', 'findings');
    u.searchParams.set('client_id', this.value);
    u.searchParams.delete('assessment_id');
    window.location.href = u.toString();
});

findingsAssessment?.addEventListener('change', function () {
    if (!this.value) return;
    const u = new URL(window.location.href);
    u.searchParams.set('route', 'findings');
    u.searchParams.set('client_id', '<?= (int)($client['id'] ?? 0) ?>');
    u.searchParams.set('assessment_id', this.value);
    window.location.href = u.toString();
});
</script>
