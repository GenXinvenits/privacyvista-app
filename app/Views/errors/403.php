<div class="d-flex justify-content-center align-items-center" style="min-height:60vh;">
    <div class="card text-center" style="max-width:520px;width:100%;">
        <div class="card-body p-5">
            <div class="mb-3" style="font-size:2.5rem;">🔒</div>
            <h1 class="h4 mb-2"><?= e($title ?? 'Access denied') ?></h1>
            <p class="text-muted mb-4"><?= e($message ?? 'You do not have permission to access this section.') ?></p>
            <a href="<?= url('dashboard') ?>" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>
</div>
