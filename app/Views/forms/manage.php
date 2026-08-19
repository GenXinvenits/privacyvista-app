<?php
$title = 'Manage Form Templates';
$templates = $templates ?? [];
?>
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Manage Form Templates</div><div class="page-subtitle mb-0">Superuser form definitions and version history.</div></div>
</div>
<div class="row g-4">
<?php foreach ($templates as $template): ?>
<div class="col-12 col-lg-6">
<div class="card h-100"><div class="card-body">
<div class="d-flex justify-content-between align-items-start gap-3"><div><div class="text-muted small">FORM TEMPLATE</div><h3 class="h5 mb-1"><?= e($template['name']) ?></h3><p class="text-muted mb-0"><?= e($template['description'] ?? '') ?></p></div><span class="badge bg-success">v<?= (int)$template['version_number'] ?></span></div>
<div class="mt-4 d-flex gap-2"><a class="btn btn-primary" href="<?= url('forms/templates/'.$template['slug'].'/edit') ?>">Edit / Create New Version</a><a class="btn btn-outline-secondary" href="<?= url('forms/templates/'.$template['slug'].'/versions') ?>">Version history</a></div>
</div></div>
</div>
<?php endforeach; ?>
</div>
