<div class="col-md-10 p-0">

<nav class="navbar navbar-expand-lg bg-light border-bottom">

    <div class="container-fluid">

        <span class="navbar-brand">

            <?= $title ?? 'Dashboard'; ?>

        </span>

        <div class="ms-auto">

            <?php if(isset($_SESSION['user'])): ?>

                Welcome,

                <strong><?= htmlspecialchars($_SESSION['user']['fullname']) ?></strong>

            <?php endif; ?>

        </div>

    </div>

</nav>

<div class="p-4">