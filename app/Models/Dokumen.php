<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class Dokumen extends Model {
    protected $table = 'dokumen';

    public function create($pengajuanId, $jenisDokumen, $filePath, $originalFilename, $version, $isCurrent, $uploadedBy) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (pengajuan_id, jenis_dokumen, file_path, original_filename, version, is_current, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$pengajuanId, $jenisDokumen, $filePath, $originalFilename, $version, (int)$isCurrent, $uploadedBy]);
        return $this->lastId();
    }

    public function findCurrentByPengajuanId($pengajuanId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE pengajuan_id = ? AND is_current = TRUE");
        $stmt->execute([$pengajuanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findCurrentByPengajuanIdAndJenis($pengajuanId, $jenisDokumen) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE pengajuan_id = ? AND jenis_dokumen = ? AND is_current = TRUE");
        $stmt->execute([$pengajuanId, $jenisDokumen]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setNotCurrent($pengajuanId, $jenisDokumen) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_current = FALSE WHERE pengajuan_id = ? AND jenis_dokumen = ?");
        return $stmt->execute([$pengajuanId, $jenisDokumen]);
    }
}
