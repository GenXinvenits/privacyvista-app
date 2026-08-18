<?php
$title = 'Add User';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3>Add User</h3>
        <small class="text-muted">Create a new application user</small>
    </div>

    <a href="<?= url('users') ?>" class="btn btn-secondary">
        Back
    </a>

</div>

<div class="card">

    <div class="card-body">

        <form method="post" action="<?= url('users/store') ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>

                    <input
                        type="text"
                        name="fullname"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Password</label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Role</label>

                    <select
                        name="role_id"
                        class="form-select">

                        <option value="1">Superuser</option>
                        <option value="2">Admin</option>
                        <option value="3">Client</option>

                    </select>

                </div>

            </div>

            <button class="btn btn-success">

                Save User

            </button>

        </form>

    </div>

</div>