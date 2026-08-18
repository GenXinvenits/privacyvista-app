<?php
$title = 'Edit Client';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3>Edit Client</h3>
        <small class="text-muted">Update client information</small>
    </div>

    <a href="<?= url('clients') ?>" class="btn btn-secondary">
        Back
    </a>

</div>

<div class="card">

    <div class="card-body">

        <form method="post" action="<?= url('clients/update') ?>">

            <input type="hidden" name="id" value="<?= e($client['id']) ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name</label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        value="<?= e($client['company_name']) ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Person</label>

                    <input
                        type="text"
                        name="contact_person"
                        class="form-control"
                        value="<?= e($client['contact_person']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= e($client['email']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= e($client['phone']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="1" <?= $client['status'] ? 'selected' : '' ?>>
                            Active
                        </option>

                        <option value="0" <?= !$client['status'] ? 'selected' : '' ?>>
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <button type="submit" class="btn btn-success">
                Update Client
            </button>

        </form>

    </div>

</div>