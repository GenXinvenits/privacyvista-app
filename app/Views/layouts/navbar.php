<main class="app-main">

    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid px-0">

            <div>
                <div class="navbar-title">
                    <?= e($title ?? 'Dashboard') ?>
                </div>
                <div class="navbar-subtitle">
                    Privacy management workspace
                </div>
            </div>

            <?php if (isset($_SESSION['user'])): ?>

                <?php
                $fullname = $_SESSION['user']['fullname'] ?? 'User';
                $role = $_SESSION['user']['role'] ?? 'User';
                $roleLabel = ucwords(str_replace(['_', '-'], ' ', trim($role)));
                $initial = strtoupper(substr(trim($fullname), 0, 1));
                ?>

                <div class="user-profile-menu">
                    <button type="button" class="user-profile-trigger" aria-label="User menu" aria-expanded="false" aria-controls="mobile-user-dropdown">
                        <span class="user-avatar"><?= e($initial) ?></span>
                        <span class="user-details">
                            <span class="user-name"><?= e($fullname) ?></span>
                            <span class="user-status badge bg-success"><?= e($roleLabel) ?></span>
                        </span>
                        <svg class="user-chevron" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>

                    <div class="user-dropdown" id="mobile-user-dropdown">
                        <div class="user-dropdown-header">
                            <span class="user-dropdown-avatar"><?= e($initial) ?></span>
                            <div>
                                <strong><?= e($fullname) ?></strong>
                                <small><?= e($roleLabel) ?></small>
                            </div>
                        </div>

                        <div class="user-dropdown-divider"></div>

                        <a href="/app/public/index.php?route=settings" class="user-dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.8 1.8-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.03 1.55V20h-2.55v-.11a1.7 1.7 0 0 0-1.03-1.55 1.7 1.7 0 0 0-1.88.34l-.06.06-1.8-1.8.06-.06A1.7 1.7 0 0 0 8.1 15a1.7 1.7 0 0 0-1.55-1.03H6v-2.55h.11A1.7 1.7 0 0 0 7.66 10.4a1.7 1.7 0 0 0-.34-1.88l-.06-.06 1.8-1.8.06.06A1.7 1.7 0 0 0 11 6.38a1.7 1.7 0 0 0 1.03-1.55V4h2.55v.11a1.7 1.7 0 0 0 1.03 1.55 1.7 1.7 0 0 0 1.88.34l.06-.06 1.8 1.8-.06.06A1.7 1.7 0 0 0 19.4 9c.2.6.77 1.03 1.4 1.03H21v2.55h-.11A1.7 1.7 0 0 0 19.4 15Z"/>
                            </svg>
                            <span>Settings</span>
                        </a>

                        <a href="/app/public/index.php?route=settings" class="user-dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 21a8 8 0 0 0-16 0"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Edit profile</span>
                        </a>

                        <a href="/app/public/index.php?route=logout" class="user-dropdown-item logout-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </nav>

    <div class="page-content">

<script>
(function () {
    'use strict';

    const menu = document.querySelector('.user-profile-menu');
    const trigger = menu?.querySelector('.user-profile-trigger');
    const dropdown = menu?.querySelector('.user-dropdown');

    if (!menu || !trigger || !dropdown) return;

    const mobileQuery = window.matchMedia('(max-width: 767.98px)');

    function closeMenu() {
        menu.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
    }

    function toggleMenu(event) {
        event.preventDefault();
        event.stopPropagation();
        const open = !menu.classList.contains('is-open');
        menu.classList.toggle('is-open', open);
        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open && mobileQuery.matches) {
            requestAnimationFrame(() => { dropdown.scrollTop = 0; });
        }
    }

    trigger.addEventListener('click', toggleMenu);

    document.addEventListener('click', function (event) {
        if (!menu.contains(event.target)) closeMenu();
    });

    document.addEventListener('touchstart', function (event) {
        if (!menu.contains(event.target)) closeMenu();
    }, { passive: true });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMenu();
            trigger.focus();
        }
    });

    dropdown.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    window.addEventListener('resize', closeMenu, { passive: true });
})();
</script>