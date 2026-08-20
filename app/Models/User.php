<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function catatWaktuLogin($userId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET last_login_at = NOW() WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    public function create($nama, $email, $passwordHash, $role = 'mahasiswa') {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $passwordHash, $role]);
        return $this->lastId();
    }
}
