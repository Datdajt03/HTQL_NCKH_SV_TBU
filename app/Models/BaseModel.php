<?php
namespace App\Models;

use App\Config\Database;
use PDO;

abstract class BaseModel {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    protected function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    protected function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    protected function execute(string $sql, array $params = []): bool {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    protected function lastInsertId(): string {
        return $this->db->lastInsertId();
    }
}
