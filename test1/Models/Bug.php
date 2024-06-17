<?php

namespace Models;

use Database\Database;
use PDO;

class Bug
{
    protected const string TABLE = 'bugs';

    private readonly PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createBug(string $title, string $comment, string $priority, ?int $visitorId): void
    {
        $sql = "
            INSERT INTO " . static::TABLE . "
            (title, comment, priority, visitor_id)
            VALUES
            (?, ?, ?, ?)
        ";

        $query = $this->db->prepare($sql);
        $query->execute([$title, $comment, $priority, $visitorId]);
    }

    public function getAllBugs(): array
    {
        $sql = "SELECT * FROM " . static::TABLE;

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBug(int $id, ?string $status = null, ?string $comment = null): void
    {
        $this->updateStatus($id, $status);
        $this->updateComment($id, $comment);

        $this->changeNotifiedStatusByBugId(0, $id);
    }

    private function updateStatus(int $id, ?string $status = null): void
    {
        if ($status === null) {
            return;
        }

        $sql = "
          UPDATE " . static::TABLE . "
          SET
            status = ?,
            updated_at = ?,
            engineer_id = ?
          WHERE id = ?
        ";
        
        $query = $this->db->prepare($sql);
        $query->execute([$status, date('Y-m-d H:i:s'), $id, $_SESSION['user']['id']]);
    }

    private function updateComment(int $id, ?string $comment = null): void
    {
        if ($comment === null) {
            return;
        }

        $sql = "
          UPDATE " . static::TABLE . "
          SET
            comment = ?,
            updated_at = ?,
            engineer_id = ?
          WHERE id = ?
        ";
        
        $query = $this->db->prepare($sql);
        $query->execute([$comment, date('Y-m-d H:i:s'), $id, $_SESSION['user']['id']]);
    }

    public function getBugUpdates(int $visitorId): array
    {
        $sql = "
            SELECT *
            FROM " . static::TABLE . "
            WHERE visitor_id = ?
              AND updated_at > created_at
              AND notified = '0'
        ";

        $query = $this->db->prepare($sql);
        $query->execute([$visitorId]);

        $this->changeNotifiedStatusByVisitorId(1, $visitorId);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeNotifiedStatusByVisitorId($notified_status, $visitorId): void
    {
        $sql = "
            UPDATE " . static::TABLE . "
            SET notified = ?
            WHERE visitor_id = ?
        ";

        $query = $this->db->prepare($sql);
        $query->execute([$notified_status, $visitorId]);
    }

    public function changeNotifiedStatusByBugId($notified_status, $bugId): void
    {
        $sql = "
            UPDATE " . static::TABLE . "
            SET notified = ?
            WHERE id = ?
        ";

        $query = $this->db->prepare($sql);
        $query->execute([$notified_status, $bugId]);
    }

    public static function textPriority($numericValue): ?string
    {
        $values = [
            1 => 'High',
            2 => 'Medium',
            3 => 'Low'
        ];

        if (!array_key_exists($numericValue, $values)) {
            return null;
        }

        return $values[$numericValue];
    }
}