<main class="app-main">

    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid px-0">

            <div class="navbar-context">
                <a class="navbar-brand-logo" href="/app/public/index.php?route=dashboard" aria-label="PrivacyVista Dashboard">
                    <img src="https://privacyvista.com/wp-content/uploads/2025/12/privacy-vista-logo-light.png" alt="PrivacyVista">
                </a>
                <div class="navbar-page-context">
                    <div class="navbar-title">
                        <?= e($title ?? 'Dashboard') ?>
                    </div>
                    <div class="navbar-subtitle">
                        Privacy management workspace
                    </div>
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
                        <svg class="user-chevron" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="user-dropdown" id="mobile-user-dropdown">
                        <div class="user-dropdown-header"><span class="user-dropdown-avatar"><?= e($initial) ?></span><div><strong><?= e($fullname) ?></strong><small><?= e($roleLabel) ?></small></div></div>
                        <div class="user-dropdown-divider"></div>
                        <a href="/app/public/index.php?route=settings" class="user-dropdown-item"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg><span>Edit profile</span></a>
                        <a href="/app/public/index.php?route=logout" class="user-dropdown-item logout-item"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 0-2 2V5a2 2 0 0 0 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg><span>Logout</span></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <div class="page-content">
<script>
(function(){'use strict';const menu=document.querySelector('.user-profile-menu'),trigger=menu?.querySelector('.user-profile-trigger'),dropdown=menu?.querySelector('.user-dropdown');if(!menu||!trigger||!dropdown)return;const mobileQuery=window.matchMedia('(max-width: 767.98px)');function closeMenu(){menu.classList.remove('is-open');trigger.setAttribute('aria-expanded','false')}function toggleMenu(event){event.preventDefault();event.stopPropagation();const open=!menu.classList.contains('is-open');menu.classList.toggle('is-open',open);trigger.setAttribute('aria-expanded',open?'true':'false');if(open&&mobileQuery.matches)requestAnimationFrame(()=>{dropdown.scrollTop=0})}trigger.addEventListener('click',toggleMenu);document.addEventListener('click',event=>{if(!menu.contains(event.target))closeMenu()});document.addEventListener('touchstart',event=>{if(!menu.contains(event.target))closeMenu()},{passive:true});document.addEventListener('keydown',event=>{if(event.key==='Escape'){closeMenu();trigger.focus()}});dropdown.addEventListener('click',event=>event.stopPropagation());window.addEventListener('resize',closeMenu,{passive:true})})();
</script>