<?php
namespace App\Services;

use App\Models\Pengajuan;
use App\Models\Divisi;
use App\Core\Session;

class PengajuanService {
    private $pengajuanModel;
    private $divisiModel;
    private $dokumenService;
    private $statusService;
    private $auditService;

    public function __construct() {
        $this->pengajuanModel = new Pengajuan();
        $this->divisiModel = new Divisi();
        $this->dokumenService = new DokumenService();
        $this->statusService = new StatusService();
        $this->auditService = new AuditService();
    }

    public function createPengajuan($userId, $divisiPreferensi, $tanggalMulai, $tanggalSelesai, $files) {
        // Validate required files
        $requiredFiles = ['surat_lamaran', 'cv', 'transkrip'];
        foreach ($requiredFiles as $req) {
            if (!isset($files[$req]) || $files[$req]['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("Dokumen {$req} wajib diunggah.");
            }
        }

        // Prevent multiple active applications
        $existing = $this->pengajuanModel->findByUserId($userId);
        if (!empty($existing)) {
            $latest = $existing[0];
            if (!in_array($latest['status'], ['ditolak', 'selesai'])) {
                throw new \Exception("Anda masih memiliki pengajuan aktif. Anda baru dapat mengajukan kembali setelah pengajuan sebelumnya ditolak atau selesai.");
            }
        }

        // Generate Nomor Pengajuan
        $nomorPengajuan = 'PM-' . date('Ymd') . '-' . rand(1000, 9999);

        // Transaction handling for creating pengajuan and uploading files
        $this->pengajuanModel->beginTransaction();
        try {
            // Check capacity (Warning only, no throw)
            $divisi = $this->divisiModel->findById($divisiPreferensi);
            if ($divisi) {
                $activeCount = $this->pengajuanModel->getActiveByDivisi($divisiPreferensi);
                if ($activeCount >= $divisi['kapasitas']) {
                    Session::setFlash('warning', "Kapasitas Divisi {$divisi['nama_divisi']} sedang penuh. Anda tetap dapat mengajukan, namun pertimbangkan ini.");
                }
            }

            // Create pengajuan (Status: diajukan initially since it's fully submitted, or draft then diajukan. Based on AC, straight to diajukan)
            $pengajuanId = $this->pengajuanModel->create($userId, $nomorPengajuan, $divisiPreferensi, $tanggalMulai, $tanggalSelesai);

            // Upload files (handled safely in DokumenService via its own local transactions, but since we share DB connection, it participates in this transaction)
            foreach (['surat_lamaran', 'cv', 'transkrip'] as $jenis) {
                $this->dokumenService->uploadDokumen($pengajuanId, $nomorPengajuan, $jenis, $files[$jenis]);
            }

            if (isset($files['tambahan']) && $files['tambahan']['error'] === UPLOAD_ERR_OK) {
                $this->dokumenService->uploadDokumen($pengajuanId, $nomorPengajuan, 'tambahan', $files['tambahan']);
            }

            $this->auditService->log('create_pengajuan', 'pengajuan', $pengajuanId, "Mengajukan permohonan magang baru: {$nomorPengajuan}");

            $this->pengajuanModel->commit();
            return $pengajuanId;

        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            // Note: DokumenService's rollback will clean up its own files via catch block if it throws
            throw $e;
        }
    }

    public function menetapkanDiterima($pengajuanId, $divisiIdFinal, $pembinaLapangan, $tanggalMulaiAktual, $tanggalSelesaiAktual, $catatan) {
        // Enforce RBAC
        if (\App\Core\Auth::role() !== 'sekretariat') {
            throw new \Exception("Akses Ditolak. Hanya Sekretariat yang dapat menetapkan keputusan.");
        }

        $pengajuan = $this->pengajuanModel->findById($pengajuanId);
        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        $this->pengajuanModel->beginTransaction();
        try {
            // Update fields manually
            $stmt = $this->pengajuanModel->getDb()->prepare("UPDATE pengajuan SET divisi_id_final = ?, pembina_lapangan = ?, tanggal_mulai_aktual = ?, tanggal_selesai_aktual = ?, catatan_verifikasi = ?, diputuskan_oleh = ?, diputuskan_at = NOW() WHERE id = ?");
            $stmt->execute([$divisiIdFinal, $pembinaLapangan, $tanggalMulaiAktual, $tanggalSelesaiAktual, $catatan, Session::get('user_id'), $pengajuanId]);

            // Transition state
            $this->statusService->updateStatus($pengajuanId, $pengajuan['status'], 'diterima', "Diterima dan ditempatkan.");

            $this->pengajuanModel->commit();
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
    }

    public function menetapkanDitolak($pengajuanId, $alasanPenolakan, $catatan) {
        // Enforce RBAC
        if (\App\Core\Auth::role() !== 'sekretariat') {
            throw new \Exception("Akses Ditolak. Hanya Sekretariat yang dapat menetapkan keputusan.");
        }

        if (empty(trim($alasanPenolakan))) {
            throw new \Exception("Alasan penolakan wajib diisi.");
        }

        $pengajuan = $this->pengajuanModel->findById($pengajuanId);
        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        $this->pengajuanModel->beginTransaction();
        try {
            $stmt = $this->pengajuanModel->getDb()->prepare("UPDATE pengajuan SET alasan_penolakan = ?, catatan_verifikasi = ?, diputuskan_oleh = ?, diputuskan_at = NOW() WHERE id = ?");
            $stmt->execute([$alasanPenolakan, $catatan, Session::get('user_id'), $pengajuanId]);

            // Transition state
            $this->statusService->updateStatus($pengajuanId, $pengajuan['status'], 'ditolak', $alasanPenolakan);

            $this->pengajuanModel->commit();
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
    }

    public function menawarkanDivisi($pengajuanId, $divisiTawaranId, $userIdSekretariat) {
        $role = \App\Core\Auth::role();
        if ($role !== 'sekretariat' && $role !== 'admin') {
            throw new \Exception("Akses Ditolak. Hanya Sekretariat atau Super Admin yang dapat menawarkan divisi.");
        }

        $pengajuan = $this->pengajuanModel->findById($pengajuanId);
        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        if ($pengajuan['status'] !== 'dalam_verifikasi') {
            throw new \Exception("Tawaran divisi hanya dapat dilakukan pada status 'dalam_verifikasi'.");
        }

        $divisi = $this->divisiModel->findById($divisiTawaranId);
        if (!$divisi || $divisi['status'] !== 'aktif') {
            throw new \Exception("Divisi tawaran tidak valid atau tidak aktif.");
        }

        $this->pengajuanModel->beginTransaction();
        try {
            $stmt = $this->pengajuanModel->getDb()->prepare("UPDATE pengajuan SET divisi_id_tawaran = ? WHERE id = ?");
            $stmt->execute([$divisiTawaranId, $pengajuanId]);

            $this->statusService->updateStatus($pengajuanId, $pengajuan['status'], 'menunggu_konfirmasi_tawaran', "Ditawarkan divisi alternatif: {$divisi['nama_divisi']}");
            
            $this->auditService->log('tawarkan_divisi', 'pengajuan', $pengajuanId, "Menawarkan divisi alternatif ID: {$divisiTawaranId}");

            $this->pengajuanModel->commit();
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
    }

    public function responTawaran($pengajuanId, $userIdMahasiswa, $terima) {
        $role = \App\Core\Auth::role();
        if ($role !== 'mahasiswa') {
            throw new \Exception("Akses Ditolak. Hanya Mahasiswa yang dapat merespons tawaran.");
        }

        $pengajuan = $this->pengajuanModel->findById($pengajuanId);
        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        if ($pengajuan['user_id'] != $userIdMahasiswa || $pengajuan['user_id'] != Session::get('user_id')) {
            throw new \Exception("Akses Ditolak. Anda tidak dapat merespons tawaran milik orang lain.");
        }

        if ($pengajuan['status'] !== 'menunggu_konfirmasi_tawaran') {
            throw new \Exception("Respon tawaran hanya dapat dilakukan pada status 'menunggu_konfirmasi_tawaran'.");
        }

        if (!$pengajuan['divisi_id_tawaran']) {
            throw new \Exception("Data tawaran divisi tidak ditemukan.");
        }

        $statusBaru = $terima ? 'menunggu_finalisasi_sekretariat' : 'dibatalkan_oleh_mahasiswa';
        $catatan = $terima ? "Mahasiswa menerima tawaran divisi." : "Mahasiswa menolak tawaran divisi.";
        $auditAction = $terima ? 'respon_tawaran_terima' : 'respon_tawaran_tolak';

        $this->pengajuanModel->beginTransaction();
        try {
            $this->statusService->updateStatus($pengajuanId, $pengajuan['status'], $statusBaru, $catatan);
            $this->auditService->log($auditAction, 'pengajuan', $pengajuanId, $catatan);
            $this->pengajuanModel->commit();
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
    }

    public function finalisasiTawaran($pengajuanId, $userIdSekretariat) {
        $role = \App\Core\Auth::role();
        if ($role !== 'sekretariat' && $role !== 'admin') {
            throw new \Exception("Akses Ditolak. Hanya Sekretariat atau Super Admin yang dapat melakukan finalisasi.");
        }

        $pengajuan = $this->pengajuanModel->findById($pengajuanId);
        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        if ($pengajuan['status'] !== 'menunggu_finalisasi_sekretariat') {
            throw new \Exception("Finalisasi hanya dapat dilakukan pada status 'menunggu_finalisasi_sekretariat'.");
        }

        if (!$pengajuan['divisi_id_tawaran']) {
            throw new \Exception("Data tawaran divisi tidak ditemukan untuk difinalisasi.");
        }

        $this->pengajuanModel->beginTransaction();
        try {
            $stmt = $this->pengajuanModel->getDb()->prepare("UPDATE pengajuan SET divisi_id_final = divisi_id_tawaran, diputuskan_oleh = ?, diputuskan_at = NOW() WHERE id = ?");
            $stmt->execute([$userIdSekretariat, $pengajuanId]);

            $this->statusService->updateStatus($pengajuanId, $pengajuan['status'], 'diterima', "Penempatan final telah disetujui Sekretariat.");
            $this->auditService->log('finalisasi_tawaran', 'pengajuan', $pengajuanId, "Finalisasi penempatan ke Divisi ID: {$pengajuan['divisi_id_tawaran']}");

            $this->pengajuanModel->commit();
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
    }

    public function getPengajuanMahasiswa($userId) {
        return $this->pengajuanModel->findByUserId($userId);
    }
}
