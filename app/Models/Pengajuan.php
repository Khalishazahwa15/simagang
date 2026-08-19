<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class Pengajuan extends Model {
    protected $table = 'pengajuan';

    public function create($userId, $nomorPengajuan, $divisiPreferensi, $tanggalMulai, $tanggalSelesai) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, nomor_pengajuan, divisi_id_preferensi, tanggal_mulai_rencana, tanggal_selesai_rencana, status) VALUES (?, ?, ?, ?, ?, 'diajukan')");
        $stmt->execute([$userId, $nomorPengajuan, $divisiPreferensi, $tanggalMulai, $tanggalSelesai]);
        return $this->db->lastInsertId();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getActiveByDivisi($divisiId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE divisi_id_final = ? AND status IN ('diterima', 'sedang_magang')");
        $stmt->execute([$divisiId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['count'] ?? 0;
    }
}
