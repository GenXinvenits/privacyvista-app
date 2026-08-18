<?php
component('page-header', [
    'title' => 'Users',
    'subtitle' => 'Manage application users',
    'actions' => [['label' => '+ Add User', 'url' => url('users/create'), 'class' => 'btn-primary']]
]);
?>
<div class="card"><div class="card-body">
    <form method="get" class="row g-2 mb-3">
        <input type="hidden" name="route" value="users">
        <div class="col"><input type="search" name="q" class="form-control" placeholder="Search users..." value="<?= e($q ?? '') ?>"></div>
        <div class="col-auto"><button type="submit" class="btn btn-outline-primary">Search</button></div>
        <?php if (!empty($q)): ?><div class="col-auto"><a href="<?= url('users') ?>" class="btn btn-outline-secondary">Clear</a></div><?php endif; ?>
    </form>
    <div class="table-responsive"><table class="table table-hover align-middle">
        <thead class="table-light"><tr><th width="80">ID</th><th>Name</th><th>Email</th><th width="140">Role</th><th width="120">Status</th><th width="200">Actions</th></tr></thead>
        <tbody>
        <?php if (empty($users)): ?><tr><td colspan="6" class="text-center text-muted">No users found.</td></tr>
        <?php else: foreach ($users as $user): $isSuperuser = strtolower((string)($user['role'] ?? '')) === 'superuser'; $canManage = $this->isSuperuser() || !$isSuperuser; ?><tr>
            <td><?= e($user['id']) ?></td><td><?= e($user['fullname']) ?></td><td><?= e($user['email']) ?></td>
            <td><span class="badge bg-primary"><?= e($user['role']) ?></span></td>
            <td><?= $user['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?></td>
            <td>
                <?php if ($canManage): ?>
                    <a href="<?= url('users/edit', ['id' => $user['id']]) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form method="post" action="<?= url('users/delete') ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');"><?php include __DIR__ . '/../partials/csrf.php'; ?><input type="hidden" name="id" value="<?= (int)$user['id'] ?>"><button type="submit" class="btn btn-sm btn-danger">Delete</button></form>
                <?php else: ?>
                    <button type="button" class="btn btn-sm btn-secondary" disabled title="Admins cannot edit Superusers">Edit</button>
                    <button type="button" class="btn btn-sm btn-secondary" disabled title="Admins cannot delete Superusers">Delete</button>
                <?php endif; ?>
            </td>
        </tr><?php endforeach; endif; ?>
        </tbody>
    </table></div>
</div></div>