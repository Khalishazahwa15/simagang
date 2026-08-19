<?php
namespace App\Services;

use App\Models\AuditLog;
use App\Core\Session;

class AuditService {
    private $auditModel;

    public function __construct() {
        $this->auditModel = new AuditLog();
    }

    public function log($action, $entity, $entityId, $details = null) {
        $userId = Session::get('user_id'); // Can be null if not logged in
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        
        $this->auditModel->create($userId, $action, $entity, $entityId, $details, $ipAddress);
    }
}
