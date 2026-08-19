<?php
namespace App\Services;

use App\Models\Pengajuan;
use App\Models\StatusHistory;
use App\Core\Session;

class StatusService {
    private $pengajuanModel;
    private $statusHistoryModel;
    private $auditService;
    private $notificationService;

    // PRD v4.1 State Machine
    private $allowedTransitions = [
        'draft' => ['diajukan', 'dibatalkan', 'dibatalkan_oleh_mahasiswa'], 
        'diajukan' => ['dalam_verifikasi', 'revisi', 'diterima', 'ditolak', 'dibatalkan', 'dibatalkan_oleh_mahasiswa'],
        'dalam_verifikasi' => ['revisi', 'diterima', 'ditolak', 'menunggu_konfirmasi_tawaran'],
        'revisi' => ['diajukan', 'dibatalkan', 'dibatalkan_oleh_mahasiswa'],
        'menunggu_konfirmasi_tawaran' => ['menunggu_finalisasi_sekretariat', 'dibatalkan_oleh_mahasiswa'],
        'menunggu_finalisasi_sekretariat' => ['diterima', 'ditolak'],
        'diterima' => ['sedang_magang', 'mengundurkan_diri'],
        'sedang_magang' => ['selesai', 'mengundurkan_diri'],
        'ditolak' => [],
        'selesai' => [],
        'mengundurkan_diri' => [],
        'dibatalkan' => [],
        'dibatalkan_oleh_mahasiswa' => []
    ];

    public function __construct() {
        $this->pengajuanModel = new Pengajuan();
        $this->statusHistoryModel = new StatusHistory();
        $this->auditService = new AuditService();
        $this->notificationService = new NotificationService();
    }

    public function updateStatus($pengajuanId, $statusAwal, $statusBaru, $catatan = null) {
        if (!isset($this->allowedTransitions[$statusAwal]) || !in_array($statusBaru, $this->allowedTransitions[$statusAwal])) {
            throw new \Exception("Transisi status ilegal dari '{$statusAwal}' ke '{$statusBaru}'.");
        }

        $userId = Session::get('user_id');

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
                    SELECT p.*, u.nama 
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
                $logDir = ROOT_PATH . '/storage/logs';
                if (!is_dir($logDir)) {
                    mkdir($logDir, 0777, true);
                }
                $errorMessage = "[" . date('Y-m-d H:i:s') . "] NOTIFIKASI GAGAL: " . $notifEx->getMessage() . "\n";
                file_put_contents($logDir . '/error.log', $errorMessage, FILE_APPEND);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->pengajuanModel->rollBack();
            throw $e;
        }
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
                $pesan = "Pengajuan baru diterima dari " . ($pengajuan['nama'] ?? 'Mahasiswa') . " (No: {$pengajuan['nomor_pengajuan']}).";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'dalam_verifikasi':
                $pesan = "Pengajuan {$pengajuan['nomor_pengajuan']} sedang dalam tahap verifikasi.";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'revisi':
                $pesan = "Pengajuan Anda perlu direvisi. Catatan: " . ($catatan ?: 'Silakan periksa dokumen pengajuan.');
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan];
                break;
            case 'menunggu_konfirmasi_tawaran':
                $pesan = "Sekretariat telah memberikan tawaran divisi alternatif. Silakan cek detail pengajuan Anda dan berikan konfirmasi.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan];
                break;
            case 'menunggu_finalisasi_sekretariat':
                $pesan = "Mahasiswa telah menyetujui tawaran divisi. Menunggu finalisasi dari Sekretariat.";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'diterima':
                $pesan = "Selamat! Pengajuan magang Anda telah DITERIMA.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan];
                break;
            case 'ditolak':
                $pesan = "Pengajuan Anda ditolak. Alasan: " . ($pengajuan['alasan_penolakan'] ?? 'Tidak memenuhi syarat.');
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan];
                break;
            case 'dibatalkan_oleh_mahasiswa':
                $pesan = "Mahasiswa membatalkan pengajuan (No: {$pengajuan['nomor_pengajuan']}).";
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => $pesan];
                }
                break;
            case 'sedang_magang':
                $pesan = "Masa magang Anda telah dimulai.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan];
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => "Mahasiswa (No: {$pengajuan['nomor_pengajuan']}) telah memasuki masa magang."];
                }
                break;
            case 'selesai':
                $pesan = "Masa magang Anda telah selesai. Anda dapat mengunduh dokumen akhir jika tersedia.";
                $notifs[] = ['user_id' => $mahasiswaId, 'pesan' => $pesan];
                foreach ($getSekretariatIds() as $sekId) {
                    $notifs[] = ['user_id' => $sekId, 'pesan' => "Mahasiswa (No: {$pengajuan['nomor_pengajuan']}) telah menyelesaikan masa magang."];
                }
                break;
        }

        // Simpan notifikasi
        foreach ($notifs as $n) {
            $this->notificationService->create($n['user_id'], $pengajuanId, $n['pesan']);
        }
    }
}
