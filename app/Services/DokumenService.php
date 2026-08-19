<?php
namespace App\Services;

use App\Models\Dokumen;
use App\Core\Session;

class DokumenService {
    private $dokumenModel;
    private $auditService;
    private $uploadDir;

    public function __construct() {
        $this->dokumenModel = new Dokumen();
        $this->auditService = new AuditService();
        $this->uploadDir = ROOT_PATH . '/storage/uploads/';
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function uploadDokumen($pengajuanId, $nomorPengajuan, $jenisDokumen, $fileArray) {
        $userId = Session::get('user_id');
        $role = \App\Core\Auth::role();

        // RBAC / IDOR Protection
        $stmt = $this->dokumenModel->getDb()->prepare("SELECT * FROM pengajuan WHERE id = ?");
        $stmt->execute([$pengajuanId]);
        $pengajuan = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$pengajuan) {
            throw new \Exception("Pengajuan tidak ditemukan.");
        }

        if ($role === 'mahasiswa' && $pengajuan['user_id'] != $userId) {
            throw new \Exception("Akses Ditolak. Anda tidak dapat mengunggah dokumen untuk pengajuan pengguna lain.");
        }

        // Dokumen akhir magang hanya bisa diunggah Sekretariat atau Super Admin
        if ($jenisDokumen === 'dokumen_akhir_magang' && !in_array($role, ['sekretariat', 'admin'])) {
            throw new \Exception("Akses Ditolak. Dokumen akhir magang hanya dapat diunggah oleh Sekretariat atau Super Admin.");
        }

        // Validation
        if ($fileArray['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Terjadi kesalahan saat mengunggah file {$jenisDokumen}.");
        }

        // Size validation (Max 2MB)
        if ($fileArray['size'] > 2 * 1024 * 1024) {
            throw new \Exception("Ukuran file {$jenisDokumen} melebihi batas 2MB.");
        }

        // MIME validation
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileArray['tmp_name']);
        finfo_close($finfo);

        if ($mimeType !== 'application/pdf') {
            throw new \Exception("File {$jenisDokumen} harus berupa PDF.");
        }

        // Extension validation
        $ext = strtolower(pathinfo($fileArray['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            throw new \Exception("Ekstensi file {$jenisDokumen} tidak valid.");
        }

        // Naming: [nomor_pengajuan]_[jenis_dokumen]_[timestamp]_[random].pdf
        $timestamp = time();
        $random = bin2hex(random_bytes(4));
        $safeNomor = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nomorPengajuan);
        $filename = "{$safeNomor}_{$jenisDokumen}_{$timestamp}_{$random}.pdf";
        $targetPath = $this->uploadDir . $filename;

        // Versioning logic
        $currentDocs = $this->dokumenModel->findCurrentByPengajuanIdAndJenis($pengajuanId, $jenisDokumen);
        $newVersion = $currentDocs ? $currentDocs['version'] + 1 : 1;

        $this->dokumenModel->beginTransaction();
        try {
            // Set old document to not current if exists
            if ($currentDocs) {
                $this->dokumenModel->setNotCurrent($pengajuanId, $jenisDokumen);
            }

            // Move file (support CLI testing)
            $moved = (PHP_SAPI === 'cli') ? copy($fileArray['tmp_name'], $targetPath) : move_uploaded_file($fileArray['tmp_name'], $targetPath);
            if (!$moved) {
                throw new \Exception("Gagal menyimpan file {$jenisDokumen} ke server.");
            }

            // Insert DB
            $docId = $this->dokumenModel->create(
                $pengajuanId, 
                $jenisDokumen, 
                $filename, 
                $fileArray['name'], 
                $newVersion, 
                true, 
                Session::get('user_id')
            );

            $this->auditService->log('upload_dokumen', 'dokumen', $docId, "Mengunggah {$jenisDokumen} versi {$newVersion}");
            
            $this->dokumenModel->commit();
            return $docId;

        } catch (\Exception $e) {
            $this->dokumenModel->rollBack();
            if (file_exists($targetPath)) {
                unlink($targetPath); // Cleanup file if DB transaction failed
            }
            throw $e;
        }
    }
    
    public function getDokumenAktif($pengajuanId) {
        return $this->dokumenModel->findCurrentByPengajuanId($pengajuanId);
    }

    public function downloadDokumen($dokumenId) {
        $userId = Session::get('user_id');
        $role = \App\Core\Auth::role();

        $dokumen = $this->dokumenModel->findById($dokumenId);
        if (!$dokumen) {
            throw new \Exception("Dokumen tidak ditemukan.");
        }

        // RBAC / IDOR Protection
        $stmt = $this->dokumenModel->getDb()->prepare("SELECT * FROM pengajuan WHERE id = ?");
        $stmt->execute([$dokumen['pengajuan_id']]);
        $pengajuan = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($role === 'mahasiswa' && $pengajuan['user_id'] != $userId) {
            throw new \Exception("Akses Ditolak. Anda tidak berhak mengunduh dokumen ini.");
        }

        $filePath = $this->uploadDir . $dokumen['file_path'];
        if (!file_exists($filePath)) {
            throw new \Exception("File fisik tidak ditemukan di server.");
        }

        return [
            'path' => $filePath,
            'original_filename' => $dokumen['original_filename']
        ];
    }
}
