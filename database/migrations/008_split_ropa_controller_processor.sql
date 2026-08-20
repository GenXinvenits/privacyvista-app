-- Split ROPA into independently versioned Controller and Processor templates.
-- Existing legacy `ropa` records/templates are intentionally preserved.
-- New records use one of the two dedicated templates.

INSERT INTO form_templates (slug, name, description, created_by)
SELECT 'ropa-controller', 'ROPA — Controller', 'Record of Processing Activities for data controllers and joint controllers.', NULL
WHERE NOT EXISTS (SELECT 1 FROM form_templates WHERE slug = 'ropa-controller');

INSERT INTO form_templates (slug, name, description, created_by)
SELECT 'ropa-processor', 'ROPA — Processor', 'Record of Processing Activities for processors and sub-processors.', NULL
WHERE NOT EXISTS (SELECT 1 FROM form_templates WHERE slug = 'ropa-processor');

-- Use the latest already-published ROPA definition as the safe migration baseline.
-- The definition remains independently versioned from this point forward.
INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 1, source.definition, 'published',
       'Initial independent ROPA Controller template', NULL
FROM form_templates ft
JOIN (
    SELECT v.definition
    FROM form_template_versions v
    JOIN form_templates t ON t.id = v.form_template_id
    WHERE t.slug = 'ropa' AND v.status = 'published'
    ORDER BY v.version_number DESC
    LIMIT 1
) source
WHERE ft.slug = 'ropa-controller'
  AND NOT EXISTS (
      SELECT 1 FROM form_template_versions v
      WHERE v.form_template_id = ft.id AND v.version_number = 1
  );

INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 1, source.definition, 'published',
       'Initial independent ROPA Processor template', NULL
FROM form_templates ft
JOIN (
    SELECT v.definition
    FROM form_template_versions v
    JOIN form_templates t ON t.id = v.form_template_id
    WHERE t.slug = 'ropa' AND v.status = 'published'
    ORDER BY v.version_number DESC
    LIMIT 1
) source
WHERE ft.slug = 'ropa-processor'
  AND NOT EXISTS (
      SELECT 1 FROM form_template_versions v
      WHERE v.form_template_id = ft.id AND v.version_number = 1
  );

UPDATE form_templates ft
JOIN form_template_versions v ON v.form_template_id = ft.id AND v.version_number = 1
SET ft.active_version_id = v.id
WHERE ft.slug IN ('ropa-controller', 'ropa-processor')
  AND ft.active_version_id IS NULL;
