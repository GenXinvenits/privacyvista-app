-- Versioned form-template architecture
-- Published versions are immutable. Records keep the exact version used at creation.

CREATE TABLE IF NOT EXISTS form_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL UNIQUE,
    name VARCHAR(190) NOT NULL,
    description TEXT NULL,
    active_version_id BIGINT UNSIGNED NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_form_templates_active (active_version_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS form_template_versions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    form_template_id BIGINT UNSIGNED NOT NULL,
    version_number INT UNSIGNED NOT NULL,
    definition JSON NOT NULL,
    status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
    change_summary VARCHAR(500) NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_form_template_version (form_template_id, version_number),
    INDEX idx_form_versions_template (form_template_id),
    INDEX idx_form_versions_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE ropa_records ADD COLUMN form_version_id BIGINT UNSIGNED NULL AFTER client_id;
CREATE INDEX idx_ropa_form_version ON ropa_records(form_version_id);

INSERT INTO form_templates (slug,name,description,created_by)
SELECT 'ropa','Record of Processing Activities','Versioned Record of Processing Activities form.',NULL
WHERE NOT EXISTS (SELECT 1 FROM form_templates WHERE slug='ropa');

INSERT INTO form_template_versions (form_template_id,version_number,definition,status,change_summary,created_by)
SELECT ft.id,1,
'{"sections":[{"title":"Record & organisation","description":"Identify the processing activity and the organisation responsible for it.","fields":[{"key":"client_id","label":"Organisation / Client","type":"client","required":true},{"key":"process_name","label":"Processing activity / process","type":"text","required":true},{"key":"department","label":"Department","type":"text"},{"key":"project","label":"Project / system","type":"text"},{"key":"status","label":"Processing status","type":"select","options":["planned","active","under_review","paused","retired"]}]},{"title":"Roles, governance & legal framework","fields":[{"key":"controller_role","label":"Controller / Joint Controller role","type":"select","options":["controller","joint_controller","processor","other"]},{"key":"legal_frameworks","label":"Applicable legal frameworks","type":"multiselect","options":["GDPR","DPDPA 2023","CCPA/CPRA","Other"]},{"key":"process_owner","label":"Process owner","type":"text"},{"key":"dpo","label":"DPO / Privacy contact","type":"text"},{"key":"eu_representative","label":"EU representative","type":"text"},{"key":"lawful_basis","label":"Lawful basis","type":"select","options":["Consent","Contract","Legal obligation","Vital interests","Public task","Legitimate interests"]},{"key":"purpose","label":"Processing purpose","type":"textarea"}]},{"title":"Data mapping & classification","fields":[{"key":"data_subject_categories","label":"Data-subject categories","type":"textarea"},{"key":"personal_data_categories","label":"Personal-data categories","type":"textarea"},{"key":"special_data","label":"Special / sensitive data","type":"textarea"},{"key":"data_source","label":"Source of data","type":"textarea"},{"key":"privacy_notice_reference","label":"Privacy notice reference","type":"text"},{"key":"data_format","label":"Data format","type":"text"},{"key":"hosting_system","label":"Hosting system / asset","type":"text"},{"key":"processing_environment","label":"Processing environment","type":"text"},{"key":"storage_countries","label":"Storage countries","type":"text"},{"key":"data_volume","label":"Data volume","type":"text"},{"key":"processing_frequency","label":"Processing frequency","type":"select","options":["daily","weekly","monthly","continuous","ad_hoc"]},{"key":"retention_period","label":"Retention period","type":"text"}]},{"title":"Recipients, processors & transfers","fields":[{"key":"internal_recipients","label":"Internal recipients","type":"textarea"},{"key":"external_recipients","label":"External recipients","type":"textarea"},{"key":"processors","label":"Processors / controllers / subprocessors","type":"textarea"},{"key":"international_transfer","label":"International transfer","type":"select","options":["no","yes"]},{"key":"transfer_safeguards","label":"Transfer safeguards","type":"textarea"}]},{"title":"Security, privacy by design & DPIA","fields":[{"key":"security_measures","label":"Technical & organisational security measures","type":"textarea"},{"key":"privacy_by_design","label":"Privacy by design / default","type":"select","options":["yes","no","not_assessed"]},{"key":"dpia_applicability","label":"DPIA applicability","type":"select","options":["not_required","required","completed","under_review"]},{"key":"dpia_triggers","label":"DPIA triggers","type":"text"},{"key":"linked_dpia","label":"Linked DPIA","type":"text"},{"key":"lia_reference","label":"LIA reference","type":"text"}]},{"title":"Privacy governance & data-subject rights","fields":[{"key":"dpa_reference","label":"DPA reference","type":"text"},{"key":"joint_controller_agreement","label":"Joint-controller agreement","type":"text"},{"key":"third_party_risk_assessment","label":"Third-party risk assessment","type":"text"},{"key":"privacy_risk_rating","label":"Privacy risk rating","type":"select","options":["low","medium","high","critical"]},{"key":"consent_mechanism","label":"Consent mechanism","type":"text"},{"key":"adm_profiling","label":"ADM / profiling","type":"select","options":["no","yes","automated_decision","profiling"]}]}]}',
'published','Initial version',NULL
FROM form_templates ft
WHERE ft.slug='ropa'
AND NOT EXISTS (SELECT 1 FROM form_template_versions v WHERE v.form_template_id=ft.id AND v.version_number=1);

UPDATE form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=1
SET ft.active_version_id=v.id
WHERE ft.slug='ropa' AND ft.active_version_id IS NULL;

UPDATE ropa_records r
JOIN form_templates ft ON ft.slug='ropa'
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=1
SET r.form_version_id=v.id
WHERE r.form_version_id IS NULL;
