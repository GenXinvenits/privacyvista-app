<div class="row justify-content-center">

    <div class="col-md-4">

        <div class="card shadow">

            <div class="card-header text-center">
                <h3>PrivacyVista Login</h3>
            </div>

            <div class="card-body">

                <?php if(isset($_SESSION['error'])): ?>

                    <div class="alert alert-danger">

                        <?= $_SESSION['error']; ?>

                    </div>

                    <?php unset($_SESSION['error']); ?>

                <?php endif; ?>

                <form method="post" action="/app/public/index.php?route=login">

                    <div class="mb-3">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Password</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>

                    </div>

                    <button
                        class="btn btn-primary w-100">

                        Login

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
