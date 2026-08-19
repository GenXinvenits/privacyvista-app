CREATE TABLE IF NOT EXISTS admin_client_access (
    admin_user_id INT NOT NULL,
    client_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (admin_user_id, client_id),
    KEY idx_admin_client_access_client (client_id),
    CONSTRAINT fk_admin_client_access_user FOREIGN KEY (admin_user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_admin_client_access_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO admin_client_access (admin_user_id, client_id)
SELECT u.id, u.client_id
FROM users u
JOIN roles r ON r.id=u.role_id
WHERE LOWER(r.name)='admin' AND u.client_id IS NOT NULL AND u.client_id > 0;
