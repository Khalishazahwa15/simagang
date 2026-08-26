<?php
namespace App\Services;

use App\Models\Pengajuan;
use App\Models\Divisi;
use App\Core\Session;

class PengajuanService {
    // Batas periode magang. Nilainya sengaja longgar; tujuannya menolak isian
    // yang mustahil, bukan mengatur kebijakan Bappeda.
    const DURASI_MIN_HARI = 7;
    const DURASI_MAKS_HARI = 366;
    const MULAI_MAKS_HARI_KE_DEPAN = 366;

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

    /**
     * Periode aktual ditetapkan Sekretariat dan boleh dimulai hari ini atau
     * di masa lalu (misalnya berkas diproses terlambat), tetapi urutannya
     * tetap harus benar.
     */
    private function periksaPeriodeAktual($tanggalMulai, $tanggalSelesai) {
        $mulai = \DateTime::createFromFormat('Y-m-d', (string)$tanggalMulai);
        $selesai = \DateTime::createFromFormat('Y-m-d', (string)$tanggalSelesai);

        if (!$mulai || !$selesai) {
            throw new \Exception("Tanggal mulai dan tanggal selesai aktual harus berupa tanggal yang sah.");
        }

        if ($selesai <= $mulai) {
            throw new \Exception("Tanggal selesai aktual harus setelah tanggal mulai aktual.");
        }

        if ((int)$mulai->diff($selesai)->days > self::DURASI_MAKS_HARI) {
            throw new \Exception("Periode magang maksimal satu tahun.");
        }
    }

    /**
     * Pastikan periode yang diminta masuk akal sebelum apa pun disimpan.
     */
    private function periksaPeriode($tanggalMulai, $tanggalSelesai) {
        $mulai = \DateTime::createFromFormat('Y-m-d', (string)$tanggalMulai);
        $selesai = \DateTime::createFromFormat('Y-m-d', (string)$tanggalSelesai);

        if (!$mulai || $mulai->format('Y-m-d') !== $tanggalMulai
            || !$selesai || $selesai->format('Y-m-d') !== $tanggalSelesai) {
            throw new \Exception("Tanggal mulai dan tanggal selesai harus berupa tanggal yang sah.");
        }

        $hariIni = new \DateTime('today');

        if ($mulai < $hariIni) {
            throw new \Exception("Tanggal mulai tidak boleh berada di masa lalu.");
        }

        if ($selesai <= $mulai) {
            throw new \Exception("Tanggal selesai harus setelah tanggal mulai.");
        }

        $durasi = (int)$hariIni->diff($mulai)->days;
        if ($mulai > $hariIni && $durasi > self::MULAI_MAKS_HARI_KE_DEPAN) {
            throw new \Exception("Tanggal mulai terlalu jauh ke depan. Maksimal satu tahun dari hari ini.");
        }

        $panjang = (int)$mulai->diff($selesai)->days;
        if ($panjang < self::DURASI_MIN_HARI) {
            throw new \Exception("Periode magang minimal " . self::DURASI_MIN_HARI . " hari.");
        }
        if ($panjang > self::DURASI_MAKS_HARI) {
            throw new \Exception("Periode magang maksimal satu tahun.");
        }
    }

    public function createPengajuan($userId, $divisiPreferensi, $tanggalMulai, $tanggalSelesai, $files) {
        // Profil adalah prasyarat formulir, jadi diperiksa lebih dulu daripada
        // isi formulirnya sendiri.
        $profilKurang = (new \App\Models\MahasiswaProfile())->fieldBelumLengkap($userId);
        if (!empty($profilKurang)) {
            throw new \Exception("Lengkapi profil Anda lebih dulu. Belum terisi: " . implode(', ', $profilKurang) . ".");
        }

        $this->periksaPeriode($tanggalMulai, $tanggalSelesai);

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
            // Semua status final melepas kunci, bukan hanya ditolak dan selesai.
            if (!in_array($latest['status'], $this->statusService->statusFinal())) {
                throw new \Exception("Anda masih memiliki pengajuan yang sedang diproses. Pengajuan baru dapat dibuat setelah pengajuan tersebut selesai, ditolak, atau dibatalkan.");
            }
        }

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
            // Nomor pengajuan mengikuti id dari basis data agar dijamin unik.
            // Baris dibuat dengan nomor sementara lebih dulu karena kolomnya NOT NULL UNIQUE.
            $nomorSementara = 'TMP-' . bin2hex(random_bytes(12));
            $pengajuanId = $this->pengajuanModel->create($userId, $nomorSementara, $divisiPreferensi, $tanggalMulai, $tanggalSelesai);

            $nomorPengajuan = 'PM-' . date('Ymd') . '-' . str_pad($pengajuanId, 6, '0', STR_PAD_LEFT);
            $this->pengajuanModel->updateNomorPengajuan($pengajuanId, $nomorPengajuan);

            // Upload files (handled safely in DokumenService via its own local transactions, but since we share DB connection, it participates in this transaction)
            foreach (['surat_lamaran', 'cv', 'transkrip'] as $jenis) {
                $this->dokumenService->uploadDokumen($pengajuanId, $nomorPengajuan, $jenis, $files[$jenis]);
            }

            if (isset($files['tambahan']) && $files['tambahan']['error'] === UPLOAD_ERR_OK) {
                $this->dokumenService->uploadDokumen($pengajuanId, $nomorPengajuan, 'tambahan', $files['tambahan']);
            }

            $this->auditService->log('create_pengajuan', 'pengajuan', $pengajuanId, "Mengajukan permohonan magang baru: {$nomorPengajuan}");

            $this->pengajuanModel->commit();

            // Riwayat dan pemberitahuan dicatat setelah transaksi berhasil,
            // supaya kegagalan pengiriman email tidak membatalkan pengajuan.
            try {
                $this->statusService->catatStatusAwal($pengajuanId);
            } catch (\Throwable $e) {
                \App\Core\Logger::error('STATUS AWAL', $e->getMessage());
            }

            return $pengajuanId;

        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            // Note: DokumenService's rollback will clean up its own files via catch block if it throws
            throw $e;
        }
    }

    public function menetapkanDiterima($pengajuanId, $divisiIdFinal, $pembinaLapangan, $tanggalMulaiAktual, $tanggalSelesaiAktual, $catatan) {
        if (\App\Core\Auth::role() !== 'sekretariat' && \App\Core\Auth::role() !== 'admin') {
            throw new \Exception("Akses Ditolak. Hanya Sekretariat atau Admin yang dapat menetapkan keputusan.");
        }

        $pengajuan = $this->pengajuanModel->findById($pengajuanId);
        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        if ($pengajuan['status'] !== 'dalam_verifikasi') {
            throw new \Exception("Keputusan hanya dapat ditetapkan saat berkas sedang dalam verifikasi. Status saat ini: '{$pengajuan['status']}'.");
        }

        $this->periksaPeriodeAktual($tanggalMulaiAktual, $tanggalSelesaiAktual);

        // Penempatan mengikuti preferensi mahasiswa. Nilai dari formulir sengaja
        // diabaikan supaya penempatan tidak bisa diubah diam-diam lewat POST;
        // untuk divisi lain, alurnya wajib lewat tawaran yang disetujui mahasiswa.
        $divisiIdFinal = $pengajuan['divisi_id_preferensi'];
        if (!$divisiIdFinal) {
            throw new \Exception("Pengajuan ini tidak memiliki divisi preferensi, sehingga tidak dapat langsung diterima.");
        }

        $this->pengajuanModel->beginTransaction();
        try {
            // Update fields manually
            $stmt = $this->pengajuanModel->getDb()->prepare("UPDATE pengajuan SET divisi_id_final = ?, pembina_lapangan = ?, tanggal_mulai_aktual = ?, tanggal_selesai_aktual = ?, catatan_verifikasi = ?, diputuskan_oleh = ?, diputuskan_at = NOW() WHERE id = ?");
            $stmt->execute([$divisiIdFinal, $pembinaLapangan, $tanggalMulaiAktual, $tanggalSelesaiAktual, $catatan, \App\Core\Auth::id(), $pengajuanId]);

            // Transition state
            $this->statusService->updateStatus($pengajuanId, $pengajuan['status'], 'diterima', "Diterima dan ditempatkan.");

            $this->pengajuanModel->commit();
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
    }

    public function menetapkanDitolak($pengajuanId, $alasanPenolakan, $catatan) {
        if (\App\Core\Auth::role() !== 'sekretariat' && \App\Core\Auth::role() !== 'admin') {
            throw new \Exception("Akses Ditolak. Hanya Sekretariat atau Admin yang dapat menetapkan keputusan.");
        }

        if (empty(trim($alasanPenolakan))) {
            throw new \Exception("Alasan penolakan wajib diisi.");
        }

        $pengajuan = $this->pengajuanModel->findById($pengajuanId);
        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        if ($pengajuan['status'] !== 'dalam_verifikasi') {
            throw new \Exception("Keputusan hanya dapat ditetapkan saat berkas sedang dalam verifikasi. Status saat ini: '{$pengajuan['status']}'.");
        }

        $this->pengajuanModel->beginTransaction();
        try {
            $stmt = $this->pengajuanModel->getDb()->prepare("UPDATE pengajuan SET alasan_penolakan = ?, catatan_verifikasi = ?, diputuskan_oleh = ?, diputuskan_at = NOW() WHERE id = ?");
            $stmt->execute([$alasanPenolakan, $catatan, \App\Core\Auth::id(), $pengajuanId]);

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

        if ($divisiTawaranId == $pengajuan['divisi_id_preferensi']) {
            throw new \Exception("Divisi tawaran harus berbeda dari preferensi mahasiswa. Bila memang divisi tersebut yang dituju, gunakan keputusan Terima.");
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

        if ($pengajuan['user_id'] != $userIdMahasiswa || $pengajuan['user_id'] != \App\Core\Auth::id()) {
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
