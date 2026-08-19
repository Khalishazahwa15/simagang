<?php
namespace App\Services;

use App\Core\Database;

class LoginThrottleService {
    const MAX_ATTEMPTS = 5;
    const WINDOW_MINUTES = 15;

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Sisa waktu penguncian dalam menit, atau 0 bila belum terkunci.
     * Dihitung per kombinasi email dan alamat IP.
     */
    public function lockedForMinutes($email) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS jumlah,
                   TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(MAX(attempted_at), INTERVAL ? MINUTE)) AS sisa_detik
            FROM login_attempts
            WHERE email = ? AND ip_address = ?
              AND attempted_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)
        ");
        $stmt->execute([self::WINDOW_MINUTES, $email, $this->ip(), self::WINDOW_MINUTES]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row || (int)$row['jumlah'] < self::MAX_ATTEMPTS) {
            return 0;
        }

        return max(1, (int)ceil((int)$row['sisa_detik'] / 60));
    }

    public function recordFailure($email) {
        $stmt = $this->db->prepare("INSERT INTO login_attempts (email, ip_address) VALUES (?, ?)");
        $stmt->execute([$email, $this->ip()]);
    }

    public function clear($email) {
        $stmt = $this->db->prepare("DELETE FROM login_attempts WHERE email = ? AND ip_address = ?");
        $stmt->execute([$email, $this->ip()]);
    }

    private function ip() {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
