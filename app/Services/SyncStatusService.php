<?php
namespace App\Services;

use App\Core\Database;
use App\Core\Logger;



/** Menyelaraskan status pengajuan menurut tanggal. Tanpa cron; dipanggil saat App boot. */
class SyncStatusService {
    private $db;
    private $statusService;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->statusService = new StatusService();
    }

    // Jeda minimum antar pemindaian. Tanggal berubah sekali sehari, jadi
    // memindai lebih sering dari ini tidak menghasilkan apa pun.
    const JEDA_MENIT = 10;

    /**
     * @param bool $paksa Lewati jeda; dipakai pengujian dan pemanggilan manual.
     */
    public function sync($paksa = false) {
        if (!$paksa && !$this->waktunyaMemindai()) {
            return;
        }

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

    /**
     * Penanda berbasis berkas, bukan tabel, supaya pemeriksaan jeda ini
     * sendiri tidak menambah kueri ke basis data.
     */
    private function waktunyaMemindai() {
        $berkas = ROOT_PATH . '/storage/sinkron-terakhir';

        if (is_file($berkas) && (time() - (int)@filemtime($berkas)) < self::JEDA_MENIT * 60) {
            return false;
        }

        $folder = dirname($berkas);
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        @touch($berkas);

        return true;
    }
}
