-- ROPA v4 reconciliation.
-- v3 contained path/index mistakes in behavioral metadata. Do not mutate v3: create corrected immutable v4 definitions.
-- Existing records retain their original form_version_id.
--
-- The Processor v4 definition is intentionally built in small UPDATE steps.
-- Deeply nested JSON_ARRAY_APPEND expressions are not reliably parsed by the
-- MariaDB/phpMyAdmin combination used by PrivacyVista.

INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 4,
       JSON_SET(v.definition,
         '$.sections[1].fields[0].type', 'auto_date',
         '$.sections[2].fields[4].type', 'multiselect',
         '$.sections[2].fields[4].options', JSON_ARRAY('PII','SPI','Address','IP Address','Account Number','Date of Birth','Physical Address','Financial Data','Health Data','Biometric Data','Other')
       ),
       'published', 'Correct Controller behavioral field mappings', NULL
FROM form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=2
WHERE ft.slug='ropa-controller'
  AND NOT EXISTS (SELECT 1 FROM form_template_versions x WHERE x.form_template_id=ft.id AND x.version_number=4);

-- Rebuild Processor v4 from the clean v2 definition so v3 duplicate fields/path errors are not carried forward.
-- Start with an immutable copy of v2.
INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 4, v.definition,
       'published', 'Reconcile Processor behavioral fields and remove v3 path errors', NULL
FROM form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=2
WHERE ft.slug='ropa-processor'
  AND NOT EXISTS (SELECT 1 FROM form_template_versions x WHERE x.form_template_id=ft.id AND x.version_number=4);

-- Add Processor-specific fields one operation at a time for MariaDB compatibility.
UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[0].fields',
    JSON_OBJECT('key','eu_representative','label','EU Representative Name of Processor (if Applicable)','type','text'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[2].fields',
    JSON_OBJECT('key','dpdpa_whitelist_status','label','Country Whitelisted as per DPDPA List','type','verification'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','dpia_applicability','label','Data Protection Impact Assessment Applicable','type','select','options',JSON_ARRAY('yes','no')))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','dpia_triggers','label','DPIA Trigger(s)','type','multiselect','options',JSON_ARRAY('Large Amount of Data','Processing Sensitive Personal Information','Systematic Profiling','Public Monitoring','New Technologies','Data Matching','Invisible Processing','Vulnerable Subjects','Biometric Identification','Genetic Data Processing','Other')))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','adm_profiling','label','Processing Includes Automated Decision-Making / Profiling','type','select','options',JSON_ARRAY('yes','no')))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','adm_profiling_details','label','ADM / Profiling Details','type','textarea','show_when',JSON_OBJECT('field','adm_profiling','equals','yes')))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','dpia_assistance','label','Processor Assists Controller with DPIA','type','select','options',JSON_ARRAY('yes','no','not_applicable')))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','dsar_support','label','DSAR Support Process Established','type','select','options',JSON_ARRAY('yes','no','not_applicable')))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','data_subject_requests','label','Data Subject Requests / Complaints Record','type','textarea'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','breach_management','label','Personal Data Breach / Incident Management','type','textarea'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','last_audit_date','label','Last Audit / Review Date','type','date'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','last_audit_performed_by','label','Last Audit Performed By','type','text'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','next_review_date','label','Next Scheduled Review Date','type','derived_date','derived_from','last_audit_date','offset_months',11))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','approver','label','Approver (Management / DPO)','type','text'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(v.definition, '$.sections[4].fields',
    JSON_OBJECT('key','remarks','label','Remarks / Notes','type','textarea'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

-- The DPIA trigger field is conditional on DPIA applicability.
UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_SET(v.definition,
    '$.sections[4].fields[4].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'))
WHERE ft.slug='ropa-processor' AND v.version_number=4 AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.status='archived'
WHERE ft.slug IN ('ropa-controller','ropa-processor')
  AND v.version_number=3
  AND v.status='published';

UPDATE form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=4
SET ft.active_version_id=v.id
WHERE ft.slug IN ('ropa-controller','ropa-processor');
