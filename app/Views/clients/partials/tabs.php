<ul class="nav nav-tabs mb-4">

    <li class="nav-item">
        <a class="nav-link <?= $active == 'overview' ? 'active' : '' ?>"
           href="<?= url('clients/show', ['id' => $client['id']]) ?>">
            Overview
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $active == 'departments' ? 'active' : '' ?>"
           href="<?= url('departments', ['client_id' => $client['id']]) ?>">
            Departments
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link disabled" href="#">Users</a>
    </li>

    <li class="nav-item">
        <a class="nav-link disabled" href="#">Processing</a>
    </li>

    <li class="nav-item">
        <a class="nav-link disabled" href="#">Assets</a>
    </li>

    <li class="nav-item">
        <a class="nav-link disabled" href="#">Vendors</a>
    </li>

    <li class="nav-item">
        <a class="nav-link disabled" href="#">Assessments</a>
    </li>

    <li class="nav-item">
        <a class="nav-link disabled" href="#">Reports</a>
    </li>

    <li class="nav-item">
        <a class="nav-link disabled" href="#">Settings</a>
    </li>

</ul>