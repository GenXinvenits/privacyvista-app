<?php
$title = 'PrivacyVista Dashboard';
$role = strtolower((string)($_SESSION['user']['role'] ?? ''));
$isSuperuser = $role === 'superuser';
$isAdmin = $role === 'admin';
$isClient = $role === 'client';
$clientParams = $defaultClientId ? ['client_id' => (int)$defaultClientId] : [];

$statsCards = [];
if ($isSuperuser) {
    $statsCards[] = ['Users','users','users','users','M4 20v-1a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v1M10 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6'];
    $statsCards[] = ['Clients','clients','clients','clients','M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0-8 0 4 4 0 0 0 0 8'];
}
$statsCards[] = ['Processing Activities','activities','processing-activities','activities','M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01'];
$statsCards[] = ['Assessments','assessments','assessments','assessments','M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11'];
$statsCards[] = ['Findings','open_findings','findings','findings','M12 9v4M12 17h.01M10.3 3.86l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.74-3l-8-14a2 2 0 0 0-3.48 0z'];
$statsCards[] = ['Remediation Tasks','open_tasks','tasks','tasks','M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11'];
?>

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="page-title">Privacy overview</div>
        <div class="page-subtitle mb-0">Monitor your privacy programme, assessments and remediation work.</div>
    </div>
    <?php if ($isSuperuser): ?>
        <a class="btn btn-primary" href="<?= url('clients/create') ?>">+ Add Client</a>
    <?php endif; ?>
</div>

<div class="row g-3 mb-4">
<?php foreach($statsCards as $s): ?>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card dashboard-stat h-100">
            <div class="card-body">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 28 28" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="<?= e($s[4]) ?>"/></svg>
                </div>
                <div class="stat-label"><?= e($s[0]) ?></div>
                <div class="stat-value"><?= e((string)($stats[$s[1]] ?? 0)) ?></div>
                <a class="stretched-link" href="<?= url($s[2], $clientParams) ?>" aria-label="Open <?= e($s[0]) ?>"></a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php if ($isSuperuser || $isAdmin): ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><strong>Recent clients</strong><div class="small text-muted">Your latest organisations</div></div>
                <a class="btn btn-sm btn-light border" href="<?= url('clients') ?>">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Organisation</th><th>Contact</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach($recentClients as $c): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($c['company_name']) ?></td>
                            <td><?= e($c['contact_person']) ?></td>
                            <td><span class="badge bg-<?= $c['status'] ? 'success' : 'secondary' ?>"><?= $c['status'] ? 'Active' : 'Inactive' ?></span></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= url('clients/show',['id'=>$c['id']]) ?>">Open</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$recentClients): ?><tr><td colspan="4" class="text-center text-muted py-5">No clients yet.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><strong>Attention needed</strong><div class="small text-muted">Items requiring action</div></div>
            <div class="card-body">
                <a class="d-flex justify-content-between align-items-center py-3 border-bottom text-dark" href="<?= url('findings',$clientParams) ?>"><span>Findings</span><strong><?= e((string)$stats['open_findings']) ?></strong></a>
                <a class="d-flex justify-content-between align-items-center py-3 text-dark" href="<?= url('tasks',$clientParams) ?>"><span>Remediation Tasks</span><strong><?= e((string)$stats['open_tasks']) ?></strong></a>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><strong>Attention needed</strong><div class="small text-muted">Items requiring action</div></div>
            <div class="card-body">
                <a class="d-flex justify-content-between align-items-center py-3 border-bottom text-dark" href="<?= url('findings',$clientParams) ?>"><span>Findings</span><strong><?= e((string)$stats['open_findings']) ?></strong></a>
                <a class="d-flex justify-content-between align-items-center py-3 text-dark" href="<?= url('tasks',$clientParams) ?>"><span>Remediation Tasks</span><strong><?= e((string)$stats['open_tasks']) ?></strong></a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>