<div class="text-center py-5">

    <h5><?= e($title) ?></h5>

    <p class="text-muted">

        <?= e($message) ?>

    </p>

    <?php if (!empty($button)): ?>

        <a href="<?= $button['url'] ?>"
           class="btn btn-primary">

            <?= e($button['label']) ?>

        </a>

    <?php endif; ?>

</div>