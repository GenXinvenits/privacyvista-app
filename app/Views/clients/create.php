<?php
$title = 'Add Client';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3>Add Client</h3>
        <small class="text-muted">Create a new client company</small>
    </div>

    <a href="<?= url('clients') ?>"
       class="btn btn-secondary">
        Back
    </a>

</div>

<div class="card">

    <div class="card-body">

        <form method="post"
              action="<?= url('clients/store') ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name</label>

                    <input type="text"
                           name="company_name"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Person</label>

                    <input type="text"
                           name="contact_person"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>

                    <input type="email"
                           name="email"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>

                    <input type="text"
                           name="phone"
                           class="form-control">
                </div>

            </div>

            <button class="btn btn-success">
                Save Client
            </button>

        </form>

    </div>

</div>