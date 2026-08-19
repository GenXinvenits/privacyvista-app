<?php
$title = 'Data Mapping & Classification';
$ropa = $ropa ?? [];
$value = static fn (string $name): string => e((string)($ropa[$name] ?? ''));
?>

<div class="page-header mb-4"><div><div class="page-title">Data Mapping &amp; Classification</div><div class="page-subtitle mb-0">Map data sources, classifications, recipients, storage and lifecycle controls for a ROPA record.</div></div></div>

<form method="post" action="<?= url('forms/data-mapping/store') ?>" class="card">
<?php include __DIR__ . '/../partials/csrf.php'; ?>
<div class="card-body">
<div class="row g-4">
<div class="col-md-6"><label class="form-label">ROPA record</label><input class="form-control" value="<?= $value('process_name') ?>" readonly></div>
<div class="col-md-6"><label class="form-label">Process / activity</label><input class="form-control" name="process_name" value="<?= $value('process_name') ?>" required></div>
<div class="col-md-6"><label class="form-label">Data-subject categories</label><textarea class="form-control" name="data_subject_categories" rows="3"><?= $value('data_subject_categories') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Data source</label><textarea class="form-control" name="data_source" rows="3"><?= $value('data_source') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Sensitive / special-category data</label><textarea class="form-control" name="special_data" rows="3"><?= $value('special_data') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Internal classification</label><select class="form-select" name="classification"><option value="public">Public</option><option value="internal">Internal</option><option value="confidential">Confidential</option><option value="restricted">Restricted</option></select></div>
<div class="col-md-6"><label class="form-label">Hosting system</label><input class="form-control" name="hosting_system" value="<?= $value('hosting_system') ?>"></div>
<div class="col-md-6"><label class="form-label">Data lifecycle</label><textarea class="form-control" name="data_lifecycle" rows="3" placeholder="Collection → use → storage → archive → deletion"><?= $value('data_lifecycle') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Internal recipients</label><textarea class="form-control" name="internal_recipients" rows="3"><?= $value('internal_recipients') ?></textarea></div>
<div class="col-md-6"><label class="form-label">External recipients</label><textarea class="form-control" name="external_recipients" rows="3"><?= $value('external_recipients') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Processors / controllers / subprocessors</label><textarea class="form-control" name="processors" rows="3"><?= $value('processors') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Cross-border transfer</label><select class="form-select" name="international_transfer"><option value="no">No</option><option value="yes">Yes</option></select></div>
<div class="col-md-6"><label class="form-label">Transfer safeguard</label><textarea class="form-control" name="transfer_safeguard" rows="3"><?= $value('transfer_safeguards') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Cloud provider</label><input class="form-control" name="cloud_provider" value="<?= $value('cloud_provider') ?>"></div>
<div class="col-md-6"><label class="form-label">Storage countries</label><input class="form-control" name="storage_countries" value="<?= $value('storage_countries') ?>"></div>
<div class="col-md-6"><label class="form-label">Retention</label><input class="form-control" name="retention_period" value="<?= $value('retention_period') ?>"></div>
<div class="col-md-6"><label class="form-label">Disposal date</label><input class="form-control" type="date" name="disposal_date"></div>
<div class="col-md-6"><label class="form-label">Disposal method</label><input class="form-control" name="disposal_method"></div>
<div class="col-md-6"><label class="form-label">Last updated by</label><input class="form-control" name="updated_by"></div>
</div></div>
<div class="card-footer d-flex justify-content-between"><a class="btn btn-outline-secondary" href="<?= url('forms') ?>">Cancel</a><button class="btn btn-primary" type="submit">Save data mapping</button></div>
</form>
