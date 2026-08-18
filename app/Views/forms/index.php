<?php
$title = $title ?? 'Forms';
$forms = $forms ?? [];
?>

<!-- Forms module checkpoint: template catalogue is stable before interactive workflows. -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="page-title">Forms</div>
        <div class="page-subtitle mb-0">Privacy management templates and data-collection forms.</div>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($forms as $form): ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card form-template-card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="form-template-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M7 3h7l4 4v14H7z"/>
                            <path d="M14 3v5h5M10 13h5M10 17h5"/>
                        </svg>
                    </div>

                    <div class="form-template-category"><?= e($form['category']) ?></div>
                    <h3 class="form-template-title"><?= e($form['name']) ?></h3>
                    <p class="form-template-description"><?= e($form['description']) ?></p>

                    <div class="mt-auto pt-3 d-flex align-items-center justify-content-between gap-3">
                        <span class="badge bg-success"><?= e($form['status']) ?></span>
                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                            Open form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
