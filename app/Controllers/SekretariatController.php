<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Services\PengajuanService;
use App\Services\StatusService;
use App\Services\DokumenService;
use App\Models\Divisi;
use App\Models\Pengajuan;

class SekretariatController extends Controller {
    private $pengajuanService;
    private $statusService;
    private $dokumenService;
    private $divisiModel;
    private $pengajuanModel;

    public function __construct() {
        Auth::requireRole('sekretariat');
        $this->pengajuanService = new PengajuanService();
        $this->statusService = new StatusService();
        $this->dokumenService = new DokumenService();
        $this->divisiModel = new Divisi();
        $this->pengajuanModel = new Pengajuan();
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
        
        // Metrics for summary cards
        $totalTindakLanjut = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status IN ('diajukan', 'dalam_verifikasi')")->fetch()['total'];
        $totalRevisi = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'revisi'")->fetch()['total'];
        $totalAktif = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'sedang_magang'")->fetch()['total'];
        $totalPengajuan = $db->query("SELECT COUNT(*) as total FROM pengajuan")->fetch()['total'];
        
        $totalSelesai = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'selesai'")->fetch()['total'];
        $totalDiterima = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'diterima'")->fetch()['total'];

        // Distribusi Status
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
            ORDER BY p.created_at DESC LIMIT 10
        ")->fetchAll();

        $this->renderView('sekretariat/dashboard', [
            'title' => 'Dashboard Sekretariat',
            'subtitle' => date('l, d F Y') . ' &middot; Bappeda Provinsi Lampung',
            'currentPage' => 'dashboard',
            'stats' => [
                'tindak_lanjut' => $totalTindakLanjut,
                'revisi' => $totalRevisi,
                'aktif' => $totalAktif,
                'total' => $totalPengajuan,
                'selesai' => $totalSelesai,
                'diterima' => $totalDiterima
            ],
            'distribusi' => $dist,
            'pengajuan' => $pengajuan_terbaru
        ]);
    }

    public function pengajuan() {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $q = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';
        $divisi = $_GET['divisi'] ?? '';

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $conditions = "WHERE 1=1";
        $params = [];

        if (!empty($q)) {
            $conditions .= " AND (u.nama LIKE ? OR p.nomor_pengajuan LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        if (!empty($status)) {
            $conditions .= " AND p.status = ?";
            $params[] = $status;
        }
        if (!empty($divisi)) {
            $conditions .= " AND p.divisi_id_preferensi = ?";
            $params[] = $divisi;
        }

        // Count total rows
        $countSql = "
            SELECT COUNT(*) as total 
            FROM pengajuan p 
            JOIN users u ON p.user_id = u.id 
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi d ON p.divisi_id_preferensi = d.id
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
            SELECT p.*, u.nama, mp.nim, mp.universitas, mp.program_studi, d.nama_divisi as nama_divisi_preferensi 
            FROM pengajuan p 
            JOIN users u ON p.user_id = u.id 
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi d ON p.divisi_id_preferensi = d.id
            $conditions
            ORDER BY p.created_at DESC
            LIMIT $limit OFFSET $offset
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $pengajuan = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $divisiList = $db->query("SELECT id, nama_divisi FROM divisi ORDER BY nama_divisi ASC")->fetchAll();

        $this->renderView('sekretariat/pengajuan', [
            'title' => 'Kelola Pengajuan',
            'subtitle' => 'Daftar permohonan magang.',
            'currentPage' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'divisiList' => $divisiList,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    public function pengajuanDetail($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $catatan = $_POST['catatan'] ?? '';

            try {
                if ($action === 'diterima') {
                    $divisiId = $_POST['divisi_id_final'] ?? null;
                    $pembina = $_POST['pembina_lapangan'] ?? null;
                    $mulai = $_POST['tanggal_mulai_aktual'] ?? null;
                    $selesai = $_POST['tanggal_selesai_aktual'] ?? null;
                    $this->pengajuanService->menetapkanDiterima($id, $divisiId, $pembina, $mulai, $selesai, $catatan);
                    Session::setFlash('success', 'Pengajuan berhasil diterima.');
                } elseif ($action === 'ditolak') {
                    $alasan = $_POST['alasan_penolakan'] ?? '';
                    $this->pengajuanService->menetapkanDitolak($id, $alasan, $catatan);
                    Session::setFlash('success', 'Pengajuan berhasil ditolak.');
                } elseif ($action === 'revisi') {
                    $peng = $this->pengajuanModel->findById($id);
                    $this->statusService->updateStatus($id, $peng['status'], 'revisi', $catatan);
                    Session::setFlash('success', 'Pengajuan dikembalikan untuk direvisi.');
                } elseif ($action === 'tawarkan') {
                    $divisiTawaranId = $_POST['divisi_id_tawaran'] ?? null;
                    $this->pengajuanService->menawarkanDivisi($id, $divisiTawaranId, Session::get('user_id'));
                    Session::setFlash('success', 'Tawaran divisi alternatif berhasil dikirim ke mahasiswa.');
                }
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . $id);
        }

        if (!$id) {
            return $this->redirect('sekretariat/pengajuan');
        }

        $pengajuan = $this->pengajuanModel->findById($id);
        if (!$pengajuan) {
            return $this->redirect('sekretariat/pengajuan');
        }

        // Auto-transition to dalam_verifikasi when opened by admin/sekretariat
        if ($pengajuan['status'] === 'diajukan' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->statusService->updateStatus($id, 'diajukan', 'dalam_verifikasi', 'Berkas sedang diperiksa oleh tim.');
            $pengajuan['status'] = 'dalam_verifikasi';
        }

        $divisi = $this->divisiModel->getAktif();
        $dokumen = $this->dokumenService->getDokumenAktif($id);

        $this->renderView('sekretariat/pengajuan_detail', [
            'title' => 'Detail Pengajuan',
            'subtitle' => 'Verifikasi dan proses dokumen.',
            'currentPage' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'divisi' => $divisi,
            'dokumen' => $dokumen
        ]);
    }

    public function peserta() {
        $db = \App\Core\Database::getInstance()->getConnection();
        $q = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';
        $divisi = $_GET['divisi'] ?? '';

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $conditions = "WHERE p.status IN ('diterima', 'sedang_magang', 'selesai')";
        $params = [];
        
        if (!empty($q)) {
            $conditions .= " AND (u.nama LIKE ? OR mp.universitas LIKE ? OR d.nama_divisi LIKE ?)";
            $qLike = "%$q%";
            $params = array_merge($params, [$qLike, $qLike, $qLike]);
        }
        if (!empty($status)) {
            $conditions .= " AND p.status = ?";
            $params[] = $status;
        }
        if (!empty($divisi)) {
            $conditions .= " AND p.divisi_id_final = ?";
            $params[] = $divisi;
        }

        // Count total rows
        $countSql = "
            SELECT COUNT(*) as total 
            FROM pengajuan p 
            JOIN users u ON p.user_id = u.id 
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi d ON p.divisi_id_final = d.id
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
            SELECT p.*, u.nama, u.email, mp.universitas, mp.program_studi, d.nama_divisi as divisi_nama 
            FROM pengajuan p 
            JOIN users u ON p.user_id = u.id 
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi d ON p.divisi_id_final = d.id
            $conditions
            ORDER BY p.updated_at DESC
            LIMIT $limit OFFSET $offset
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $peserta = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $divisiList = $db->query("SELECT id, nama_divisi FROM divisi ORDER BY nama_divisi ASC")->fetchAll();

        $this->renderView('sekretariat/peserta', [
            'title' => 'Daftar Peserta',
            'subtitle' => 'Kelola mahasiswa magang aktif.',
            'currentPage' => 'peserta',
            'peserta' => $peserta,
            'q' => $q,
            'divisiList' => $divisiList,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    public function dokumen() {
        $db = \App\Core\Database::getInstance()->getConnection();
        $q = $_GET['q'] ?? '';

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $conditions = "WHERE d.jenis_dokumen = 'laporan' AND d.is_current = 1";
        $params = [];
        if (!empty($q)) {
            $conditions .= " AND (u.nama LIKE ? OR p.nomor_pengajuan LIKE ?)";
            $qLike = "%$q%";
            $params = [$qLike, $qLike];
        }

        // Count total rows
        $countSql = "
            SELECT COUNT(*) as total 
            FROM dokumen d
            JOIN pengajuan p ON d.pengajuan_id = p.id
            JOIN users u ON p.user_id = u.id
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
            SELECT d.*, p.nomor_pengajuan, u.nama as mahasiswa_nama
            FROM dokumen d
            JOIN pengajuan p ON d.pengajuan_id = p.id
            JOIN users u ON p.user_id = u.id
            $conditions
            ORDER BY d.created_at DESC
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $dokumenList = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->renderView('sekretariat/dokumen', [
            'title' => 'Arsip Dokumen',
            'subtitle' => 'Laporan akhir mahasiswa magang.',
            'currentPage' => 'dokumen',
            'dokumenList' => $dokumenList,
            'q' => $q,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    public function laporan() {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        // Distribusi Status Pengajuan
        $statusRaw = $db->query("SELECT status, COUNT(*) as count FROM pengajuan GROUP BY status")->fetchAll(\PDO::FETCH_ASSOC);
        $distribusi_status = [];
        foreach ($statusRaw as $s) {
            $distribusi_status[$s['status']] = $s['count'];
        }

        // Pengajuan per Divisi (Preferensi)
        $distribusi_divisi = $db->query("
            SELECT d.nama_divisi, COUNT(p.id) as count 
            FROM divisi d 
            LEFT JOIN pengajuan p ON d.id = p.divisi_id_preferensi 
            GROUP BY d.id, d.nama_divisi 
            ORDER BY count DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        $this->renderView('sekretariat/laporan', [
            'title' => 'Laporan & Ekspor',
            'subtitle' => 'Ringkasan data pengajuan magang untuk keperluan pelaporan.',
            'currentPage' => 'laporan',
            'distribusi_status' => $distribusi_status,
            'distribusi_divisi' => $distribusi_divisi
        ]);
    }

    public function exportLaporan() {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $filter = $_GET['filter'] ?? 'semua';
        $whereClause = "";
        
        if ($filter === 'aktif') {
            $whereClause = "WHERE p.status = 'sedang_magang'";
        } elseif ($filter === 'selesai') {
            $whereClause = "WHERE p.status = 'selesai'";
        } elseif ($filter === 'ditolak') {
            $whereClause = "WHERE p.status IN ('ditolak', 'mengundurkan_diri')";
        } elseif ($filter === 'baru') {
            $whereClause = "WHERE p.status IN ('diajukan', 'dalam_verifikasi', 'revisi', 'cek_divisi', 'diterima')";
        }
        
        $pengajuanRaw = $db->query("
            SELECT p.nomor_pengajuan, u.nama, u.email, mp.universitas, mp.program_studi,
                   p.status, p.tanggal_mulai_rencana, p.tanggal_selesai_rencana,
                   dp.nama_divisi as divisi_preferensi, df.nama_divisi as divisi_final
            FROM pengajuan p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi dp ON p.divisi_id_preferensi = dp.id
            LEFT JOIN divisi df ON p.divisi_id_final = df.id
            $whereClause
            ORDER BY p.created_at DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        $filename = "laporan_magang_" . $filter . "_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Nomor Pengajuan', 'Nama Mahasiswa', 'Email', 'Universitas', 'Program Studi',
            'Status', 'Tanggal Mulai', 'Tanggal Selesai', 'Divisi Preferensi', 'Divisi Final'
        ]);

        foreach ($pengajuanRaw as $row) {
            fputcsv($output, [
                $row['nomor_pengajuan'],
                $row['nama'],
                $row['email'],
                $row['universitas'],
                $row['program_studi'],
                strtoupper(str_replace('_', ' ', $row['status'])),
                $row['tanggal_mulai_rencana'],
                $row['tanggal_selesai_rencana'],
                $row['divisi_preferensi'] ?? '-',
                $row['divisi_final'] ?? '-'
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function downloadDokumen($dokumenId) {
        try {
            $file = $this->dokumenService->downloadDokumen($dokumenId);
            
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
            return $this->redirect('sekretariat/pengajuan');
        }
    }

    public function viewDokumen($dokumenId) {
        try {
            $file = $this->dokumenService->downloadDokumen($dokumenId);
            
            if (ob_get_length()) ob_clean();
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($file['original_filename']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file['path']));
            
            readfile($file['path']);
            exit;
        } catch (\Exception $e) {
            Session::setFlash('error', $e->getMessage());
            return $this->redirect('sekretariat/pengajuan');
        }
    }

    public function uploadFinal($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $jenisDokumen = $_POST['jenis_dokumen'] ?? null;
                if (!$jenisDokumen || empty($_FILES['file_dokumen'])) {
                    throw new \Exception("Parameter tidak lengkap.");
                }

                $pengajuan = $this->pengajuanModel->findById($id);
                if (!$pengajuan) {
                    throw new \Exception("Pengajuan tidak ditemukan.");
                }

                $this->dokumenService->uploadDokumen($id, $pengajuan['nomor_pengajuan'], $jenisDokumen, $_FILES['file_dokumen']);
                Session::setFlash('success', 'Dokumen final berhasil diunggah.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . $id);
        }
    }

    public function mulaiMagang($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->statusService->updateStatus($id, 'diterima', 'sedang_magang', 'Peserta telah memulai masa magang.');
                Session::setFlash('success', 'Status pengajuan diubah menjadi sedang magang.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . $id);
        }
    }

    public function tandaiSelesai($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->statusService->updateStatus($id, 'sedang_magang', 'selesai', 'Peserta telah menyelesaikan masa magang.');
                Session::setFlash('success', 'Status pengajuan diubah menjadi selesai.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . $id);
        }
    }

    public function verifikasiMundur($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $pengajuan = $this->pengajuanModel->findById($id);
                $this->statusService->updateStatus($id, $pengajuan['status'], 'mengundurkan_diri', 'Permintaan pengunduran diri telah disetujui Sekretariat.');
                Session::setFlash('success', 'Status pengajuan diubah menjadi mengundurkan diri.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . $id);
        }
    }

    public function tawarkan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $pengajuanId = $_POST['pengajuan_id'] ?? null;
                $divisiTawaranId = $_POST['divisi_id_tawaran'] ?? null;

                if (!$pengajuanId || !$divisiTawaranId) {
                    throw new \Exception("Parameter tidak lengkap.");
                }

                $this->pengajuanService->menawarkanDivisi($pengajuanId, $divisiTawaranId, Session::get('user_id'));
                Session::setFlash('success', 'Tawaran divisi berhasil dikirim ke mahasiswa.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . ($pengajuanId ?? ''));
        }
        return $this->redirect('sekretariat/pengajuan');
    }

    public function finalisasiTawaran() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $pengajuanId = $_POST['pengajuan_id'] ?? null;

                if (!$pengajuanId) {
                    throw new \Exception("Parameter tidak lengkap.");
                }

                $this->pengajuanService->finalisasiTawaran($pengajuanId, Session::get('user_id'));
                Session::setFlash('success', 'Finalisasi penempatan berhasil. Status pengajuan menjadi diterima.');
            } catch (\Exception $e) {
                Session::setFlash('error', $e->getMessage());
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . ($pengajuanId ?? ''));
        }
        return $this->redirect('sekretariat/pengajuan');
    }
}
