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
 CONSTRAINT fk_findings_assessment FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS privacy_tasks (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 client_id BIGINT UNSIGNED NOT NULL,
 assessment_id BIGINT UNSIGNED NULL,
 finding_id BIGINT UNSIGNED NULL,
 title VARCHAR(255) NOT NULL,
 description TEXT NULL,
 priority ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
 status ENUM('open','in_progress','blocked','completed','cancelled') NOT NULL DEFAULT 'open',
 assigned_to BIGINT UNSIGNED NULL,
 due_date DATE NULL,
 completed_at DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_tasks_client (client_id), INDEX idx_tasks_assessment (assessment_id), INDEX idx_tasks_assignee (assigned_to),
 CONSTRAINT fk_tasks_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
 CONSTRAINT fk_tasks_assessment FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE SET NULL,
 CONSTRAINT fk_tasks_finding FOREIGN KEY (finding_id) REFERENCES assessment_findings(id) ON DELETE SET NULL,
 CONSTRAINT fk_tasks_assignee FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
