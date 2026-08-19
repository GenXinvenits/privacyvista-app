<div class="page-header mb-4 d-flex justify-content-between align-items-start">
    <div><div class="page-title"><?= e($template['name']) ?> — Version History</div><div class="page-subtitle mb-0">Published definitions are immutable and remain available to historical records.</div></div>
    <a class="btn btn-primary" href="<?= url('form-templates/edit?slug='.rawurlencode($template['slug'])) ?>">Create new version</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Version</th><th>Status</th><th>Change summary</th><th>Created</th></tr></thead><tbody>
<?php foreach (($versions ?? []) as $version): ?>
<tr><td class="fw-semibold">v<?= (int)$version['version_number'] ?></td><td><?= e(ucfirst($version['status'])) ?></td><td><?= e($version['change_summary'] ?? '') ?></td><td><?= e($version['created_at'] ?? '') ?></td></tr>
<?php endforeach; ?>
</tbody></table></div></div></div>
