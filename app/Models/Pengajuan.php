<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class Pengajuan extends Model {
    protected $table = 'pengajuan';

    public function create($userId, $nomorPengajuan, $divisiPreferensi, $tanggalMulai, $tanggalSelesai) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, nomor_pengajuan, divisi_id_preferensi, tanggal_mulai_rencana, tanggal_selesai_rencana, status) VALUES (?, ?, ?, ?, ?, 'diajukan')");
        $stmt->execute([$userId, $nomorPengajuan, $divisiPreferensi, $tanggalMulai, $tanggalSelesai]);
        return $this->lastId();
    }

    /**
     * Pengajuan beserta identitas pendaftarnya.
     * findById() bawaan hanya mengembalikan kolom tabel pengajuan, sehingga
     * halaman detail Sekretariat tampil tanpa data mahasiswa sama sekali.
     */
    public function findDetailById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*,
                   u.nama  AS mahasiswa_nama,
                   u.email AS mahasiswa_email,
                   mp.nim,
                   mp.tempat_lahir,
                   mp.tanggal_lahir,
                   mp.universitas,
                   mp.fakultas,
                   mp.program_studi AS jurusan,
                   mp.semester,
                   mp.nomor_hp AS telepon,
                   mp.alamat,
                   dp.nama_divisi AS nama_divisi,
                   dt.nama_divisi AS nama_divisi_tawaran,
                   df.nama_divisi AS nama_divisi_final
            FROM {$this->table} p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi dp ON p.divisi_id_preferensi = dp.id
            LEFT JOIN divisi dt ON p.divisi_id_tawaran = dt.id
            LEFT JOIN divisi df ON p.divisi_id_final = df.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateNomorPengajuan($id, $nomorPengajuan) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nomor_pengajuan = ? WHERE id = ?");
        return $stmt->execute([$nomorPengajuan, $id]);
    }

    /**
     * Jumlah pengajuan per status dalam satu kueri.
     * Dasbor sebelumnya menjalankan satu COUNT terpisah untuk tiap status,
     * padahal seluruh angkanya ada di hasil GROUP BY yang sama.
     */
    public function hitungPerStatus() {
        $stmt = $this->db->query("SELECT status, COUNT(*) AS jumlah FROM {$this->table} GROUP BY status");

        $hasil = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $baris) {
            $hasil[$baris['status']] = (int)$baris['jumlah'];
        }

        return $hasil;
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
