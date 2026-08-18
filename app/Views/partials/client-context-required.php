<?php
$title = $title ?? 'Client required';
$message = $message ?? 'Please select a client to continue.';
$backRoute = $backRoute ?? 'clients';
$backLabel = $backLabel ?? 'Select Client';
?>

<div class="client-context-page">
    <div class="client-context-card">
        <div class="client-context-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M19 8v6M16 11h6"/>
            </svg>
        </div>

        <div class="client-context-eyebrow">Client context required</div>
        <h1><?= e($title) ?></h1>
        <p><?= e($message) ?></p>

        <div class="client-context-actions">
            <a href="<?= url($backRoute) ?>" class="btn btn-primary">
                <?= e($backLabel) ?>
            </a>
            <a href="<?= url('dashboard') ?>" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
