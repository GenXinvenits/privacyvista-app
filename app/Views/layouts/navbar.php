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
                $initial = strtoupper(substr(trim($fullname), 0, 1));
                ?>

                <div class="user-profile-menu">
                    <button type="button" class="user-profile-trigger" aria-label="User menu">
                        <span class="user-avatar"><?= e($initial) ?></span>
                        <span class="user-details">
                            <span class="user-name"><?= e($fullname) ?></span>
                            <span class="user-status">Signed in</span>
                        </span>
                        <svg class="user-chevron" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>

                    <div class="user-dropdown">
                        <div class="user-dropdown-header">
                            <span class="user-dropdown-avatar"><?= e($initial) ?></span>
                            <div>
                                <strong><?= e($fullname) ?></strong>
                                <small>Signed in</small>
                            </div>
                        </div>

                        <div class="user-dropdown-divider"></div>

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