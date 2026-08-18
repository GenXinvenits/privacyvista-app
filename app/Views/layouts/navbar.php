<main class="flex-grow-1">
<nav class="navbar px-4">
    <div class="container-fluid px-0">
        <div>
            <div class="navbar-brand mb-0"><?= e($title ?? 'PrivacyVista') ?></div>
            <div class="small text-muted d-none d-md-block">Privacy management workspace</div>
        </div>
        <?php if(isset($_SESSION['user'])): ?>
            <div class="d-flex align-items-center gap-2">
                <div class="text-end d-none d-sm-block">
                    <div class="small fw-semibold"><?= e($_SESSION['user']['fullname']) ?></div>
                    <div class="small text-muted">Signed in</div>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;background:#eff6ff;color:#2563eb">
                    <?= e(strtoupper(substr($_SESSION['user']['fullname'] ?? 'U',0,1))) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="page-content">