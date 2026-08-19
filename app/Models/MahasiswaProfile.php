<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class MahasiswaProfile extends Model {
    protected $table = 'mahasiswa_profiles';

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($userId, $nim, $universitas, $fakultas, $programStudi, $semester, $nomorHp, $alamat) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, nim, universitas, fakultas, program_studi, semester, nomor_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $nim, $universitas, $fakultas, $programStudi, $semester, $nomorHp, $alamat]);
        return $this->db->lastInsertId();
    }
}
