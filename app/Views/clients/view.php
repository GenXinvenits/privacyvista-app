<?php
$title = e($client['company_name']);

$active = 'overview';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1"><?= e($client['company_name']) ?></h2>

        <div class="text-muted">

            <?= e($client['contact_person']) ?>

            <?php if (!empty($client['email'])): ?>
                • <?= e($client['email']) ?>
            <?php endif; ?>

            <?php if (!empty($client['phone'])): ?>
                • <?= e($client['phone']) ?>
            <?php endif; ?>

        </div>

    </div>

    <div>

        <?php if ($client['status']): ?>

            <span class="badge bg-success fs-6">
                Active
            </span>

        <?php else: ?>

            <span class="badge bg-danger fs-6">
                Inactive
            </span>

        <?php endif; ?>

        <a href="<?= url('clients') ?>"
           class="btn btn-outline-secondary ms-2">
            Back
        </a>

    </div>

</div>


<?php require __DIR__ . '/partials/tabs.php'; ?>


<div class="row g-3 mb-4">

<?php

component('stat-card', [
    'title' => 'Users',
    'value' => 0
]);

component('stat-card', [
    'title' => 'Departments',
    'value' => 0
]);

component('stat-card', [
    'title' => 'Assessments',
    'value' => 0
]);

component('stat-card', [
    'title' => 'Compliance',
    'value' => '0%'
]);

?>

</div>


<div class="row">

    <div class="col-lg-8">

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <strong>Company Information</strong>

            </div>

            <div class="card-body">

                <table class="table table-borderless mb-0">

                    <tr>
                        <th width="220">Company Name</th>
                        <td><?= e($client['company_name']) ?></td>
                    </tr>

                    <tr>
                        <th>Contact Person</th>
                        <td><?= e($client['contact_person']) ?></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><?= e($client['email']) ?></td>
                    </tr>

                    <tr>
                        <th>Phone</th>
                        <td><?= e($client['phone']) ?></td>
                    </tr>

                    <tr>
                        <th>Status</th>

                        <td>

                            <?php if ($client['status']): ?>

                                <span class="badge bg-success">

                                    Active

                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header">

                <strong>Recent Activity</strong>

            </div>

            <div class="card-body text-muted">

                No activity available.

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <strong>Quick Actions</strong>

            </div>

            <div class="list-group list-group-flush">

                <a href="#"
                   class="list-group-item list-group-item-action">

                    Add User

                </a>

                <a href="#"
                   class="list-group-item list-group-item-action">

                    Start Assessment

                </a>

                <a href="#"
                   class="list-group-item list-group-item-action">

                    Upload Evidence

                </a>

                <a href="#"
                   class="list-group-item list-group-item-action">

                    Generate Report

                </a>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header">

                <strong>Client Summary</strong>

            </div>

            <div class="card-body">

                <p class="mb-2">

                    <strong>Created</strong>

                </p>

                <p class="text-muted">

                    <?= e($client['created_at'] ?? '-') ?>

                </p>

                <hr>

                <p class="mb-2">

                    <strong>Next Assessment</strong>

                </p>

                <p class="text-muted">

                    Not scheduled

                </p>

            </div>

        </div>

    </div>

</div>