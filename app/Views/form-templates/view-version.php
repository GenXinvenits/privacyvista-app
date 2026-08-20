<?php
$version = $version ?? [];
$definition = $version['definition'] ?? ['sections' => []];
?>
<div class="page-header mb-4 d-flex justify-content-between align-items-start">
    <div>
        <div class="page-title"><?= e($version['name'] ?? 'Form') ?> — Version <?= (int)($version['version_number'] ?? 0) ?></div>
        <div class="page-subtitle mb-0">Read-only historical definition. This version cannot be modified.</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="<?= url('form-templates/versions?slug='.rawurlencode($version['slug'] ?? '')) ?>">Back to versions</a>
        <a class="btn btn-primary" href="<?= url('form-templates/edit?slug='.rawurlencode($version['slug'] ?? '').'&version='.(int)($version['id'] ?? 0)) ?>">Use as base</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3"><div class="text-secondary small">Version</div><div class="fw-semibold">v<?= (int)($version['version_number'] ?? 0) ?></div></div>
            <div class="col-md-3"><div class="text-secondary small">Status</div><div class="fw-semibold"><?= e(ucfirst((string)($version['status'] ?? ''))) ?></div></div>
            <div class="col-md-3"><div class="text-secondary small">Created</div><div><?= e((string)($version['created_at'] ?? '')) ?></div></div>
            <div class="col-md-3"><div class="text-secondary small">Change summary</div><div><?= e((string)($version['change_summary'] ?? '')) ?></div></div>
        </div>
    </div>
</div>

<?php foreach (($definition['sections'] ?? []) as $section): ?>
<div class="card mb-4">
    <div class="card-header">
        <div class="fw-semibold"><?= e($section['title'] ?? 'Section') ?></div>
        <?php if (!empty($section['description'])): ?><div class="text-secondary small mt-1"><?= e($section['description']) ?></div><?php endif; ?>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach (($section['fields'] ?? []) as $field): ?>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold"><?= e($field['label'] ?? $field['key'] ?? 'Field') ?></div>
                    <div class="text-secondary small mt-1">
                        Key: <?= e($field['key'] ?? '') ?> · Type: <?= e($field['type'] ?? 'text') ?>
                        <?php if (!empty($field['required'])): ?> · Required<?php endif; ?>
                    </div>
                    <?php if (!empty($field['roles'])): ?><div class="text-secondary small">Roles: <?= e(implode(', ', (array)$field['roles'])) ?></div><?php endif; ?>
                    <?php if (!empty($field['options'])): ?><div class="text-secondary small">Options: <?= e(implode(', ', (array)$field['options'])) ?></div><?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
