<?php
$role = strtolower((string)($_SESSION['user']['role'] ?? ''));
$clientId = (int)($_SESSION['user']['client_id'] ?? 0);
$isSuperuser = $role === 'superuser';
$isAdmin = $role === 'admin';
$isClient = $role === 'client';
$hasClientContext = $clientId > 0;
$clientQuery = $hasClientContext ? '&client_id=' . rawurlencode((string)$clientId) : '';
?>
<aside class="sidebar d-flex flex-column">
    <div class="sidebar-brand justify-content-center">
        <a href="/app/public/index.php?route=dashboard" aria-label="PrivacyVista Dashboard">
            <img src="https://privacyvista.com/wp-content/uploads/2025/12/privacy-vista-logo-light.png" alt="PrivacyVista">
        </a>
    </div>

    <nav class="nav flex-column py-2">
        <div class="sidebar-section">Workspace</div>
        <a class="nav-link" href="/app/public/index.php?route=dashboard"><span>Dashboard</span></a>

        <?php if ($isSuperuser): ?>
            <a class="nav-link" href="/app/public/index.php?route=clients"><span>Clients</span></a>
        <?php elseif ($isAdmin && $hasClientContext): ?>
            <a class="nav-link" href="/app/public/index.php?route=clients<?= $clientQuery ?>"><span>Clients</span></a>
        <?php endif; ?>

        <?php if ($isAdmin && $hasClientContext): ?>
            <a class="nav-link" href="/app/public/index.php?route=processing-activities<?= $clientQuery ?>"><span>Processing Activities</span></a>
            <a class="nav-link" href="/app/public/index.php?route=assessments<?= $clientQuery ?>"><span>Assessments</span></a>
            <a class="nav-link" href="/app/public/index.php?route=findings<?= $clientQuery ?>"><span>Findings</span></a>
            <a class="nav-link" href="/app/public/index.php?route=tasks<?= $clientQuery ?>"><span>Remediation Tasks</span></a>
        <?php endif; ?>

        <?php if ($isSuperuser): ?>
            <a class="nav-link" href="/app/public/index.php?route=clients"><span>Processing Activities</span></a>
            <a class="nav-link" href="/app/public/index.php?route=clients"><span>Assessments</span></a>
            <a class="nav-link" href="/app/public/index.php?route=clients"><span>Findings</span></a>
            <a class="nav-link" href="/app/public/index.php?route=clients"><span>Remediation Tasks</span></a>
        <?php endif; ?>

        <?php if ($isSuperuser || $isAdmin): ?>
            <div class="sidebar-section">Administration</div>
            <a class="nav-link" href="/app/public/index.php?route=users<?= $isAdmin ? $clientQuery : '' ?>"><span>Users</span></a>
            <a class="nav-link" href="/app/public/index.php?route=departments<?= $isAdmin ? $clientQuery : '' ?>"><span>Departments</span></a>
        <?php endif; ?>

        <div class="sidebar-section">More</div>
        <a class="nav-link" href="/app/public/index.php?route=forms"><span>Forms</span></a>

        <?php if ($isSuperuser || $isAdmin): ?>
            <a class="nav-link" href="/app/public/index.php?route=reports<?= $isAdmin ? $clientQuery : '' ?>"><span>Reports</span></a>
        <?php endif; ?>

        <a class="nav-link" href="/app/public/index.php?route=settings"><span>Settings</span></a>
    </nav>
</aside>
