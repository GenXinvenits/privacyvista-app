<main class="app-main">
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-0">
        <div>
            <div class="navbar-title"><?= e($title ?? 'Dashboard') ?></div>
            <div class="navbar-subtitle">Privacy management workspace</div>
        </div>
        <?php if(isset($_SESSION['user'])): ?>
            <div class="user-chip">
                <span class="user-avatar"><?= e(strtoupper(substr($_SESSION['user']['fullname'] ?? 'U', 0, 1))) ?></span>
                <div class="user-details">
                    <div><?= e($_SESSION['user']['fullname']) ?></div>
                    <small>Signed in</small>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="page-content">