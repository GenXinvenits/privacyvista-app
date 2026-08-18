<?php $role = strtolower((string)($_SESSION['user']['role'] ?? '')); ?>
<aside class="sidebar d-flex flex-column">
    <div class="sidebar-brand justify-content-center">
        <a href="/app/public/index.php?route=dashboard" aria-label="PrivacyVista Dashboard">
            <img src="https://privacyvista.com/wp-content/uploads/2025/12/privacy-vista-logo-light.png" alt="PrivacyVista">
        </a>
    </div>

    <nav class="nav flex-column py-2">
        <div class="sidebar-section">Workspace</div>
        <a class="nav-link" href="/app/public/index.php?route=dashboard"><span>Dashboard</span></a>

        <?php if ($role === 'superuser' || $role === 'admin'): ?>
            <a class="nav-link" href="/app/public/index.php?route=clients<?= $role === 'admin' && !empty($_SESSION['user']['client_id']) ? '&client_id='.(int)$_SESSION['user']['client_id'] : '' ?>"><span>Clients</span></a>
            <a class="nav-link" href="/app/public/index.php?route=processing-activities<?= !empty($_SESSION['user']['client_id']) ? '&client_id='.(int)$_SESSION['user']['client_id'] : '' ?>"><span>Processing Activities</span></a>
            <a class="nav-link" href="/app/public/index.php?route=assessments<?= !empty($_SESSION['user']['client_id']) ? '&client_id='.(int)$_SESSION['user']['client_id'] : '' ?>"><span>Assessments</span></a>
            <a class="nav-link" href="/app/public/index.php?route=findings<?= !empty($_SESSION['user']['client_id']) ? '&client_id='.(int)$_SESSION['user']['client_id'] : '' ?>"><span>Findings</span></a>
            <a class="nav-link" href="/app/public/index.php?route=tasks<?= !empty($_SESSION['user']['client_id']) ? '&client_id='.(int)$_SESSION['user']['client_id'] : '' ?>"><span>Remediation Tasks</span></a>
        <?php endif; ?>

        <?php if ($role === 'superuser' || $role === 'admin'): ?>
            <div class="sidebar-section">Administration</div>
            <a class="nav-link" href="/app/public/index.php?route=users"><span>Users</span></a>
            <a class="nav-link" href="/app/public/index.php?route=departments<?= !empty($_SESSION['user']['client_id']) ? '&client_id='.(int)$_SESSION['user']['client_id'] : '' ?>"><span>Departments</span></a>
        <?php endif; ?>

        <div class="sidebar-section">More</div>
        <a class="nav-link" href="/app/public/index.php?route=forms"><span>Forms</span></a>
        <?php if ($role === 'superuser' || $role === 'admin'): ?>
            <a class="nav-link" href="/app/public/index.php?route=reports<?= !empty($_SESSION['user']['client_id']) ? '&client_id='.(int)$_SESSION['user']['client_id'] : '' ?>"><span>Reports</span></a>
        <?php endif; ?>
        <a class="nav-link" href="/app/public/index.php?route=settings"><span>Settings</span></a>
    </nav>
</aside>
