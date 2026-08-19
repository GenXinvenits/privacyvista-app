<div class="page-header mb-4">
    <div>
        <div class="page-title">Form Templates</div>
        <div class="page-subtitle mb-0">Superuser-controlled form definitions. Published versions are immutable.</div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Form</th><th>Description</th><th>Current version</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                <?php foreach (($templates ?? []) as $template): ?>
                    <tr>
                        <td><div class="fw-semibold"><?= e($template['name']) ?></div><div class="small text-secondary"><?= e($template['slug']) ?></div></td>
                        <td><?= e($template['description'] ?? '') ?></td>
                        <td>v<?= (int)($template['active_version_number'] ?? 0) ?></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary" href="<?= url('form-templates/edit?slug='.rawurlencode($template['slug'])) ?>">Edit form</a>
                            <a class="btn btn-sm btn-outline-secondary" href="<?= url('form-templates/versions?slug='.rawurlencode($template['slug'])) ?>">Version history</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
