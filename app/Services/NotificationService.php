<?php
namespace App\Services;

use App\Core\Database;

class NotificationService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $pengajuanId, $pesan) {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, pengajuan_id, pesan) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $pengajuanId, $pesan]);
    }

    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = FALSE");
        $stmt->execute([$userId]);
        return $stmt->fetch()['count'];
    }

    public function getByUser($userId, $limit = 10) {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        // PDO needs limit as INT when using prepare, or bindParam with PDO::PARAM_INT
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function markAllAsRead($userId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE");
        return $stmt->execute([$userId]);
    }
}
