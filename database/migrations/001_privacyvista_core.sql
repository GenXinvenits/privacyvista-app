-- PrivacyVista core privacy-management schema
-- Run once against the application database.

CREATE TABLE IF NOT EXISTS processing_activities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    department_id BIGINT UNSIGNED NULL,
    name VARCHAR(190) NOT NULL,
    description TEXT NULL,
    purpose TEXT NULL,
    legal_basis VARCHAR(100) NULL,
    data_subjects TEXT NULL,
    personal_data_categories TEXT NULL,
    special_category_data TINYINT(1) NOT NULL DEFAULT 0,
    recipients TEXT NULL,
    international_transfer TINYINT(1) NOT NULL DEFAULT 0,
    transfer_details TEXT NULL,
    retention_period VARCHAR(190) NULL,
    security_measures TEXT NULL,
    risk_level ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
    status ENUM('draft','active','under_review','archived') NOT NULL DEFAULT 'draft',
    owner_user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pa_client (client_id),
    INDEX idx_pa_department (department_id),
    INDEX idx_pa_status (status),
    INDEX idx_pa_risk (risk_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS privacy_assessments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    processing_activity_id BIGINT UNSIGNED NULL,
    title VARCHAR(190) NOT NULL,
    assessment_type ENUM('privacy_review','dpia','vendor_review','transfer_review','other') NOT NULL DEFAULT 'privacy_review',
    status ENUM('draft','in_progress','completed','approved','archived') NOT NULL DEFAULT 'draft',
    risk_score DECIMAL(6,2) NULL,
    findings TEXT NULL,
    recommendations TEXT NULL,
    due_date DATE NULL,
    completed_at DATETIME NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_assessment_client (client_id),
    INDEX idx_assessment_status (status),
    INDEX idx_assessment_due (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS privacy_tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    assessment_id BIGINT UNSIGNED NULL,
    title VARCHAR(190) NOT NULL,
    description TEXT NULL,
    priority ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
    status ENUM('open','in_progress','blocked','completed','cancelled') NOT NULL DEFAULT 'open',
    assigned_to BIGINT UNSIGNED NULL,
    due_date DATE NULL,
    completed_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_task_client (client_id),
    INDEX idx_task_status (status),
    INDEX idx_task_due (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS privacy_audit_log (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    client_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NULL,
    entity_id BIGINT UNSIGNED NULL,
    details JSON NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_user (user_id),
    INDEX idx_audit_client (client_id),
    INDEX idx_audit_entity (entity_type, entity_id),
    INDEX idx_audit_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
