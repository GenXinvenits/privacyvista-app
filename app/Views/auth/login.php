<main class="login-page">
    <div class="login-card">
        <div class="login-brand">
            <img src="https://privacyvista.com/wp-content/uploads/2025/12/privacy-vista-logo-light.png" alt="PrivacyVista">
        </div>

        <div class="login-heading">
            <h1>Welcome back</h1>
            <p>Sign in to continue to PrivacyVista</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="login-alert">
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="post" action="/app/public/index.php?route=login" class="login-form">
            <div class="login-field">
                <label for="email">Email address</label>
                <input id="email" type="email" name="email" autocomplete="email" placeholder="you@example.com" required>
            </div>

            <div class="login-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-submit">Sign in</button>
        </form>

        <p class="login-footer">PrivacyVista Privacy Management Platform</p>
    </div>
</main>
