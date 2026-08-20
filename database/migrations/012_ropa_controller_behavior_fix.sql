-- Controller v5: rebuild behavioral metadata from clean v2 paths.
-- v3 had an incorrect verification path; v4 only corrected that path. v5 carries the complete intended behavior.
-- Existing records remain tied to their historical versions.

INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 5,
       JSON_SET(v.definition,
         '$.sections[1].fields[0].type', 'auto_date',
         '$.sections[2].fields[4].type', 'multiselect',
         '$.sections[2].fields[4].options', JSON_ARRAY('PII','SPI','Address','IP Address','Account Number','Date of Birth','Physical Address','Financial Data','Health Data','Biometric Data','Other'),
         '$.sections[2].fields[4].show_when', NULL,
         '$.sections[3].fields[4].type', 'verification',
         '$.sections[4].fields[2].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
         '$.sections[4].fields[3].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
         '$.sections[4].fields[4].show_when', JSON_OBJECT('field','dpia_applicability','equals','yes'),
         '$.sections[4].fields[10].show_when', JSON_OBJECT('field','lawful_basis','equals','Consent'),
         '$.sections[5].fields[1].show_when', JSON_OBJECT('field','adm_profiling','equals','yes'),
         '$.sections[5].fields[9].type', 'derived_date',
         '$.sections[5].fields[9].derived_from', 'last_audit_date',
         '$.sections[5].fields[9].offset_months', 11
       ),
       'published', 'Complete Controller behavioral field reconciliation', NULL
FROM form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=2
WHERE ft.slug='ropa-controller'
  AND NOT EXISTS (SELECT 1 FROM form_template_versions x WHERE x.form_template_id=ft.id AND x.version_number=5);

UPDATE form_template_versions v
JOIN form_templates ft ON ft.id=v.form_template_id
SET v.status='archived'
WHERE ft.slug='ropa-controller'
  AND v.version_number=4
  AND v.status='published';

UPDATE form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id AND v.version_number=5
SET ft.active_version_id=v.id
WHERE ft.slug='ropa-controller';
