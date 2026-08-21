<?php
$role = strtolower((string)($_SESSION['user']['role'] ?? ''));
$clientId = (int)($_SESSION['user']['client_id'] ?? 0);
$isSuperuser = $role === 'superuser';
$isAdmin = $role === 'admin';
$hasClientContext = $clientId > 0;
$clientQuery = $hasClientContext ? '&client_id=' . rawurlencode((string)$clientId) : '';
?>
<aside class="sidebar d-flex flex-column" data-sidebar>
    <nav class="nav flex-column py-2" aria-label="Primary navigation">
        <div class="sidebar-section sidebar-workspace-section">Workspace</div>
        <a class="nav-link sidebar-workspace-dashboard" href="/app/public/index.php?route=dashboard" data-mobile-label="Dashboard" aria-label="Dashboard"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a>
        <?php if (($isAdmin || $isSuperuser) && $hasClientContext): ?>
            <a class="nav-link sidebar-workspace-activities" href="/app/public/index.php?route=processing-activities<?= $clientQuery ?>" data-mobile-label="Activities" aria-label="Activities"><i class="fa-solid fa-list-check" aria-hidden="true"></i><span>Activities</span></a>
            <a class="nav-link sidebar-workspace-assessments" href="/app/public/index.php?route=assessments<?= $clientQuery ?>" data-mobile-label="Assessments" aria-label="Assessments"><i class="fa-solid fa-clipboard-check" aria-hidden="true"></i><span>Assessments</span></a>
            <a class="nav-link sidebar-workspace-findings" href="/app/public/index.php?route=findings<?= $clientQuery ?>" data-mobile-label="Findings" aria-label="Findings"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i><span>Findings</span></a>
            <a class="nav-link sidebar-workspace-tasks" href="/app/public/index.php?route=tasks<?= $clientQuery ?>" data-mobile-label="Tasks" aria-label="Remediation Tasks"><i class="fa-solid fa-list-check" aria-hidden="true"></i><span>Remediation Tasks</span></a>
        <?php elseif ($isSuperuser && !$hasClientContext): ?>
            <a class="nav-link sidebar-workspace-activities" href="/app/public/index.php?route=processing-activities" data-mobile-label="Activities" aria-label="Activities"><i class="fa-solid fa-list-check" aria-hidden="true"></i><span>Activities</span></a>
            <a class="nav-link sidebar-workspace-assessments" href="/app/public/index.php?route=assessments" data-mobile-label="Assessments" aria-label="Assessments"><i class="fa-solid fa-clipboard-check" aria-hidden="true"></i><span>Assessments</span></a>
            <a class="nav-link sidebar-workspace-findings" href="/app/public/index.php?route=findings" data-mobile-label="Findings" aria-label="Findings"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i><span>Findings</span></a>
            <a class="nav-link sidebar-workspace-tasks" href="/app/public/index.php?route=tasks" data-mobile-label="Tasks" aria-label="Remediation Tasks"><i class="fa-solid fa-list-check" aria-hidden="true"></i><span>Remediation Tasks</span></a>
        <?php endif; ?>
        <?php if ($isSuperuser || $isAdmin): ?>
            <a class="nav-link sidebar-workspace-report" href="/app/public/index.php?route=reports<?= $isAdmin ? $clientQuery : '' ?>" data-mobile-label="Reports" aria-label="Reports"><i class="fa-solid fa-chart-column" aria-hidden="true"></i><span>Reports</span></a>

            <div class="sidebar-section sidebar-administration-section">Administration</div>
            <a class="nav-link sidebar-administration-users" href="/app/public/index.php?route=users<?= $isAdmin ? $clientQuery : '' ?>" data-mobile-label="Users" aria-label="Users"><i class="fa-solid fa-user-group" aria-hidden="true"></i><span>Users</span></a>
            <?php if ($isSuperuser): ?>
                <a class="nav-link sidebar-administration-clients" href="/app/public/index.php?route=clients" data-mobile-label="Clients" aria-label="Clients"><i class="fa-solid fa-users" aria-hidden="true"></i><span>Clients</span></a>
            <?php elseif ($isAdmin && $hasClientContext): ?>
                <a class="nav-link sidebar-administration-clients" href="/app/public/index.php?route=clients<?= $clientQuery ?>" data-mobile-label="Clients" aria-label="Clients"><i class="fa-solid fa-users" aria-hidden="true"></i><span>Clients</span></a>
            <?php endif; ?>
            <a class="nav-link sidebar-administration-departments" href="/app/public/index.php?route=departments<?= $isAdmin ? $clientQuery : '' ?>" data-mobile-label="Departments" aria-label="Departments"><i class="fa-solid fa-building" aria-hidden="true"></i><span>Departments</span></a>
        <?php endif; ?>

        <div class="sidebar-section sidebar-more-section">More</div>
        <a class="nav-link sidebar-more-forms" href="/app/public/index.php?route=forms" data-mobile-label="Forms" aria-label="Forms"><i class="fa-solid fa-file-lines" aria-hidden="true"></i><span>Forms</span></a>
        <?php if ($isSuperuser || $isAdmin): ?>
            <a class="nav-link sidebar-more-report" href="/app/public/index.php?route=reports<?= $isAdmin ? $clientQuery : '' ?>" data-mobile-label="Reports" aria-label="Reports"><i class="fa-solid fa-chart-column" aria-hidden="true"></i><span>Reports</span></a>
        <?php endif; ?>
        <a class="nav-link sidebar-more-settings" href="/app/public/index.php?route=settings" data-mobile-label="Settings" aria-label="Settings"><i class="fa-solid fa-gear" aria-hidden="true"></i><span>Settings</span></a>
    </nav>

    <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-label="Collapse sidebar" aria-expanded="true" title="Collapse sidebar">
        <i class="fa-solid fa-angles-left" data-sidebar-toggle-icon aria-hidden="true"></i>
    </button>
</aside>
