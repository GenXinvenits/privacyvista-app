<?php
$title = 'Client Management';

component('page-header', [
    'title' => 'Clients',
    'subtitle' => 'Manage client companies',
    'actions' => [
        [
            'label' => '+ Add Client',
            'url' => url('clients/create'),
            'class' => 'btn-primary'
        ]
    ]
]);
?>

<div class="card">

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-4">

                <input type="text"
                       class="form-control"
                       placeholder="Search clients...">

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th width="170">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($clients)): ?>

                    <tr>

                        <td colspan="7" class="text-center text-muted">
                            No clients found.
                        </td>

                    </tr>

                <?php else: ?>

                    <?php foreach($clients as $client): ?>

                        <tr>

                            <td><?= $client['id']; ?></td>

                            <td><?= htmlspecialchars($client['company_name']); ?></td>

                            <td><?= htmlspecialchars($client['contact_person']); ?></td>

                            <td><?= htmlspecialchars($client['email']); ?></td>

                            <td><?= htmlspecialchars($client['phone']); ?></td>

                            <td>

                                <?php if($client['status']): ?>

                                    <span class="badge bg-success">Active</span>

                                <?php else: ?>

                                    <span class="badge bg-danger">Inactive</span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <a href="<?= url('clients/show', ['id' => $client['id']]) ?>"
                                   class="btn btn-sm btn-info">
                                    View
                                </a>

                                <a href="<?= url('clients/edit', ['id' => $client['id']]) ?>"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <a href="<?= url('clients/delete', ['id' => $client['id']]) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this client?');">
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