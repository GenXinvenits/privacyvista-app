<?php
$title = 'ROPA Master Register';
$clients = $clients ?? [];
$ropa = $ropa ?? [];

$selected = static function (string $name, string $value) use ($ropa): string {
    return (($ropa[$name] ?? '') === $value) ? 'selected' : '';
};

$checked = static function (string $name, string $value) use ($ropa): string {
    $values = $ropa[$name] ?? [];
    if (!is_array($values)) {
        $values = array_filter(array_map('trim', preg_split('/[,\n]+/', (string)$values)));
    }
    return in_array($value, $values, true) ? 'checked' : '';
};

$value = static fn (string $name): string => e((string)($ropa[$name] ?? ''));
?>

<div class="page-header mb-4">
    <div>
        <div class="page-title">ROPA Master Register</div>
        <div class="page-subtitle mb-0">Record and maintain the organisation's Record of Processing Activities.</div>
    </div>
</div>

<form method="post" action="<?= url('forms/ropa/store') ?>" class="card">
    <?php include __DIR__ . '/../partials/csrf.php'; ?>

    <div class="card-body">
        <div class="mb-4">
            <div class="fw-semibold mb-1">1. Record &amp; organisation</div>
            <div class="text-secondary small">Identify the processing activity and the organisation responsible for it.</div>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label" for="client_id">Organisation / Client <span class="text-danger">*</span></label>
                <select class="form-select" id="client_id" name="client_id" required>
                    <option value="">Select organisation</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= (int)$client['id'] ?>" <?= (($ropa['client_id'] ?? '') == $client['id']) ? 'selected' : '' ?>><?= e($client['company_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="process_name">Processing activity / process <span class="text-danger">*</span></label>
                <input class="form-control" id="process_name" name="process_name" required value="<?= $value('process_name') ?>" placeholder="e.g. Customer account management">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="department">Department</label>
                <input class="form-control" id="department" name="department" value="<?= $value('department') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="project">Project / system</label>
                <input class="form-control" id="project" name="project" value="<?= $value('project') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="status">Processing status</label>
                <select class="form-select" id="status" name="status">
                    <option value="planned" <?= $selected('status','planned') ?>>Planned</option>
                    <option value="active" <?= $selected('status','active') ?>>Active</option>
                    <option value="under_review" <?= $selected('status','under_review') ?>>Under review</option>
                    <option value="paused" <?= $selected('status','paused') ?>>Paused</option>
                    <option value="retired" <?= $selected('status','retired') ?>>Retired</option>
                </select>
            </div>
        </div>

        <hr class="my-5">
        <div class="mb-4">
            <div class="fw-semibold mb-1">2. Roles, governance &amp; legal framework</div>
            <div class="text-secondary small">Capture controller roles, accountability contacts and the applicable privacy frameworks.</div>
        </div>
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label">Controller / Joint Controller role</label><select class="form-select" name="controller_role"><option value="controller" <?= $selected('controller_role','controller') ?>>Controller</option><option value="joint_controller" <?= $selected('controller_role','joint_controller') ?>>Joint Controller</option><option value="processor" <?= $selected('controller_role','processor') ?>>Processor</option><option value="other" <?= $selected('controller_role','other') ?>>Other</option></select></div>
            <div class="col-md-6"><label class="form-label">Applicable legal frameworks</label><select class="form-select" name="legal_frameworks[]" multiple size="4"><option value="GDPR" <?= $checked('legal_frameworks','GDPR') ?>>GDPR</option><option value="DPDPA 2023" <?= $checked('legal_frameworks','DPDPA 2023') ?>>DPDPA 2023</option><option value="CCPA/CPRA" <?= $checked('legal_frameworks','CCPA/CPRA') ?>>CCPA / CPRA</option><option value="Other" <?= $checked('legal_frameworks','Other') ?>>Other</option></select><div class="form-text">Use Ctrl/Cmd to select multiple frameworks.</div></div>
            <div class="col-md-4"><label class="form-label">Process owner</label><input class="form-control" name="process_owner" value="<?= $value('process_owner') ?>"></div>
            <div class="col-md-4"><label class="form-label">DPO / Privacy contact</label><input class="form-control" name="dpo" value="<?= $value('dpo') ?>"></div>
            <div class="col-md-4"><label class="form-label">EU representative</label><input class="form-control" name="eu_representative" value="<?= $value('eu_representative') ?>"></div>
            <div class="col-md-6"><label class="form-label">Lawful basis</label><select class="form-select" name="lawful_basis"><option value="" <?= $selected('lawful_basis','') ?>>Select basis</option><option value="Consent" <?= $selected('lawful_basis','Consent') ?>>Consent</option><option value="Contract" <?= $selected('lawful_basis','Contract') ?>>Contract</option><option value="Legal obligation" <?= $selected('lawful_basis','Legal obligation') ?>>Legal obligation</option><option value="Vital interests" <?= $selected('lawful_basis','Vital interests') ?>>Vital interests</option><option value="Public task" <?= $selected('lawful_basis','Public task') ?>>Public task</option><option value="Legitimate interests" <?= $selected('lawful_basis','Legitimate interests') ?>>Legitimate interests</option></select></div>
            <div class="col-md-6"><label class="form-label">Processing purpose</label><textarea class="form-control" name="purpose" rows="3"><?= $value('purpose') ?></textarea></div>
        </div>

        <hr class="my-5">
        <div class="mb-4"><div class="fw-semibold mb-1">3. Data mapping &amp; classification</div><div class="text-secondary small">Describe whose data is processed, what data is used, where it comes from and how it is handled.</div></div>
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label">Data-subject categories</label><textarea class="form-control" name="data_subject_categories" rows="3" placeholder="Customers, employees, applicants…"><?= $value('data_subject_categories') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Personal-data categories</label><textarea class="form-control" name="personal_data_categories" rows="3" placeholder="Names, contact details, identifiers…"><?= $value('personal_data_categories') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Special / sensitive data</label><textarea class="form-control" name="special_data" rows="3" placeholder="Health, biometrics, financial data…"><?= $value('special_data') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Source of data</label><textarea class="form-control" name="data_source" rows="3" placeholder="Data subject, internal system, third party…"><?= $value('data_source') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Privacy notice reference</label><input class="form-control" name="privacy_notice_reference" value="<?= $value('privacy_notice_reference') ?>"></div>
            <div class="col-md-6"><label class="form-label">Data format</label><input class="form-control" name="data_format" value="<?= $value('data_format') ?>" placeholder="Digital, paper, structured…"></div>
            <div class="col-md-6"><label class="form-label">Hosting system / asset</label><input class="form-control" name="hosting_system" value="<?= $value('hosting_system') ?>"></div>
            <div class="col-md-6"><label class="form-label">Processing environment</label><input class="form-control" name="processing_environment" value="<?= $value('processing_environment') ?>" placeholder="On-premise, cloud, hybrid…"></div>
            <div class="col-md-6"><label class="form-label">Storage countries</label><input class="form-control" name="storage_countries" value="<?= $value('storage_countries') ?>"></div>
            <div class="col-md-3"><label class="form-label">Data volume</label><input class="form-control" name="data_volume" value="<?= $value('data_volume') ?>"></div>
            <div class="col-md-3"><label class="form-label">Processing frequency</label><select class="form-select" name="processing_frequency"><option value="daily" <?= $selected('processing_frequency','daily') ?>>Daily</option><option value="weekly" <?= $selected('processing_frequency','weekly') ?>>Weekly</option><option value="monthly" <?= $selected('processing_frequency','monthly') ?>>Monthly</option><option value="continuous" <?= $selected('processing_frequency','continuous') ?>>Continuous</option><option value="ad_hoc" <?= $selected('processing_frequency','ad_hoc') ?>>Ad hoc</option></select></div>
            <div class="col-md-6"><label class="form-label">Retention period</label><input class="form-control" name="retention_period" value="<?= $value('retention_period') ?>" placeholder="e.g. 7 years"></div>
        </div>

        <hr class="my-5">
        <div class="mb-4"><div class="fw-semibold mb-1">4. Recipients, processors &amp; transfers</div><div class="text-secondary small">Document who receives the data and whether information leaves the relevant jurisdiction.</div></div>
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label">Internal recipients</label><textarea class="form-control" name="internal_recipients" rows="3"><?= $value('internal_recipients') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">External recipients</label><textarea class="form-control" name="external_recipients" rows="3"><?= $value('external_recipients') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Processors / controllers / subprocessors</label><textarea class="form-control" name="processors" rows="3"><?= $value('processors') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">International transfer</label><select class="form-select" name="international_transfer"><option value="no" <?= $selected('international_transfer','no') ?>>No</option><option value="yes" <?= $selected('international_transfer','yes') ?>>Yes</option></select></div>
            <div class="col-12"><label class="form-label">Transfer safeguards</label><textarea class="form-control" name="transfer_safeguards" rows="3" placeholder="SCCs, adequacy decision, DPF, safeguards…"><?= $value('transfer_safeguards') ?></textarea></div>
        </div>

        <hr class="my-5">
        <div class="mb-4"><div class="fw-semibold mb-1">5. Security, privacy by design &amp; DPIA</div><div class="text-secondary small">Record safeguards and assessment requirements linked to this processing activity.</div></div>
        <div class="row g-4">
            <div class="col-12"><label class="form-label">Technical &amp; organisational security measures</label><textarea class="form-control" name="security_measures" rows="4" placeholder="Access control, encryption, backups, policies, training…"><?= $value('security_measures') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Privacy by design / default</label><select class="form-select" name="privacy_by_design"><option value="yes" <?= $selected('privacy_by_design','yes') ?>>Yes</option><option value="no" <?= $selected('privacy_by_design','no') ?>>No</option><option value="not_assessed" <?= $selected('privacy_by_design','not_assessed') ?>>Not assessed</option></select></div>
            <div class="col-md-4"><label class="form-label">DPIA applicability</label><select class="form-select" name="dpia_applicability"><option value="not_required" <?= $selected('dpia_applicability','not_required') ?>>Not required</option><option value="required" <?= $selected('dpia_applicability','required') ?>>Required</option><option value="completed" <?= $selected('dpia_applicability','completed') ?>>Completed</option><option value="under_review" <?= $selected('dpia_applicability','under_review') ?>>Under review</option></select></div>
            <div class="col-md-4"><label class="form-label">DPIA triggers</label><input class="form-control" name="dpia_triggers" value="<?= $value('dpia_triggers') ?>"></div>
            <div class="col-md-6"><label class="form-label">Linked DPIA</label><input class="form-control" name="linked_dpia" value="<?= $value('linked_dpia') ?>"></div>
            <div class="col-md-6"><label class="form-label">LIA reference</label><input class="form-control" name="lia_reference" value="<?= $value('lia_reference') ?>"></div>
        </div>

        <hr class="my-5">
        <div class="mb-4"><div class="fw-semibold mb-1">6. Privacy governance &amp; data-subject rights</div><div class="text-secondary small">Capture contractual controls, risk, consent, profiling and rights handling.</div></div>
        <div class="row g-4">
            <div class="col-md-4"><label class="form-label">DPA reference</label><input class="form-control" name="dpa_reference" value="<?= $value('dpa_reference') ?>"></div>
            <div class="col-md-4"><label class="form-label">Joint-controller agreement</label><input class="form-control" name="joint_controller_agreement" value="<?= $value('joint_controller_agreement') ?>"></div>
            <div class="col-md-4"><label class="form-label">Third-party risk assessment</label><input class="form-control" name="third_party_risk_assessment" value="<?= $value('third_party_risk_assessment') ?>"></div>
            <div class="col-md-4"><label class="form-label">Privacy risk rating</label><select class="form-select" name="privacy_risk_rating"><option value="low" <?= $selected('privacy_risk_rating','low') ?>>Low</option><option value="medium" <?= $selected('privacy_risk_rating','medium') ?>>Medium</option><option value="high" <?= $selected('privacy_risk_rating','high') ?>>High</option><option value="critical" <?= $selected('privacy_risk_rating','critical') ?>>Critical</option></select></div>
            <div class="col-md-4"><label class="form-label">Consent mechanism</label><input class="form-control" name="consent_mechanism" value="<?= $value('consent_mechanism') ?>"></div>
            <div class="col-md-4"><label class="form-label">ADM / profiling</label><select class="form-select" name="adm_profiling"><option value="no" <?= $selected('adm_profiling','no') ?>>No</option><option value="yes" <?= $selected('adm_profiling','yes') ?>>Yes</option><option value="automated_decision" <?= $selected('adm_profiling','automated_decision') ?>>Automated decision-making</option><option value="profiling" <?= $selected('adm_profiling','profiling') ?>>Profiling</option></select></div>
            <div class="col-md-6"><label class="form-label">ADM / profiling details</label><textarea class="form-control" name="adm_profiling_details" rows="3"><?= $value('adm_profiling_details') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Data-subject rights</label><textarea class="form-control" name="data_subject_rights" rows="3" placeholder="Access, rectification, erasure, portability…"><?= $value('data_subject_rights') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Breach management</label><textarea class="form-control" name="breach_management" rows="3"><?= $value('breach_management') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Disposal / deletion</label><textarea class="form-control" name="disposal_deletion" rows="3"><?= $value('disposal_deletion') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Retention / disposal policy</label><input class="form-control" name="retention_disposal_policy" value="<?= $value('retention_disposal_policy') ?>"></div>
            <div class="col-md-6"><label class="form-label">Requests / complaints</label><textarea class="form-control" name="requests_complaints" rows="2"><?= $value('requests_complaints') ?></textarea></div>
        </div>

        <hr class="my-5">
        <div class="mb-4"><div class="fw-semibold mb-1">7. Review &amp; approval</div><div class="text-secondary small">Maintain the ROPA review lifecycle and accountability.</div></div>
        <div class="row g-4">
            <div class="col-md-4"><label class="form-label">Last audit / review</label><input class="form-control" type="date" name="last_review" value="<?= $value('last_review') ?>"></div>
            <div class="col-md-4"><label class="form-label">Next review</label><input class="form-control" type="date" name="next_review" value="<?= $value('next_review') ?>"></div>
            <div class="col-md-4"><label class="form-label">Approver</label><input class="form-control" name="approver" value="<?= $value('approver') ?>"></div>
            <div class="col-12"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="4"><?= $value('remarks') ?></textarea></div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <a class="btn btn-outline-secondary" href="<?= url('forms') ?>">Cancel</a>
        <button class="btn btn-primary" type="submit">Create ROPA record</button>
    </div>
</form>
