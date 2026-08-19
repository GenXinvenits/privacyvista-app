<?php

namespace App\Models;

use App\Core\Model;

class FormTemplate extends Model
{
    public function all(): array
    {
        $s = $this->db->query('SELECT ft.*, v.version_number AS active_version_number FROM form_templates ft LEFT JOIN form_template_versions v ON v.id=ft.active_version_id ORDER BY ft.name');
        return $s->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $s = $this->db->prepare('SELECT * FROM form_templates WHERE slug=? LIMIT 1');
        $s->execute([$slug]);
        return $s->fetch() ?: null;
    }

    public function active(string $slug): ?array
    {
        $s = $this->db->prepare('SELECT ft.*, v.version_number, v.definition, v.status AS version_status FROM form_templates ft JOIN form_template_versions v ON v.id=ft.active_version_id WHERE ft.slug=? LIMIT 1');
        $s->execute([$slug]);
        $row = $s->fetch();
        if (!$row) return null;
        $row['definition'] = $this->decode($row['definition']);
        return $row;
    }

    public function versions(string $slug): array
    {
        $s = $this->db->prepare('SELECT v.*, ft.name FROM form_template_versions v JOIN form_templates ft ON ft.id=v.form_template_id WHERE ft.slug=? ORDER BY v.version_number DESC');
        $s->execute([$slug]);
        $rows = $s->fetchAll();
        foreach ($rows as &$row) $row['definition'] = $this->decode($row['definition']);
        return $rows;
    }

    public function version(int $id): ?array
    {
        $s = $this->db->prepare('SELECT v.*, ft.slug, ft.name FROM form_template_versions v JOIN form_templates ft ON ft.id=v.form_template_id WHERE v.id=? LIMIT 1');
        $s->execute([$id]);
        $row = $s->fetch();
        if (!$row) return null;
        $row['definition'] = $this->decode($row['definition']);
        return $row;
    }

    public function createVersion(string $slug, array $definition, ?int $userId, string $summary = ''): int
    {
        $this->db->beginTransaction();
        try {
            $template = $this->findBySlug($slug);
            if (!$template) throw new \RuntimeException('Form template not found');

            $s = $this->db->prepare('SELECT COALESCE(MAX(version_number),0)+1 FROM form_template_versions WHERE form_template_id=?');
            $s->execute([(int)$template['id']]);
            $number = (int)$s->fetchColumn();

            $s = $this->db->prepare('INSERT INTO form_template_versions (form_template_id,version_number,definition,status,change_summary,created_by) VALUES (?,?,?,?,?,?)');
            $s->execute([(int)$template['id'], $number, json_encode($definition, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), 'published', trim($summary) ?: 'Form definition updated', $userId]);
            $id = (int)$this->db->lastInsertId();

            $s = $this->db->prepare("UPDATE form_template_versions SET status='archived' WHERE form_template_id=? AND id<>? AND status='published'");
            $s->execute([(int)$template['id'], $id]);
            $s = $this->db->prepare('UPDATE form_templates SET active_version_id=? WHERE id=?');
            $s->execute([$id, (int)$template['id']]);

            $this->db->commit();
            return $id;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function decode($json): array
    {
        if (is_array($json)) return $json;
        $decoded = json_decode((string)$json, true);
        return is_array($decoded) ? $decoded : [];
    }
}
