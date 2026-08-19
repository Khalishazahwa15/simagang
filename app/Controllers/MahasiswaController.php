<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Services\PengajuanService;
use App\Models\Divisi;

class MahasiswaController extends Controller {
    private $pengajuanService;
    private $divisiModel;

    public function __construct() {
        Auth::requireRole('mahasiswa');
        $this->pengajuanService = new PengajuanService();
        $this->divisiModel = new Divisi();
    }

    private function renderView($view, $data = []) {
        ob_start();
        extract($data);
        $this->view($view, $data);
        $content = ob_get_clean();
        
        $this->view('layouts/app', [
            'content' => $content, 
            'title' => $data['title'] ?? 'Dashboard',
            'subtitle' => $data['subtitle'] ?? '',
            'currentPage' => $data['currentPage'] ?? 'dashboard'
        ]);
    }

    public function dashboard() {
        $nama = Session::get('nama') ?? 'Mahasiswa';
        
        $pengajuan = $this->pengajuanService->getPengajuanMahasiswa(Session::get('user_id'));
        $aktif = $pengajuan[0] ?? null;

        $history = [];
        $dokumen = [];
        if ($aktif) {
            $statusHistoryModel = new \App\Models\StatusHistory();
            $history = $statusHistoryModel->findByPengajuanId($aktif['id']);
            
            $dokumenModel = new \App\Models\Dokumen();
            $dokumen = $dokumenModel->findCurrentByPengajuanId($aktif['id']);
            
            if ($aktif['divisi_id_preferensi']) {
                $divisi = $this->divisiModel->findById($aktif['divisi_id_preferensi']);
                $aktif['nama_divisi_preferensi'] = $divisi['nama_divisi'] ?? null;
            }

            if ($aktif['divisi_id_final']) {
                $divisi = $this->divisiModel->findById($aktif['divisi_id_final']);
                $aktif['nama_divisi_final'] = $divisi['nama_divisi'] ?? null;
            }
        }

        $this->renderView('mahasiswa/dashboard', [
            'title' => 'Selamat datang, ' . explode(' ', $nama)[0] . '.',
            'subtitle' => 'Pantau proses pengajuan magangmu dari satu tempat.',
            'currentPage' => 'dashboard',
            'pengajuan' => $aktif,
            'riwayat' => $history,
            'dokumen' => $dokumen,
            'namaLengkap' => $nama
        ]);
    }

    public function pengajuan() {
        // Cek apakah mahasiswa sudah punya pengajuan aktif (opsional, tapi disarankan)
        $divisi = $this->divisiModel->getAktif();
        
        $this->renderView('mahasiswa/pengajuan', [
            'title' => 'Pengajuan Magang',
            'subtitle' => 'Formulir permohonan magang baru.',
            'currentPage' => 'pengajuan',
            'divisi' => $divisi
        ]);
    }

    public function submitPengajuan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $divisiPreferensi = $_POST['divisi'] ?? null;
                $tanggalMulai = $_POST['start_date'] ?? null;
                $tanggalSelesai = $_POST['end_date'] ?? null;
                
                $this->pengajuanService->createPengajuan(
                    Session::get('user_id'),
                    $divisiPreferensi,
                    $tanggalMulai,
                    $tanggalSelesai,
                    $_FILES
                );

                Session::setFlash('success', 'Pengajuan berhasil dikirim.');
                return $this->redirect('mahasiswa/status');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
                return $this->redirect('mahasiswa/pengajuan');
            }
        }
        return $this->redirect('mahasiswa/pengajuan');
    }

    public function status() {
        $pengajuan = $this->pengajuanService->getPengajuanMahasiswa(Session::get('user_id'));
        $aktif = $pengajuan[0] ?? null; // Ambil yang paling baru
        
        $history = [];
        if ($aktif) {
            $statusHistoryModel = new \App\Models\StatusHistory();
            $history = $statusHistoryModel->findByPengajuanId($aktif['id']);
            
            if ($aktif['divisi_id_final']) {
                $divisi = $this->divisiModel->findById($aktif['divisi_id_final']);
                $aktif['nama_divisi_final'] = $divisi['nama_divisi'] ?? null;
            } elseif ($aktif['divisi_id_preferensi']) {
                $divisi = $this->divisiModel->findById($aktif['divisi_id_preferensi']);
                $aktif['nama_divisi_preferensi'] = $divisi['nama_divisi'] ?? null;
            }
        }

        $this->renderView('mahasiswa/status', [
            'title' => 'Status Pengajuan',
            'subtitle' => 'Lacak progres verifikasi.',
            'currentPage' => 'status',
            'pengajuan' => $aktif,
            'riwayat' => $history
        ]);
    }

    public function dokumen() {
        $pengajuan = $this->pengajuanService->getPengajuanMahasiswa(Session::get('user_id'));
        $aktif = $pengajuan[0] ?? null;
        
        $dokumen = [];
        if ($aktif) {
            $dokumenModel = new \App\Models\Dokumen();
            $dokumen = $dokumenModel->findCurrentByPengajuanId($aktif['id']);
        }

        $this->renderView('mahasiswa/dokumen', [
            'title' => 'Dokumen',
            'subtitle' => 'Dokumen yang Anda unggah dan dokumen resmi dari Bappeda.',
            'currentPage' => 'dokumen',
            'pengajuan' => $aktif,
            'dokumen' => $dokumen
        ]);
    }

    public function profil() {
        $userId = Session::get('user_id');
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT u.nama, u.email, p.* FROM users u LEFT JOIN mahasiswa_profiles p ON u.id = p.user_id WHERE u.id = ?");
        $stmt->execute([$userId]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->renderView('mahasiswa/profil', [
            'title' => 'Profil',
            'subtitle' => 'Pengaturan akun Anda.',
            'currentPage' => 'profil',
            'user' => $userData
        ]);
    }

    public function downloadDokumen($dokumenId) {
        try {
            $dokumenService = new \App\Services\DokumenService();
            $file = $dokumenService->downloadDokumen($dokumenId);
            
            // Clear buffer
            if (ob_get_length()) ob_clean();
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file['original_filename']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file['path']));
            
            readfile($file['path']);
            exit;
        } catch (\Exception $e) {
            Session::setFlash('error', $e->getMessage());
            return $this->redirect('mahasiswa/dokumen');
        }
    }

    public function updateProfil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userId = Session::get('user_id');
                $nama = trim($_POST['nama'] ?? '');
                $nim = trim($_POST['nim'] ?? '');
                $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
                $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
                $no_hp = trim($_POST['no_hp'] ?? '');
                $alamat = trim($_POST['alamat'] ?? '');
                $universitas = trim($_POST['universitas'] ?? '');
                $fakultas = trim($_POST['fakultas'] ?? '');
                $prodi = trim($_POST['prodi'] ?? '');
                $semester = (int)($_POST['semester'] ?? 0);

                if (empty($nama) || empty($nim) || empty($tempat_lahir) || empty($tanggal_lahir) || empty($no_hp) || empty($alamat) || empty($universitas) || empty($fakultas) || empty($prodi) || empty($semester)) {
                    throw new \Exception("Semua field wajib diisi.");
                }

                $db = \App\Core\Database::getInstance()->getConnection();
                
                // Mulai transaksi
                $db->beginTransaction();
                
                // Update tabel users
                $stmt = $db->prepare("UPDATE users SET nama = ? WHERE id = ?");
                $stmt->execute([$nama, $userId]);
                
                // Update atau Insert ke mahasiswa_profiles
                $stmt = $db->prepare("SELECT id FROM mahasiswa_profiles WHERE user_id = ?");
                $stmt->execute([$userId]);
                $profile = $stmt->fetch();
                
                if ($profile) {
                    $stmt = $db->prepare("UPDATE mahasiswa_profiles SET nim=?, tempat_lahir=?, tanggal_lahir=?, nomor_hp=?, alamat=?, universitas=?, fakultas=?, program_studi=?, semester=? WHERE user_id=?");
                    $stmt->execute([$nim, $tempat_lahir, $tanggal_lahir, $no_hp, $alamat, $universitas, $fakultas, $prodi, $semester, $userId]);
                } else {
                    $stmt = $db->prepare("INSERT INTO mahasiswa_profiles (user_id, nim, tempat_lahir, tanggal_lahir, nomor_hp, alamat, universitas, fakultas, program_studi, semester) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $nim, $tempat_lahir, $tanggal_lahir, $no_hp, $alamat, $universitas, $fakultas, $prodi, $semester]);
                }
                
                $db->commit();
                
                // Update nama di session
                $userSession = Session::get('user');
                $userSession['nama'] = $nama;
                Session::set('user', $userSession);
                
                $auditService = new \App\Services\AuditService();
                $auditService->log('UPDATE_PROFIL', 'users', $userId, "Mahasiswa memperbarui profil");

                Session::setFlash('success', 'Profil berhasil diperbarui.');
            } catch (\Exception $e) {
                if (isset($db) && $db->inTransaction()) {
                    $db->rollBack();
                }
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('mahasiswa/profil');
        }
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userId = Session::get('user_id');
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['new_password_confirm'] ?? '';

                if (empty($new_password) || empty($confirm_password)) {
                    throw new \Exception("Semua field kata sandi wajib diisi.");
                }

                if ($new_password !== $confirm_password) {
                    throw new \Exception("Konfirmasi kata sandi tidak cocok.");
                }

                if (strlen($new_password) < 8) {
                    throw new \Exception("Kata sandi minimal 8 karakter.");
                }

                $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
                
                $db = \App\Core\Database::getInstance()->getConnection();
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $userId]);
                
                $auditService = new \App\Services\AuditService();
                $auditService->log('UPDATE_PASSWORD', 'users', $userId, "Mahasiswa memperbarui kata sandi");

                Session::setFlash('success', 'Kata sandi berhasil diperbarui.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('mahasiswa/profil');
        }
    }

    public function revisi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $pengajuanId = $_POST['pengajuan_id'] ?? null;
                $jenisDokumen = $_POST['jenis_dokumen'] ?? null;

                if (!$pengajuanId || !$jenisDokumen || empty($_FILES['file_dokumen'])) {
                    throw new \Exception("Parameter tidak lengkap.");
                }

                // Verify pengajuan is indeed in revisi state
                $pengajuan = $this->pengajuanService->getPengajuanMahasiswa(Session::get('user_id'))[0] ?? null;
                if (!$pengajuan || $pengajuan['id'] != $pengajuanId || $pengajuan['status'] !== 'revisi') {
                    throw new \Exception("Akses Ditolak. Anda tidak dapat merevisi dokumen ini.");
                }

                $dokumenService = new \App\Services\DokumenService();
                $dokumenService->uploadDokumen($pengajuanId, $pengajuan['nomor_pengajuan'], $jenisDokumen, $_FILES['file_dokumen']);

                // Optionally change status back to diajukan/dalam_verifikasi
                // The PRD says "Mahasiswa -> mengunggah revisi dokumen (status: kembali ke dalam_verifikasi atau diajukan)"
                $statusService = new \App\Services\StatusService();
                $statusService->updateStatus($pengajuanId, 'revisi', 'dalam_verifikasi', 'Mahasiswa telah mengunggah revisi dokumen: ' . $jenisDokumen);

                Session::setFlash('success', 'Dokumen revisi berhasil diunggah.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('mahasiswa/status');
        }
        return $this->redirect('mahasiswa/status');
    }

    public function pengunduranDiri() {
        $pengajuan = $this->pengajuanService->getPengajuanMahasiswa(Session::get('user_id'));
        $aktif = $pengajuan[0] ?? null;
        
        // HANYA muncul kalau status pengajuan aktif == diterima atau sedang_magang
        if (!$aktif || !in_array($aktif['status'], ['diterima', 'sedang_magang'])) {
            Session::setFlash('error', 'Fitur pengunduran diri belum dapat digunakan pada tahap ini.');
            return $this->redirect('mahasiswa/dashboard');
        }

        $this->renderView('mahasiswa/pengunduran_diri', [
            'title' => 'Pengunduran Diri',
            'subtitle' => 'Formulir pengajuan pengunduran diri magang.',
            'currentPage' => 'pengajuan',
            'pengajuan' => $aktif
        ]);
    }

    public function submitPengunduranDiri() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $pengajuanId = $_POST['pengajuan_id'] ?? null;
                $alasan = $_POST['alasan'] ?? '';

                if (!$pengajuanId || empty($_FILES['surat_pengunduran_diri'])) {
                    throw new \Exception("Mohon lengkapi surat pengunduran diri.");
                }

                $pengajuan = $this->pengajuanService->getPengajuanMahasiswa(Session::get('user_id'))[0] ?? null;
                if (!$pengajuan || $pengajuan['id'] != $pengajuanId || !in_array($pengajuan['status'], ['diterima', 'sedang_magang'])) {
                    throw new \Exception("Akses Ditolak.");
                }

                $dokumenService = new \App\Services\DokumenService();
                $dokumenService->uploadDokumen($pengajuanId, $pengajuan['nomor_pengajuan'], 'surat_pengunduran_diri', $_FILES['surat_pengunduran_diri']);

                // We don't transition the state machine yet, but we can log the request
                // In a robust system, we might add a secondary status, but PRD says "Sekretariat verifies to change status to mengundurkan_diri".
                // We'll leave a log and a flash message.
                $auditService = new \App\Services\AuditService();
                $auditService->log('pengunduran_diri', 'pengajuan', $pengajuanId, "Mahasiswa mengajukan pengunduran diri dengan alasan: " . substr($alasan, 0, 50));

                Session::setFlash('success', 'Permintaan pengunduran diri berhasil diajukan dan sedang menunggu verifikasi Sekretariat.');
                return $this->redirect('mahasiswa/dashboard');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
                return $this->redirect('mahasiswa/pengunduran-diri');
            }
        }
        return $this->redirect('mahasiswa/dashboard');
    }

    public function responTawaran() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // CSRF is checked in Router
                $pengajuanId = $_POST['pengajuan_id'] ?? null;
                $action = $_POST['action'] ?? null; // 'terima' or 'tolak'
                
                if (!$pengajuanId || !in_array($action, ['terima', 'tolak'])) {
                    throw new \Exception("Parameter tidak valid.");
                }

                $terima = ($action === 'terima');
                $this->pengajuanService->responTawaran($pengajuanId, Session::get('user_id'), $terima);

                if ($terima) {
                    Session::setFlash('success', 'Anda telah menerima tawaran divisi. Menunggu finalisasi Sekretariat.');
                } else {
                    Session::setFlash('success', 'Tawaran telah ditolak. Pengajuan dibatalkan.');
                }
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('mahasiswa/status');
        }
        return $this->redirect('mahasiswa/status');
    }
}
