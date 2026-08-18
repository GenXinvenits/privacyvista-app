<?php
$title = 'Add User';
$clients = $clients ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Add User</h3>
        <small class="text-muted">Create a new application user</small>
    </div>

    <a href="<?= url('users') ?>" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= url('users/store') ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_id" id="user-role" class="form-select" required>
                        <option value="1">Superuser</option>
                        <option value="2">Admin</option>
                        <option value="3" selected>Client</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3" id="client-assignment-field">
                    <label class="form-label">Assigned Client <span class="text-danger">*</span></label>
                    <select name="client_id" id="client-id" class="form-select">
                        <option value="">Select client...</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= (int)$client['id'] ?>">
                                <?= e($client['company_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Required for Admin and Client accounts.</div>
                </div>
            </div>

            <button class="btn btn-success">Save User</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const role = document.getElementById('user-role');
    const field = document.getElementById('client-assignment-field');
    const client = document.getElementById('client-id');

    function syncClientField() {
        const requiresClient = role.value !== '1';
        field.style.display = requiresClient ? '' : 'none';
        client.required = requiresClient;

        if (!requiresClient) {
            client.value = '';
        }
    }

    role.addEventListener('change', syncClientField);
    syncClientField();
});
</script>