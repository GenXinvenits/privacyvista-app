-- ROPA v3: encode behavioral metadata from the supplied Controller/Processor specifications.
-- Published v2 definitions are never modified. Existing records keep their original version.
--
-- This migration intentionally avoids deeply nested JSON_ARRAY_APPEND() expressions.
-- MariaDB/phpMyAdmin parsing is more reliable when each array mutation is performed
-- as a separate UPDATE.

INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 3,
       JSON_SET(v.definition,
         '$.sections[1].fields[0].type', 'auto_date',
         '$.sections[2].fields[4].type', 'verification',
         '$.sections[4].fields[2].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
         '$.sections[4].fields[3].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
         '$.sections[4].fields[4].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
         '$.sections[4].fields[10].show_when', JSON_OBJECT('field','lawful_basis','equals','Consent'),
         '$.sections[5].fields[1].show_when', JSON_OBJECT('field','adm_profiling','equals','yes'),
         '$.sections[5].fields[9].type', 'derived_date',
         '$.sections[5].fields[9].derived_from', 'last_audit_date',
         '$.sections[5].fields[9].offset_months', 11
       ),
       'published', 'Add Controller conditional, automated and derived field behavior', NULL
FROM form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=2
WHERE ft.slug='ropa-controller'
  AND NOT EXISTS (SELECT 1 FROM form_template_versions x WHERE x.form_template_id=ft.id AND x.version_number=3);

-- Create Processor v3 from the immutable v2 definition first.
INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 3, v.definition,
       'published', 'Add Processor conditional, automated and derived field behavior', NULL
FROM form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=2
WHERE ft.slug='ropa-processor'
  AND NOT EXISTS (SELECT 1 FROM form_template_versions x WHERE x.form_template_id=ft.id AND x.version_number=3);

-- Add Processor fields one at a time so MariaDB does not have to parse a deeply
-- nested JSON_ARRAY_APPEND() expression.
UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(
    v.definition,
    '$.sections[0].fields',
    JSON_OBJECT('key','eu_representative','label','EU Representative Name of Processor (if Applicable)','type','text')
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published'
  AND NOT JSON_CONTAINS(v.definition, JSON_OBJECT('key','eu_representative'), '$.sections[0].fields');

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(
    v.definition,
    '$.sections[2].fields',
    JSON_OBJECT('key','dpdpa_whitelist_status','label','Country Whitelisted as per DPDPA List','type','verification')
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published'
  AND NOT JSON_CONTAINS(v.definition, JSON_OBJECT('key','dpdpa_whitelist_status'), '$.sections[2].fields');

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(
    v.definition,
    '$.sections[2].fields',
    JSON_OBJECT('key','data_volume','label','Data Volume','type','select','options',JSON_ARRAY('under_100000','100000_to_6_million','over_6_million','systematic_public_monitoring','other'))
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published'
  AND NOT JSON_CONTAINS(v.definition, JSON_OBJECT('key','data_volume'), '$.sections[2].fields');

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(
    v.definition,
    '$.sections[4].fields',
    JSON_OBJECT('key','dpia_applicability','label','Data Protection Impact Assessment Applicable','type','select','options',JSON_ARRAY('yes','no'))
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published'
  AND NOT JSON_CONTAINS(v.definition, JSON_OBJECT('key','dpia_applicability'), '$.sections[4].fields');

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(
    v.definition,
    '$.sections[4].fields',
    JSON_OBJECT('key','dpia_triggers','label','DPIA Trigger(s)','type','multiselect','options',JSON_ARRAY('Large Amount of Data','Processing Sensitive Personal Information','Systematic Profiling','Public Monitoring','New Technologies','Data Matching','Invisible Processing','Vulnerable Subjects','Biometric Identification','Genetic Data Processing','Other'))
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published'
  AND NOT JSON_CONTAINS(v.definition, JSON_OBJECT('key','dpia_triggers'), '$.sections[4].fields');

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(
    v.definition,
    '$.sections[4].fields',
    JSON_OBJECT('key','adm_profiling','label','Processing Includes Automated Decision-Making / Profiling','type','select','options',JSON_ARRAY('yes','no'))
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published'
  AND NOT JSON_CONTAINS(v.definition, JSON_OBJECT('key','adm_profiling'), '$.sections[4].fields');

-- Add conditional/derived metadata to the resulting Processor definition.
UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_SET(
    v.definition,
    '$.sections[4].fields[2].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
    '$.sections[4].fields[13].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
    '$.sections[4].fields[14].show_when', JSON_OBJECT('field','adm_profiling','equals','yes'),
    '$.sections[4].fields[9].type', 'derived_date',
    '$.sections[4].fields[9].derived_from', 'last_audit_date',
    '$.sections[4].fields[9].offset_months', 11
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published';

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.definition = JSON_ARRAY_APPEND(
    v.definition,
    '$.sections[4].fields',
    JSON_OBJECT('key','adm_profiling_details','label','ADM / Profiling Details','type','textarea','show_when',JSON_OBJECT('field','adm_profiling','equals','yes'))
)
WHERE ft.slug='ropa-processor'
  AND v.version_number=3
  AND v.status='published'
  AND NOT JSON_CONTAINS(v.definition, JSON_OBJECT('key','adm_profiling_details'), '$.sections[4].fields');

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.status='archived'
WHERE ft.slug IN ('ropa-controller','ropa-processor')
  AND v.version_number=2
  AND v.status='published';

UPDATE form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=3
SET ft.active_version_id=v.id
WHERE ft.slug IN ('ropa-controller','ropa-processor');
