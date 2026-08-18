<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="mb-1"><?= e($title) ?></h3>

        <?php if (!empty($subtitle)): ?>
            <div class="text-muted">
                <?= e($subtitle) ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if (!empty($actions)): ?>

        <div>

            <?php foreach ($actions as $action): ?>

                <a href="<?= $action['url'] ?>"
                   class="btn <?= $action['class'] ?? 'btn-primary' ?> ms-2">

                    <?= e($action['label']) ?>

                </a>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>