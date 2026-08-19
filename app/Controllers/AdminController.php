<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Models\Divisi;
use App\Services\AuditService;

class AdminController extends Controller {
    private $userModel;
    private $divisiModel;
    private $auditService;

    public function __construct() {
        Auth::requireRole('admin');
        $this->userModel = new User();
        $this->divisiModel = new Divisi();
        $this->auditService = new AuditService();
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
        $db = \App\Core\Database::getInstance()->getConnection();
        
        // Admin Metrics
        $totalSekretariat = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'sekretariat'")->fetch()['total'];
        $totalAdmin = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'")->fetch()['total'];
        $totalDivisi = $db->query("SELECT COUNT(*) as total FROM divisi")->fetch()['total'];
        
        $kapasitasTotal = $db->query("SELECT SUM(kapasitas) as total FROM divisi")->fetch()['total'] ?? 0;
        $slotTerpakai = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status IN ('diterima', 'sedang_magang')")->fetch()['total'] ?? 0;
        
        $listDivisi = $db->query("
            SELECT d.*, 
            (SELECT COUNT(*) FROM pengajuan p WHERE p.divisi_id_final = d.id AND p.status IN ('diterima', 'sedang_magang')) as terisi 
            FROM divisi d 
            ORDER BY d.nama_divisi ASC
        ")->fetchAll();

        $divisiPenuh = 0;
        foreach ($listDivisi as $d) {
            if ($d['terisi'] >= $d['kapasitas']) {
                $divisiPenuh++;
            }
        }

        $listInternal = $db->query("SELECT id, nama, email, role FROM users WHERE role IN ('admin', 'sekretariat') ORDER BY role DESC, nama ASC")->fetchAll();

        // Sekretariat Metrics
        $totalTindakLanjut = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status IN ('diajukan', 'dalam_verifikasi')")->fetch()['total'];
        $totalRevisi = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'revisi'")->fetch()['total'];
        $totalAktif = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'sedang_magang'")->fetch()['total'];
        $totalPengajuan = $db->query("SELECT COUNT(*) as total FROM pengajuan")->fetch()['total'];
        
        $totalSelesai = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'selesai'")->fetch()['total'];
        $totalDiterima = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'diterima'")->fetch()['total'];

        $distribusi = $db->query("SELECT status, COUNT(*) as count FROM pengajuan GROUP BY status")->fetchAll(\PDO::FETCH_ASSOC);
        $dist = [];
        foreach ($distribusi as $d) {
            $dist[$d['status']] = $d['count'];
        }

        $pengajuan_terbaru = $db->query("
            SELECT p.*, u.nama as mahasiswa_nama, mp.universitas, mp.program_studi, d.nama_divisi as divisi_nama 
            FROM pengajuan p 
            JOIN users u ON p.user_id = u.id 
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi d ON p.divisi_id_preferensi = d.id
            WHERE p.status IN ('diajukan', 'dalam_verifikasi')
            ORDER BY p.created_at DESC LIMIT 5
        ")->fetchAll();

        $this->renderView('admin/dashboard', [
            'title' => 'Dashboard',
            'subtitle' => 'Ringkasan operasional dan sistem.',
            'currentPage' => 'dashboard',
            'stats' => [
                'divisi_total' => $totalDivisi,
                'divisi_penuh' => $divisiPenuh,
                'kapasitas_total' => $kapasitasTotal,
                'slot_terpakai' => $slotTerpakai,
                'internal_total' => $totalSekretariat + $totalAdmin,
                'sekretariat' => $totalSekretariat,
                'admin' => $totalAdmin,
                'tindak_lanjut' => $totalTindakLanjut,
                'revisi' => $totalRevisi,
                'aktif' => $totalAktif,
                'total' => $totalPengajuan,
                'selesai' => $totalSelesai,
                'diterima' => $totalDiterima
            ],
            'distribusi' => $dist,
            'pengajuan_terbaru' => $pengajuan_terbaru,
            'list_divisi' => $listDivisi,
            'list_internal' => $listInternal
        ]);
    }

    public function users() {
        $db = \App\Core\Database::getInstance()->getConnection();
        $q = $_GET['q'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $conditions = "WHERE 1=1";
        $params = [];
        
        if (!empty($q)) {
            $conditions .= " AND (nama LIKE ? OR email LIKE ? OR id LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        if (!empty($role)) {
            $conditions .= " AND role = ?";
            $params[] = $role;
        }
        if (!empty($status)) {
            $conditions .= " AND status = ?";
            $params[] = $status;
        }
        
        // Count total rows
        $countSql = "SELECT COUNT(*) as total FROM users $conditions";
        $stmtCount = $db->prepare($countSql);
        $stmtCount->execute($params);
        $totalRows = $stmtCount->fetch()['total'];
        $totalPages = ceil($totalRows / $limit);
        
        // Handle out of bounds page safely
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $sql = "SELECT * FROM users $conditions ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->renderView('admin/users', [
            'title' => 'Kelola Pengguna',
            'subtitle' => 'Kelola akses akun untuk Mahasiswa, Sekretariat, dan Administrator.',
            'currentPage' => 'users',
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    public function bidang() {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Count total rows
        $countSql = "SELECT COUNT(*) as total FROM divisi";
        $stmtCount = $db->prepare($countSql);
        $stmtCount->execute();
        $totalRows = $stmtCount->fetch()['total'];
        $totalPages = ceil($totalRows / $limit);
        
        // Handle out of bounds page safely
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $divisi = $db->query("
            SELECT d.*, 
            (SELECT COUNT(*) FROM pengajuan p WHERE p.divisi_id_final = d.id AND p.status IN ('diterima', 'sedang_magang')) as terisi 
            FROM divisi d 
            ORDER BY d.nama_divisi ASC
            LIMIT $limit OFFSET $offset
        ")->fetchAll();

        $this->renderView('admin/bidang', [
            'title' => 'Kelola Divisi / Bidang',
            'subtitle' => 'Master data divisi magang dan status kebutuhan masing-masing.',
            'currentPage' => 'bidang',
            'divisi' => $divisi,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    public function storeDivisi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nama = trim($_POST['nama_divisi'] ?? '');
                $deskripsi = trim($_POST['deskripsi'] ?? '');
                $kapasitas = (int)($_POST['kapasitas'] ?? 0);

                if (empty($nama)) {
                    throw new \Exception("Nama divisi wajib diisi.");
                }

                $db = \App\Core\Database::getInstance()->getConnection();
                
                // Pengecekan nama divisi tidak boleh duplikat (case-insensitive)
                $stmt = $db->prepare("SELECT id FROM divisi WHERE LOWER(nama_divisi) = LOWER(?)");
                $stmt->execute([$nama]);
                if ($stmt->fetch()) {
                    throw new \Exception("Nama divisi '{$nama}' sudah digunakan.");
                }

                $stmt = $db->prepare("INSERT INTO divisi (nama_divisi, deskripsi, kapasitas) VALUES (?, ?, ?)");
                $stmt->execute([$nama, $deskripsi, $kapasitas]);
                $divisiId = $db->lastInsertId();

                $this->auditService->log('CREATE_DIVISI', 'divisi', $divisiId, "Menambahkan divisi baru: $nama");

                \App\Core\Session::setFlash('success', 'Divisi berhasil ditambahkan.');
            } catch (\Exception $e) {
                \App\Core\Session::setFlash('error', \App\Core\ErrorHandler::userMessage($e));
            }
            return $this->redirect('admin/bidang');
        }
    }

    public function updateDivisi($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nama = trim($_POST['nama_divisi'] ?? '');
                $deskripsi = trim($_POST['deskripsi'] ?? '');
                $kapasitas = (int)($_POST['kapasitas'] ?? 0);

                if (empty($nama)) {
                    throw new \Exception("Nama divisi wajib diisi.");
                }

                $db = \App\Core\Database::getInstance()->getConnection();
                
                // Pengecekan duplikasi saat update
                $stmt = $db->prepare("SELECT id FROM divisi WHERE LOWER(nama_divisi) = LOWER(?) AND id != ?");
                $stmt->execute([$nama, $id]);
                if ($stmt->fetch()) {
                    throw new \Exception("Nama divisi '{$nama}' sudah digunakan oleh divisi lain.");
                }

                $stmt = $db->prepare("UPDATE divisi SET nama_divisi = ?, deskripsi = ?, kapasitas = ? WHERE id = ?");
                $stmt->execute([$nama, $deskripsi, $kapasitas, $id]);

                $this->auditService->log('UPDATE_DIVISI', 'divisi', $id, "Memperbarui divisi: $nama");

                \App\Core\Session::setFlash('success', 'Divisi berhasil diperbarui.');
            } catch (\Exception $e) {
                \App\Core\Session::setFlash('error', \App\Core\ErrorHandler::userMessage($e));
            }
            return $this->redirect('admin/bidang');
        }
    }

    public function toggleStatusDivisi($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $db = \App\Core\Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT status FROM divisi WHERE id = ?");
                $stmt->execute([$id]);
                $divisi = $stmt->fetch();

                if ($divisi) {
                    $newStatus = ($divisi['status'] === 'aktif') ? 'nonaktif' : 'aktif';
                    $stmt = $db->prepare("UPDATE divisi SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $id]);
                    
                    $this->auditService->log('TOGGLE_STATUS_DIVISI', 'divisi', $id, "Mengubah status divisi menjadi $newStatus");
                    
                    \App\Core\Session::setFlash('success', 'Status divisi berhasil diubah.');
                }
            } catch (\Exception $e) {
                \App\Core\Session::setFlash('error', \App\Core\ErrorHandler::userMessage($e));
            }
            return $this->redirect('admin/bidang');
        }
    }

    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nama = trim($_POST['nama'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? '';

                if (empty($nama) || empty($email) || empty($password) || empty($role)) {
                    throw new \Exception("Semua kolom wajib diisi.");
                }

                if (!in_array($role, ['sekretariat', 'admin'])) {
                    throw new \Exception("Role tidak valid. Hanya dapat membuat akun Sekretariat atau Admin.");
                }

                $db = \App\Core\Database::getInstance()->getConnection();
                
                // Check email uniqueness
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    throw new \Exception("Email sudah digunakan.");
                }

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $db->prepare("INSERT INTO users (nama, email, password, role, status) VALUES (?, ?, ?, ?, 'aktif')");
                $stmt->execute([$nama, $email, $hashedPassword, $role]);
                $userId = $db->lastInsertId();

                $this->auditService->log('CREATE_USER', 'users', $userId, "Membuat akun internal baru: $email ($role)");

                \App\Core\Session::setFlash('success', 'Pengguna internal berhasil ditambahkan.');
            } catch (\Exception $e) {
                \App\Core\Session::setFlash('error', \App\Core\ErrorHandler::userMessage($e));
            }
            return $this->redirect('admin/users');
        }
    }

    public function updateUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nama = trim($_POST['nama'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $role = $_POST['role'] ?? '';
                $password = $_POST['password'] ?? '';

                if (empty($nama) || empty($email) || empty($role)) {
                    throw new \Exception("Nama, Email, dan Role wajib diisi.");
                }

                if (!in_array($role, ['sekretariat', 'admin', 'mahasiswa'])) {
                    throw new \Exception("Role tidak valid.");
                }

                // Protect self privilege demotion
                if ($id == \App\Core\Session::get('user_id') && $role !== 'admin') {
                    throw new \Exception("Anda tidak dapat menurunkan role akun Anda sendiri.");
                }

                $db = \App\Core\Database::getInstance()->getConnection();
                
                // Check email uniqueness, excluding current user
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $id]);
                if ($stmt->fetch()) {
                    throw new \Exception("Email sudah digunakan oleh pengguna lain.");
                }

                if (!empty($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $db->prepare("UPDATE users SET nama = ?, email = ?, role = ?, password = ? WHERE id = ?");
                    $stmt->execute([$nama, $email, $role, $hashedPassword, $id]);
                } else {
                    $stmt = $db->prepare("UPDATE users SET nama = ?, email = ?, role = ? WHERE id = ?");
                    $stmt->execute([$nama, $email, $role, $id]);
                }

                $this->auditService->log('UPDATE_USER', 'users', $id, "Memperbarui akun pengguna: $email ($role)");

                \App\Core\Session::setFlash('success', 'Pengguna berhasil diperbarui.');
            } catch (\Exception $e) {
                \App\Core\Session::setFlash('error', \App\Core\ErrorHandler::userMessage($e));
            }
            return $this->redirect('admin/users');
        }
    }

    public function toggleStatusUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Prevent self-deactivation
                if ($id == \App\Core\Session::get('user_id')) {
                    throw new \Exception("Anda tidak dapat menonaktifkan akun Anda sendiri.");
                }

                $db = \App\Core\Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT status FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $user = $stmt->fetch();

                if ($user) {
                    $newStatus = ($user['status'] === 'aktif') ? 'nonaktif' : 'aktif';
                    $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $id]);
                    
                    $this->auditService->log('TOGGLE_STATUS_USER', 'users', $id, "Mengubah status akun pengguna menjadi $newStatus");
                    
                    \App\Core\Session::setFlash('success', 'Status pengguna berhasil diubah.');
                }
            } catch (\Exception $e) {
                \App\Core\Session::setFlash('error', \App\Core\ErrorHandler::userMessage($e));
            }
            return $this->redirect('admin/users');
        }
    }
    public function auditLog() {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $action = $_GET['action'] ?? '';
        $q = $_GET['q'] ?? '';
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $conditions = "WHERE 1=1";
        $params = [];
        
        if (!empty($action)) {
            $conditions .= " AND a.action = ?";
            $params[] = $action;
        }

        if (!empty($q)) {
            $conditions .= " AND (u.nama LIKE ? OR a.action LIKE ? OR a.entity LIKE ? OR a.details LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        
        // Count total rows
        $countSql = "
            SELECT COUNT(*) as total 
            FROM audit_logs a
            LEFT JOIN users u ON a.user_id = u.id
            $conditions
        ";
        $stmtCount = $db->prepare($countSql);
        $stmtCount->execute($params);
        $totalRows = $stmtCount->fetch()['total'];
        $totalPages = ceil($totalRows / $limit);
        
        // Handle out of bounds page safely
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $sql = "
            SELECT a.*, u.nama as user_name 
            FROM audit_logs a
            LEFT JOIN users u ON a.user_id = u.id
            $conditions
            ORDER BY a.created_at DESC
            LIMIT $limit OFFSET $offset
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $logs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->renderView('admin/audit_log', [
            'title' => 'Audit Log',
            'subtitle' => 'Pantau aktivitas administratif dan perubahan data penting dalam sistem.',
            'currentPage' => 'audit_log',
            'logs' => $logs,
            'actionFilter' => $action,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }
}
