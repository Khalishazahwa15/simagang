<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class StatusHistory extends Model {
    protected $table = 'status_history';

    public function create($pengajuanId, $statusAwal, $statusBaru, $changedBy, $catatan = null) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (pengajuan_id, status_awal, status_baru, changed_by, catatan) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$pengajuanId, $statusAwal, $statusBaru, $changedBy, $catatan]);
    }

    public function findByPengajuanId($pengajuanId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE pengajuan_id = ? ORDER BY created_at ASC");
        $stmt->execute([$pengajuanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
