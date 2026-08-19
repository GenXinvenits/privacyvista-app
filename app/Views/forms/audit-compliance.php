<?php
$title = 'Audit & Compliance Tracker';
$record = $record ?? [];
$value = static fn (string $name): string => e((string)($record[$name] ?? ''));
?>

<div class="page-header mb-4"><div><div class="page-title">Audit &amp; Compliance Tracker</div><div class="page-subtitle mb-0">Track privacy audits, findings, corrective actions and closure verification.</div></div></div>

<form method="post" action="<?= url('forms/audit-compliance/store') ?>" class="card">
<?php include __DIR__ . '/../partials/csrf.php'; ?>
<div class="card-body"><div class="row g-4">
<div class="col-md-6"><label class="form-label">ROPA record / process</label><input class="form-control" name="process_name" value="<?= $value('process_name') ?>" required></div>
<div class="col-md-6"><label class="form-label">Audit type</label><select class="form-select" name="audit_type"><option value="internal">Internal audit</option><option value="external">External audit</option><option value="compliance_review">Compliance review</option><option value="follow_up">Follow-up</option></select></div>
<div class="col-md-4"><label class="form-label">Audit date</label><input class="form-control" type="date" name="audit_date"></div>
<div class="col-md-4"><label class="form-label">Auditor</label><input class="form-control" name="auditor" value="<?= $value('auditor') ?>"></div>
<div class="col-md-4"><label class="form-label">Applicable legal framework</label><input class="form-control" name="legal_framework" value="<?= $value('legal_framework') ?>"></div>
<div class="col-12"><label class="form-label">Findings</label><textarea class="form-control" name="findings" rows="4"><?= $value('findings') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Evidence reference</label><textarea class="form-control" name="evidence_reference" rows="3"><?= $value('evidence_reference') ?></textarea></div>
<div class="col-md-6"><label class="form-label">Non-compliance</label><textarea class="form-control" name="non_compliance" rows="3"><?= $value('non_compliance') ?></textarea></div>
<div class="col-12"><label class="form-label">Corrective actions</label><textarea class="form-control" name="corrective_actions" rows="4"><?= $value('corrective_actions') ?></textarea></div>
<div class="col-md-4"><label class="form-label">Process owner</label><input class="form-control" name="process_owner" value="<?= $value('process_owner') ?>"></div>
<div class="col-md-4"><label class="form-label">Target closure</label><input class="form-control" type="date" name="target_closure"></div>
<div class="col-md-4"><label class="form-label">Verification date</label><input class="form-control" type="date" name="verification_date"></div>
<div class="col-md-6"><label class="form-label">Final status</label><select class="form-select" name="final_status"><option value="open">Open</option><option value="in_progress">In progress</option><option value="closed">Closed</option><option value="accepted">Risk accepted</option></select></div>
<div class="col-12"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="3"><?= $value('remarks') ?></textarea></div>
</div></div>
<div class="card-footer d-flex justify-content-between"><a class="btn btn-outline-secondary" href="<?= url('forms') ?>">Cancel</a><button class="btn btn-primary" type="submit">Save audit record</button></div>
</form>
