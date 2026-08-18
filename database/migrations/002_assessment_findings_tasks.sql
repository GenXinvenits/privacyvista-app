CREATE TABLE IF NOT EXISTS assessment_findings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 assessment_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(255) NOT NULL,
 description TEXT NULL,
 severity ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
 likelihood TINYINT UNSIGNED NOT NULL DEFAULT 1,
 impact TINYINT UNSIGNED NOT NULL DEFAULT 1,
 status ENUM('open','accepted','mitigated','closed') NOT NULL DEFAULT 'open',
 recommendation TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_findings_assessment (assessment_id),
 CONSTRAINT fk_findings_assessment FOREIGN KEY (assessment_id) REFERENCES privacy_assessments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS privacy_task_links (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 task_id BIGINT UNSIGNED NOT NULL,
 finding_id BIGINT UNSIGNED NOT NULL,
 UNIQUE KEY uq_task_finding (task_id,finding_id),
 CONSTRAINT fk_link_task FOREIGN KEY (task_id) REFERENCES privacy_tasks(id) ON DELETE CASCADE,
 CONSTRAINT fk_link_finding FOREIGN KEY (finding_id) REFERENCES assessment_findings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
