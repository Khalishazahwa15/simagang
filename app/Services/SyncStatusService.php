<?php
namespace App\Services;

use App\Core\Database;
use App\Core\Logger;

class SyncStatusService {
    private $db;
    private $statusService;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->statusService = new StatusService();
    }

    /**
     * Sinkronisasi status pengajuan secara otomatis berdasarkan tanggal.
     * Karena tidak ada cron job, fungsi ini akan dipanggil secara "lazy" 
     * setiap kali aplikasi diakses (misalnya di Middleware atau boot).
     */
    public function sync() {
        // Prevent double sync or infinite loop issues by making it fast and simple
        $today = date('Y-m-d');

        // 1. diterima -> sedang_magang
        // Jika tanggal_mulai_aktual <= hari ini
        $stmtDiterima = $this->db->prepare("
            SELECT id FROM pengajuan 
            WHERE status = 'diterima' 
            AND tanggal_mulai_aktual IS NOT NULL 
            AND tanggal_mulai_aktual <= ?
        ");
        $stmtDiterima->execute([$today]);
        $toMulai = $stmtDiterima->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($toMulai as $pengajuanId) {
            try {
                // Gunakan fungsi updateStatus dari StatusService agar History dan Notifikasi tetap berjalan
                // Catatan: Ini membutuhkan akses user session, tapi jika dipanggil dari public middleware, 
                // user session mungkin belum ada atau admin. Kita override user_id = 0 (Sistem) di StatusHistoryModel.
                // Namun karena ini dipanggil saat ada request, user_id akan menggunakan user yang sedang login.
                $this->statusService->updateStatus($pengajuanId, 'diterima', 'sedang_magang', 'Sistem: Sinkronisasi otomatis tanggal mulai magang.');
            } catch (\Exception $e) {
                // Ignore single failure to allow others to process
                Logger::error('SYNC (sedang_magang)', $e->getMessage());
            }
        }

        // 2. sedang_magang -> selesai
        // Jika tanggal_selesai_aktual < hari ini
        $stmtSelesai = $this->db->prepare("
            SELECT id FROM pengajuan 
            WHERE status = 'sedang_magang' 
            AND tanggal_selesai_aktual IS NOT NULL 
            AND tanggal_selesai_aktual < ?
        ");
        $stmtSelesai->execute([$today]);
        $toSelesai = $stmtSelesai->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($toSelesai as $pengajuanId) {
            try {
                $this->statusService->updateStatus($pengajuanId, 'sedang_magang', 'selesai', 'Sistem: Sinkronisasi otomatis tanggal selesai magang.');
            } catch (\Exception $e) {
                // Ignore single failure
                Logger::error('SYNC (selesai)', $e->getMessage());
            }
        }
    }
}
