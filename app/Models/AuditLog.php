<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class AuditLog extends Model {
    protected $table = 'audit_logs';

    public function create($userId, $action, $entity, $entityId, $details = null, $ipAddress = null) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, action, entity, entity_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $action, $entity, $entityId, $details, $ipAddress]);
    }
}
