-- Supporting indexes for client-scoped privacy reporting.
CREATE INDEX idx_pa_client_risk_status ON processing_activities(client_id, risk_level, status);
CREATE INDEX idx_assessment_client_risk ON privacy_assessments(client_id, risk_score, status);
CREATE INDEX idx_task_client_priority_status_due ON privacy_tasks(client_id, priority, status, due_date);
CREATE INDEX idx_audit_client_created ON privacy_audit_log(client_id, created_at);
