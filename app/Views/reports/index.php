<?php
$title = 'Reports';
$clientName = $client['company_name'] ?? 'Select a client';
$completion = $summary['assessments'] > 0 ? round(($summary['completed_assessments'] / $summary['assessments']) * 100) : 0;
?>

<div class="reports-page">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><div class="page-title">Reports</div><div class="page-subtitle mb-0">Privacy governance summaries and operational insights.</div></div>
        <?php if ($client): ?><button type="button" class="btn btn-secondary" onclick="window.print()">Print report</button><?php endif; ?>
    </div>
    <div class="card mb-4 reports-filter-card"><div class="card-body">
        <form method="get" action="/app/public/index.php" class="row g-3 align-items-end">
            <input type="hidden" name="route" value="reports">
            <div class="col-12">
                <label class="form-label">Client</label>
                <select class="form-select" name="client_id" onchange="this.form.submit()">
                    <option value="">Choose a client...</option>
                    <?php foreach ($clients as $item): ?><option value="<?= (int)$item['id'] ?>" <?= $client && (int)$client['id'] === (int)$item['id'] ? 'selected' : '' ?>><?= e($item['company_name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <?php if ($client): ?><div class="report-client-state"><span class="report-state-dot"></span>Reporting for <strong><?= e($clientName) ?></strong></div>
                <?php else: ?><div class="report-client-state muted">Select a client to generate a report.</div><?php endif; ?>
            </div>
        </form>
    </div></div>

    <?php if (!$client): ?>
        <div class="card reports-empty"><div class="card-body text-center py-5"><div class="report-empty-icon">R</div><h2 class="h5 mt-3">Choose a client</h2><p class="text-muted mb-0">Reports are generated from the selected client's processing activities, assessments, findings and remediation tasks.</p></div></div>
    <?php else: ?>
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3"><div class="card report-stat"><div class="card-body"><span class="report-stat-label">Processing activities</span><strong><?= $summary['activities'] ?></strong><small><?= $summary['active_activities'] ?> active</small></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card report-stat"><div class="card-body"><span class="report-stat-label">Assessments</span><strong><?= $summary['assessments'] ?></strong><small><?= $completion ?>% completed</small></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card report-stat"><div class="card-body"><span class="report-stat-label">Open findings</span><strong><?= $summary['open_findings'] ?></strong><small><?= $summary['findings'] ?> total findings</small></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card report-stat"><div class="card-body"><span class="report-stat-label">Open tasks</span><strong><?= $summary['open_tasks'] ?></strong><small><?= $summary['overdue_tasks'] ?> overdue</small></div></div></div>
        </div>
        <div class="card mb-4"><div class="card-header d-flex justify-content-between align-items-center"><div><strong>Privacy posture</strong><div class="text-muted small mt-1">Current snapshot for <?= e($clientName) ?></div></div><span class="badge bg-primary">Live</span></div><div class="card-body"><div class="report-progress-row"><span>Assessment completion</span><strong><?= $completion ?>%</strong></div><div class="report-progress"><span style="width: <?= $completion ?>%"></span></div><div class="report-health-grid mt-4"><div><span>Activities</span><strong><?= $summary['activities'] ?></strong></div><div><span>Assessments complete</span><strong><?= $summary['completed_assessments'] ?></strong></div><div><span>Findings open</span><strong><?= $summary['open_findings'] ?></strong></div><div><span>Overdue remediation</span><strong><?= $summary['overdue_tasks'] ?></strong></div></div></div></div>
        <div class="row g-4"><div class="col-xl-7"><div class="card h-100"><div class="card-header"><strong>Recent assessments</strong></div><div class="table-responsive"><table class="table"><thead><tr><th>Assessment</th><th>Status</th><th>Risk</th><th>Due</th></tr></thead><tbody><?php if (!$recentAssessments): ?><tr><td colspan="4" class="text-muted text-center py-4">No assessments yet.</td></tr><?php endif; ?><?php foreach ($recentAssessments as $row): ?><tr><td><strong><?= e($row['title']) ?></strong><div class="small text-muted"><?= e($row['assessment_type']) ?></div></td><td><span class="badge bg-secondary"><?= e($row['status']) ?></span></td><td><?= $row['risk_score'] !== null ? e($row['risk_score']) : '—' ?></td><td><?= e($row['due_date'] ?: '—') ?></td></tr><?php endforeach; ?></tbody></table></div></div></div><div class="col-xl-5"><div class="card h-100"><div class="card-header"><strong>Open findings</strong></div><div class="card-body report-list"><?php if (!$recentFindings): ?><div class="text-muted text-center py-4">No findings recorded.</div><?php endif; ?><?php foreach ($recentFindings as $row): ?><div class="report-list-item"><div><strong><?= e($row['title']) ?></strong><small><?= e($row['assessment_title']) ?></small></div><span class="badge bg-<?= $row['severity'] === 'critical' || $row['severity'] === 'high' ? 'danger' : ($row['severity'] === 'medium' ? 'warning' : 'secondary') ?>"><?= e($row['severity']) ?></span></div><?php endforeach; ?></div></div></div><div class="col-12"><div class="card"><div class="card-header"><strong>Remediation task outlook</strong></div><div class="table-responsive"><table class="table"><thead><tr><th>Task</th><th>Priority</th><th>Status</th><th>Due date</th></tr></thead><tbody><?php if (!$tasks): ?><tr><td colspan="4" class="text-muted text-center py-4">No remediation tasks yet.</td></tr><?php endif; ?><?php foreach ($tasks as $row): ?><tr><td><?= e($row['title']) ?></td><td><span class="badge bg-secondary"><?= e($row['priority']) ?></span></td><td><?= e($row['status']) ?></td><td><?= e($row['due_date'] ?: '—') ?></td></tr><?php endforeach; ?></tbody></table></div></div></div></div>
    <?php endif; ?>
</div>