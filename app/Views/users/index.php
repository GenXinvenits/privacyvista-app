<?php

component('page-header', [
    'title' => 'Users',
    'subtitle' => 'Manage application users',
    'actions' => [
        [
            'label' => '+ Add User',
            'url' => url('users/create'),
            'class' => 'btn-primary'
        ]
    ]
]);

?>

<div class="card">

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-4">

                <input
                    type="text"
                    class="form-control"
                    placeholder="Search users...">

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th width="80">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="140">Role</th>
                        <th width="120">Status</th>
                        <th width="170">Actions</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($users)): ?>

                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No users found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($users as $user): ?>

                        <tr>

                            <td><?= e($user['id']) ?></td>

                            <td><?= e($user['fullname']) ?></td>

                            <td><?= e($user['email']) ?></td>

                            <td>
                                <span class="badge bg-primary">
                                    <?= e($user['role']) ?>
                                </span>
                            </td>

                            <td>

                                <?php if ($user['status']): ?>

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <a href="<?= url('users/edit', ['id' => $user['id']]) ?>"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <a href="<?= url('users/delete', ['id' => $user['id']]) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>