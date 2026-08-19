-- ROPA Master Register persistence
-- Stores the ROPA template as a structured JSON payload so the form can evolve
-- without losing fields or requiring a schema change for every template revision.

CREATE TABLE IF NOT EXISTS ropa_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    process_name VARCHAR(190) NOT NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'planned',
    data JSON NOT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ropa_client (client_id),
    INDEX idx_ropa_status (status),
    INDEX idx_ropa_process (process_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
