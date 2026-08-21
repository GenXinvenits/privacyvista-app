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

    <nav class="nav flex-column py-2" aria-label="Primary navigation">
        <div class="sidebar-section">Workspace</div>
        <a class="nav-link" href="/app/public/index.php?route=dashboard" data-mobile-label="Dashboard" aria-label="Dashboard">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <span>Dashboard</span>
        </a>

        <?php if ($isSuperuser): ?>
            <a class="nav-link" href="/app/public/index.php?route=clients" data-mobile-label="Clients" aria-label="Clients"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg><span>Clients</span></a>
        <?php elseif ($isAdmin && $hasClientContext): ?>
            <a class="nav-link" href="/app/public/index.php?route=clients<?= $clientQuery ?>" data-mobile-label="Clients" aria-label="Clients"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg><span>Clients</span></a>
        <?php endif; ?>

        <?php if (($isAdmin || $isSuperuser) && $hasClientContext): ?>
            <a class="nav-link" href="/app/public/index.php?route=processing-activities<?= $clientQuery ?>" data-mobile-label="Activities" aria-label="Activities"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 6h8M8 10h8M8 14h5"/></svg><span>Activities</span></a>
            <a class="nav-link" href="/app/public/index.php?route=assessments<?= $clientQuery ?>" data-mobile-label="Assessments" aria-label="Assessments"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg><span>Assessments</span></a>
            <a class="nav-link" href="/app/public/index.php?route=findings<?= $clientQuery ?>" data-mobile-label="Findings" aria-label="Findings"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M10.3 2.9 1.8 17.6A2 2 0 0 0 3.5 20.5h17a2 2 0 0 0 1.7-2.9L13.7 2.9a2 2 0 0 0-3.4 0Z"/><path d="M12 8v5M12 17h.01"/></svg><span>Findings</span></a>
            <a class="nav-link" href="/app/public/index.php?route=tasks<?= $clientQuery ?>" data-mobile-label="Tasks" aria-label="Remediation Tasks"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg><span>Remediation Tasks</span></a>
        <?php endif; ?>

        <?php if ($isSuperuser && !$hasClientContext): ?>
            <a class="nav-link" href="/app/public/index.php?route=processing-activities" data-mobile-label="Activities" aria-label="Activities"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg><span>Activities</span></a>
            <a class="nav-link" href="/app/public/index.php?route=assessments" data-mobile-label="Assessments" aria-label="Assessments"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg><span>Assessments</span></a>
            <a class="nav-link" href="/app/public/index.php?route=findings" data-mobile-label="Findings" aria-label="Findings"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M10.3 2.9 1.8 17.6A2 2 0 0 0 3.5 20.5h17a2 2 0 0 0 1.7-2.9L13.7 2.9a2 2 0 0 0-3.4 0Z"/><path d="M12 8v5M12 17h.01"/></svg><span>Findings</span></a>
            <a class="nav-link" href="/app/public/index.php?route=tasks" data-mobile-label="Tasks" aria-label="Remediation Tasks"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg><span>Remediation Tasks</span></a>
        <?php endif; ?>

        <?php if ($isSuperuser || $isAdmin): ?>
            <div class="sidebar-section">Administration</div>
            <a class="nav-link" href="/app/public/index.php?route=users<?= $isAdmin ? $clientQuery : '' ?>" data-mobile-label="Users" aria-label="Users"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M16 11a4 4 0 1 0 0-8"/></svg><span>Users</span></a>
            <a class="nav-link" href="/app/public/index.php?route=departments<?= $isAdmin ? $clientQuery : '' ?>" data-mobile-label="Departments" aria-label="Departments"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 21V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v16"/><path d="M3 21h18M7 7h2M15 7h2M7 11h2M15 11h2M7 15h2M15 15h2M10 21v-3h4v3"/></svg><span>Departments</span></a>
        <?php endif; ?>

        <button type="button" class="sidebar-section more-desktop-trigger" aria-expanded="false" aria-controls="privacyvista-more-group"><span>More</span><span class="more-state-indicator" data-more-indicator aria-hidden="true">⌄</span></button>
        <div id="privacyvista-more-group" class="desktop-more-group" hidden>
            <a class="nav-link" href="/app/public/index.php?route=forms" data-mobile-label="Forms" aria-label="Forms"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 2h9l5 5v15H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/><path d="M14 2v6h6M8 13h8M8 17h6"/></svg><span>Forms</span></a>
            <?php if ($isSuperuser || $isAdmin): ?>
                <a class="nav-link" href="/app/public/index.php?route=reports<?= $isAdmin ? $clientQuery : '' ?>" data-mobile-label="Reports" aria-label="Reports"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 19V5M4 19h16"/><path d="m7 16 4-5 3 3 5-7"/></svg><span>Reports</span></a>
            <?php endif; ?>
            <a class="nav-link" href="/app/public/index.php?route=settings" data-mobile-label="Settings" aria-label="Settings"><svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="m19.4 15 .1.1a2 2 0 1 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.3a2 2 0 1 1-4 0v-.3a2 2 0 0 0-3.4-1.4l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1A2 2 0 0 0 3.7 12a2 2 0 0 0-.3-1.1l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A2 2 0 0 0 9.6 6.7h.3a2 2 0 0 0 1.1-.3 2 2 0 0 0 0-3.5 2 2 0 1 1 4 0v.3a2 2 0 0 0 1.4 1.8 2 2 0 0 0 1.9-.4l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1A2 2 0 0 0 20.3 12a2 2 0 0 0-.9 1.7 2 2 0 0 0 0 1.3Z"/></svg><span>Settings</span></a>
        </div>
    </nav>
</aside>
