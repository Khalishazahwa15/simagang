<?php
namespace App\Services;

use App\Models\Pengajuan;
use App\Models\StatusHistory;
use App\Core\Session;
use App\Services\MailService;

class StatusService {
    private $pengajuanModel;
    private $statusHistoryModel;
    private $auditService;
    private $notificationService;

    // Label status untuk pemberitahuan ke mahasiswa
    const LABEL_STATUS = [
        'diajukan' => 'Diajukan',
        'dalam_verifikasi' => 'Sedang Diverifikasi',
        'revisi' => 'Perlu Revisi',
        'menunggu_konfirmasi_tawaran' => 'Menunggu Konfirmasi Anda',
        'menunggu_finalisasi_sekretariat' => 'Menunggu Finalisasi Sekretariat',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
        'dibatalkan_oleh_mahasiswa' => 'Dibatalkan',
        'sedang_magang' => 'Sedang Magang',
        'selesai' => 'Selesai',
        'mengundurkan_diri' => 'Mengundurkan Diri',
    ];

    // PRD v4.1 State Machine
    private $allowedTransitions = [
        'draft' => ['diajukan', 'dibatalkan_oleh_mahasiswa'],
        'diajukan' => ['dalam_verifikasi', 'revisi', 'diterima', 'ditolak', 'dibatalkan_oleh_mahasiswa'],
        'dalam_verifikasi' => ['revisi', 'diterima', 'ditolak', 'menunggu_konfirmasi_tawaran'],
        // 'dalam_verifikasi' diperlukan saat mahasiswa mengunggah perbaikan:
        // berkasnya kembali ke tangan Sekretariat yang meminta revisi, bukan
        // ke antrean awal.
        'revisi' => ['diajukan', 'dalam_verifikasi', 'dibatalkan_oleh_mahasiswa'],
        'menunggu_konfirmasi_tawaran' => ['menunggu_finalisasi_sekretariat', 'dibatalkan_oleh_mahasiswa'],
        'menunggu_finalisasi_sekretariat' => ['diterima', 'ditolak'],
        'diterima' => ['sedang_magang', 'mengundurkan_diri'],
        'sedang_magang' => ['selesai', 'mengundurkan_diri'],
        'ditolak' => [],
        'selesai' => [],
        'mengundurkan_diri' => [],
        'dibatalkan_oleh_mahasiswa' => []
    ];

    public function __construct() {
        $this->pengajuanModel = new Pengajuan();
        $this->statusHistoryModel = new StatusHistory();
        $this->auditService = new AuditService();
        $this->notificationService = new NotificationService();
    }

    /**
     * Status yang tidak punya transisi keluar. Diturunkan dari peta transisi
     * agar tidak perlu dirawat sebagai daftar kedua yang bisa ketinggalan.
     */
    public function statusFinal() {
        $final = [];
        foreach ($this->allowedTransitions as $status => $tujuan) {
            if (empty($tujuan)) {
                $final[] = $status;
            }
        }
        return $final;
    }

    public function updateStatus($pengajuanId, $statusAwal, $statusBaru, $catatan = null) {
        if (!isset($this->allowedTransitions[$statusAwal]) || !in_array($statusBaru, $this->allowedTransitions[$statusAwal])) {
            throw new \Exception("Transisi status ilegal dari '{$statusAwal}' ke '{$statusBaru}'.");
        }

        $userId = \App\Core\Auth::id();

        $this->pengajuanModel->beginTransaction();
        try {
            $this->pengajuanModel->updateStatus($pengajuanId, $statusBaru);
            $this->statusHistoryModel->create($pengajuanId, $statusAwal, $statusBaru, $userId, $catatan);
            $this->auditService->log('update_status', 'pengajuan', $pengajuanId, "Mengubah status menjadi {$statusBaru}");
            
            $this->pengajuanModel->commit();
            
            // Create Notification outside of the transaction block
            try {
                $db = \App\Core\Database::getInstance()->getConnection();
                // Join users to get nama for notifications
                $stmt = $db->prepare("
                    SELECT p.*, u.nama, u.email
                    FROM pengajuan p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.id = ?
                ");
                $stmt->execute([$pengajuanId]);
                $pengajuan = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($pengajuan) {
                    $this->dispatchNotifications($statusBaru, $catatan, $pengajuanId, $pengajuan);
                }
            } catch (\Exception $notifEx) {
                // Log failure but do not rollback the status transition
                \App\Core\Logger::error('NOTIFIKASI GAGAL', $notifEx->getMessage());
            }
            
            return true;
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
    }
    
    /**
     * Catat status pembuka sebuah pengajuan sekaligus sebarkan pemberitahuannya.
     * Dipanggil setelah baris pengajuan dibuat, karena pembuatan tidak melewati
     * updateStatus() yang biasanya menangani riwayat dan notifikasi.
     */
    public function catatStatusAwal($pengajuanId, $status = 'diajukan', $catatan = 'Pengajuan dikirim oleh mahasiswa.') {
        $db = \App\Core\Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT p.*, u.nama, u.email
            FROM pengajuan p
            JOIN users u ON p.user_id = u.id
            WHERE p.id = ?
        ");
        $stmt->execute([$pengajuanId]);
        $pengajuan = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$pengajuan) {
            return;
        }

        $this->statusHistoryModel->create($pengajuanId, 'draft', $status, \App\Core\Auth::id(), $catatan);
        $this->dispatchNotifications($status, $catatan, $pengajuanId, $pengajuan);
    }

    private function dispatchNotifications($status, $catatan, $pengajuanId, $pengajuan) {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        // Helper untuk mendapatkan ID seluruh sekretariat
        $getSekretariatIds = function() use ($db) {
            $stmt = $db->query("SELECT id FROM users WHERE role = 'sekretariat' AND status = 'aktif'");
            return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        };

        $mahasiswaId = $pengajuan['user_id'];
        $notifs = []; // Format: ['user_id' => target_id, 'pesan' => pesan_notif]

        switch ($status) {
            case 'diajukan':
                $notifs[] = [
                    'user_id' => $mahasiswaId,
                    'pesan' => "Pengajuan magang Anda sudah masuk dan menunggu antrean pemeriksaan Sekretariat.",
                    'email' => true,
                ];
                $pesan = "Pengajuan baru diterima dari " . ($pengajuan['nama'] ?? 'Mahasiswa') . " (No: {$pengajuan['nomor_pengajuan']}).";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'dalam_verifikasi':
                $notifs[] = [
                    'user_id' => $mahasiswaId,
                    'pesan' => "Berkas pengajuan Anda sedang diperiksa oleh Sekretariat.",
                    'email' => true,
                ];
                $pesan = "Pengajuan {$pengajuan['nomor_pengajuan']} sedang dalam tahap verifikasi.";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'revisi':
                $pesan = "Pengajuan Anda perlu direvisi. Catatan: " . ($catatan ?: 'Silakan periksa dokumen pengajuan.');
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan, 'email' => true];
                break;
            case 'menunggu_konfirmasi_tawaran':
                $pesan = "Sekretariat telah memberikan tawaran divisi alternatif. Silakan cek detail pengajuan Anda dan berikan konfirmasi.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan, 'email' => true];
                break;
            case 'menunggu_finalisasi_sekretariat':
                $notifs[] = [
                    'user_id' => $mahasiswaId,
                    'pesan' => "Persetujuan Anda atas tawaran divisi sudah tercatat. Menunggu penetapan akhir dari Sekretariat.",
                    'email' => true,
                ];
                $pesan = "Mahasiswa telah menyetujui tawaran divisi. Menunggu finalisasi dari Sekretariat.";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'diterima':
                $pesan = "Selamat! Pengajuan magang Anda telah DITERIMA.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan, 'email' => true];
                break;
            case 'ditolak':
                $pesan = "Pengajuan Anda ditolak. Alasan: " . ($pengajuan['alasan_penolakan'] ?? 'Tidak memenuhi syarat.');
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan, 'email' => true];
                break;
            case 'dibatalkan_oleh_mahasiswa':
                $notifs[] = [
                    'user_id' => $mahasiswaId,
                    'pesan' => "Pengajuan magang Anda telah dibatalkan.",
                    'email' => true,
                ];
                $pesan = "Mahasiswa membatalkan pengajuan (No: {$pengajuan['nomor_pengajuan']}).";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'sedang_magang':
                $pesan = "Masa magang Anda telah dimulai.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan, 'email' => true];
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => "Mahasiswa (No: {$pengajuan['nomor_pengajuan']}) telah memasuki masa magang."];
                }
                break;
            case 'mengundurkan_diri':
                $pesan = "Pengunduran diri Anda telah diverifikasi. Masa magang dinyatakan berakhir.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan, 'email' => true];
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => "Mahasiswa (No: {$pengajuan['nomor_pengajuan']}) mengundurkan diri."];
                }
                break;
            case 'selesai':
                $pesan = "Masa magang Anda telah selesai. Anda dapat mengunduh dokumen akhir jika tersedia.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan, 'email' => true];
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => "Mahasiswa (No: {$pengajuan['nomor_pengajuan']}) telah menyelesaikan masa magang."];
                }
                break;
        }

        // Simpan notifikasi
        foreach ($notifs as $n) {
            $this->notificationService->create($n['user_id'], $pengajuanId, $n['pesan']);
        }

        // Kirim salinan ke email mahasiswa. Kegagalan pengiriman tidak boleh
        // membatalkan perubahan status yang sudah tersimpan.
        foreach ($notifs as $n) {
            if (empty($n['email']) || $n['user_id'] != $mahasiswaId) {
                continue;
            }
            try {
                $this->emailPerubahanStatus($pengajuan, $status, $n['pesan']);
            } catch (\Throwable $e) {
                \App\Core\Logger::error('EMAIL STATUS', $e->getMessage());
            }
            break;
        }
    }

    /**
     * Kirim pemberitahuan perubahan status ke email mahasiswa.
     * Dilewati bila MAIL_NOTIFIKASI di .env bernilai false, berguna saat
     * pengembangan agar tidak membanjiri kotak masuk.
     */
    private function emailPerubahanStatus($pengajuan, $status, $pesan) {
        // Env::get mengubah 'false' menjadi boolean false, jadi nilainya
        // dilewatkan filter_var alih-alih dibandingkan sebagai teks.
        if (!filter_var(\App\Core\Env::get('MAIL_NOTIFIKASI', true), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $tujuan = trim((string)($pengajuan['email'] ?? ''));
        if ($tujuan === '') {
            return;
        }

        $label = self::LABEL_STATUS[$status] ?? ucfirst(str_replace('_', ' ', $status));
        $nomor = $pengajuan['nomor_pengajuan'] ?? '-';
        $nama = $pengajuan['nama'] ?? 'Mahasiswa';
        $tautan = BASE_URL . '/mahasiswa/status';

        $subjek = "[{$nomor}] Status pengajuan magang: {$label}";

        $isiTeks = "Halo {$nama},\n\n"
            . "Status pengajuan magang Anda ({$nomor}) berubah menjadi: {$label}.\n\n"
            . $pesan . "\n\n"
            . "Rincian selengkapnya dapat dilihat di halaman berikut:\n"
            . $tautan . "\n\n"
            . "Email ini dikirim otomatis oleh sistem. Mohon tidak membalas.\n\n"
            . "SIMAGANG Bappeda Provinsi Lampung";

        $isiHtml = '<p>Halo ' . htmlspecialchars($nama) . ',</p>'
            . '<p>Status pengajuan magang Anda (<strong>' . htmlspecialchars($nomor) . '</strong>) '
            . 'berubah menjadi: <strong>' . htmlspecialchars($label) . '</strong>.</p>'
            . '<p>' . htmlspecialchars($pesan) . '</p>'
            . '<p><a href="' . htmlspecialchars($tautan) . '">Lihat rincian pengajuan</a></p>'
            . '<hr>'
            . '<p style="font-size:12px;color:#666">Email ini dikirim otomatis oleh sistem. Mohon tidak membalas.<br>'
            . 'SIMAGANG Bappeda Provinsi Lampung</p>';

        $mailService = new MailService();
        $mailService->kirim($tujuan, $nama, $subjek, $isiHtml, $isiTeks);
    }
}
