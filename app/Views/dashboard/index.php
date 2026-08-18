<?php

$title = 'Dashboard';

?>

<div class="row">

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Total Users</h5>

                <h2>1</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Total Clients</h5>

                <h2>0</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Forms</h5>

                <h2>0</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Reports</h5>

                <h2>0</h2>

            </div>

        </div>

    </div>

</div>

<div class="card mt-4">

    <div class="card-header">

        Recent Activity

    </div>

    <div class="card-body">

        Welcome <strong><?= htmlspecialchars($user['fullname']) ?></strong>

        <br><br>

        Email :
        <?= htmlspecialchars($user['email']) ?>

        <br>

        Role :
        <?= htmlspecialchars($user['role']) ?>

    </div>

</div>