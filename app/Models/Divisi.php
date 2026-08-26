<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class Divisi extends Model {
    protected $table = 'divisi';

    public function getAktif() {
        $sql = "SELECT d.*, 
                (SELECT COUNT(*) FROM pengajuan p WHERE p.divisi_id_final = d.id AND p.status IN ('diterima', 'sedang_magang')) as terisi
                FROM {$this->table} d 
                WHERE d.status = 'aktif'";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
