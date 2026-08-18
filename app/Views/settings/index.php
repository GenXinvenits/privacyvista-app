<?php use App\Core\Security; ?>
<div class="settings-page">
    <div class="page-title">Settings</div>
    <div class="page-subtitle">Manage your PrivacyVista account and security preferences.</div>

    <div class="settings-grid">
        <section class="card settings-card">
            <div class="card-header">
                <h5 class="mb-1">Profile</h5>
                <p class="settings-help mb-0">Update the information used for your account.</p>
            </div>
            <div class="card-body">
                <form method="post" action="/app/public/index.php?route=settings/profile">
                    <input type="hidden" name="_csrf" value="<?= Security::e(Security::csrfToken()) ?>">
                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input class="form-control" name="fullname" value="<?= Security::e($account['fullname'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input class="form-control" type="email" name="email" value="<?= Security::e($account['email'] ?? '') ?>" required>
                    </div>
                    <div class="settings-meta">
                        <span>Account ID</span><strong>#<?= (int)($account['id'] ?? 0) ?></strong>
                    </div>
                    <button class="btn btn-primary mt-4" type="submit">Save profile</button>
                </form>
            </div>
        </section>

        <section class="card settings-card">
            <div class="card-header">
                <h5 class="mb-1">Password & security</h5>
                <p class="settings-help mb-0">Change your password without leaving the application.</p>
            </div>
            <div class="card-body">
                <form method="post" action="/app/public/index.php?route=settings/password">
                    <input type="hidden" name="_csrf" value="<?= Security::e(Security::csrfToken()) ?>">
                    <div class="mb-3">
                        <label class="form-label">Current password</label>
                        <input class="form-control" type="password" name="current_password" autocomplete="current-password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New password</label>
                        <input class="form-control" type="password" name="new_password" minlength="8" autocomplete="new-password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm new password</label>
                        <input class="form-control" type="password" name="confirm_password" minlength="8" autocomplete="new-password" required>
                    </div>
                    <div class="settings-note">Use at least 8 characters for your new password.</div>
                    <button class="btn btn-primary mt-4" type="submit">Change password</button>
                </form>
            </div>
        </section>

        <section class="card settings-card settings-card-wide">
            <div class="card-header">
                <h5 class="mb-1">Application</h5>
                <p class="settings-help mb-0">Current PrivacyVista workspace configuration.</p>
            </div>
            <div class="card-body">
                <div class="settings-options">
                    <div class="settings-option"><div><strong>Theme</strong><span>Dark interface</span></div><span class="settings-status">Active</span></div>
                    <div class="settings-option"><div><strong>Security</strong><span>CSRF protection enabled</span></div><span class="settings-status">Protected</span></div>
                    <div class="settings-option"><div><strong>Session</strong><span>Authenticated workspace</span></div><span class="settings-status">Active</span></div>
                </div>
            </div>
        </section>
    </div>
</div>
