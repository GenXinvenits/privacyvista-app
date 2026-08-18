<?php
$title = 'Edit User';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3>Edit User</h3>
        <small class="text-muted">Update user information</small>
    </div>

    <a href="<?= url('users') ?>" class="btn btn-secondary">
        Back
    </a>

</div>

<div class="card">

    <div class="card-body">

        <form method="post" action="<?= url('users/update') ?>">

            <input type="hidden"
                   name="id"
                   value="<?= $user['id']; ?>">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Full Name</label>

                    <input type="text"
                           name="fullname"
                           class="form-control"
                           value="<?= htmlspecialchars($user['fullname']); ?>"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Email</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="<?= htmlspecialchars($user['email']); ?>"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Role</label>

                    <select name="role_id"
                            class="form-select">

                        <option value="1" <?= $user['role_id'] == 1 ? 'selected' : ''; ?>>
                            Superuser
                        </option>

                        <option value="2" <?= $user['role_id'] == 2 ? 'selected' : ''; ?>>
                            Admin
                        </option>

                        <option value="3" <?= $user['role_id'] == 3 ? 'selected' : ''; ?>>
                            Client
                        </option>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Status</label>

                    <select name="status"
                            class="form-select">

                        <option value="1" <?= $user['status'] ? 'selected' : ''; ?>>
                            Active
                        </option>

                        <option value="0" <?= !$user['status'] ? 'selected' : ''; ?>>
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <button class="btn btn-success">
                Update User
            </button>

        </form>

    </div>

</div>