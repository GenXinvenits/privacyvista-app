<?php
$title = 'Edit User';
$isSuperuser = (int)($user['role_id'] ?? 0) === 1;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h3>Edit User</h3><small class="text-muted">Update user information and access scope</small></div>
    <a href="<?= url('users') ?>" class="btn btn-secondary">Back</a>
</div>

<div class="card"><div class="card-body">
<form method="post" action="<?= url('users/update') ?>">
    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Full Name</label><input type="text" name="fullname" class="form-control" value="<?= e($user['fullname']) ?>" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Role</label><select name="role_id" id="role_id" class="form-select"><option value="1" <?= (int)$user['role_id']===1?'selected':'' ?>>Superuser</option><option value="2" <?= (int)$user['role_id']===2?'selected':'' ?>>Admin</option><option value="3" <?= (int)$user['role_id']===3?'selected':'' ?>>Client</option></select></div>
        <div class="col-md-6 mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" <?= $user['status']?'selected':'' ?>>Active</option><option value="0" <?= !$user['status']?'selected':'' ?>>Inactive</option></select></div>
        <div class="col-12 mb-3" id="client-assignment-row">
            <label class="form-label">Assigned Client</label>
            <select name="client_id" id="client_id" class="form-select">
                <option value="">Select a client...</option>
                <?php foreach (($clients ?? []) as $client): ?><option value="<?= (int)$client['id'] ?>" <?= (int)($user['client_id'] ?? 0)===(int)$client['id']?'selected':'' ?>><?= e($client['company_name']) ?></option><?php endforeach; ?>
            </select>
            <div class="form-text">Required for Admin and Client accounts. Superusers do not require a client assignment.</div>
        </div>
    </div>
    <button class="btn btn-success">Update User</button>
</form>
</div></div>

<script>
(function(){
 const role=document.getElementById('role_id'), row=document.getElementById('client-assignment-row'), client=document.getElementById('client_id');
 function sync(){const needs=role.value!=='1'; row.style.display=needs?'block':'none'; client.required=needs;}
 role.addEventListener('change',sync); sync();
})();
</script>