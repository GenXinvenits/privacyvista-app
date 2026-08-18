<?php $title = 'Processing activities'; ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><div class="text-muted small"><?= htmlspecialchars($client['company_name']) ?></div><h1 class="h3 mb-0">Processing activities</h1></div>
        <a class="btn btn-primary" href="processing-activities/create?client_id=<?= (int)$client['id'] ?>">Add activity</a>
    </div>
    <div class="card border-0 shadow-sm"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0"><thead><tr><th>Name</th><th>Purpose</th><th>Legal basis</th><th>Risk</th><th>Status</th><th></th></tr></thead><tbody>
    <?php foreach ($activities as $activity): ?>
        <tr><td class="fw-semibold"><?= htmlspecialchars($activity['name']) ?></td><td><?= htmlspecialchars($activity['purpose'] ?: '—') ?></td><td><?= htmlspecialchars($activity['legal_basis'] ?: '—') ?></td><td><span class="badge text-bg-<?= $activity['risk_level']==='critical'||$activity['risk_level']==='high' ? 'danger' : ($activity['risk_level']==='medium' ? 'warning' : 'success') ?>"><?= htmlspecialchars(ucfirst($activity['risk_level'])) ?></span></td><td><?= htmlspecialchars(ucwords(str_replace('_',' ',$activity['status']))) ?></td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="processing-activities/edit?id=<?= (int)$activity['id'] ?>&client_id=<?= (int)$client['id'] ?>">Edit</a></td></tr>
    <?php endforeach; ?>
    <?php if (!$activities): ?><tr><td colspan="6" class="text-center text-muted py-5">No processing activities recorded yet.</td></tr><?php endif; ?>
    </tbody></table></div></div></div>
</div>
