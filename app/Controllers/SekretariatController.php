<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Sql;
use App\Core\Session;
use App\Core\ErrorHandler;
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
        // Seluruh angka di bawah berasal dari satu kueri GROUP BY.
        $dist = (new \App\Models\Pengajuan())->hitungPerStatus();
        $jumlah = function (...$status) use ($dist) {
            $n = 0;
            foreach ($status as $s) {
                $n += $dist[$s] ?? 0;
            }
            return $n;
        };

        $totalTindakLanjut = $jumlah('diajukan', 'dalam_verifikasi');
        $totalRevisi = $jumlah('revisi');
        $totalAktif = $jumlah('sedang_magang');
        $totalSelesai = $jumlah('selesai');
        $totalDiterima = $jumlah('diterima');
        $totalPengajuan = array_sum($dist);

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
            $conditions .= ' AND (' . Sql::searchText('u.nama') . ' OR ' . Sql::searchText('p.nomor_pengajuan') . ')';
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
                } elseif ($action === 'mulai_verifikasi') {
                    $peng = $this->pengajuanModel->findById($id);
                    if ($peng && $peng['status'] === 'diajukan') {
                        $this->statusService->updateStatus($id, 'diajukan', 'dalam_verifikasi', 'Berkas sedang diperiksa oleh tim.');
                        Session::setFlash('success', 'Pengajuan ditandai sedang diverifikasi.');
                    }
                } elseif ($action === 'tawarkan') {
                    $divisiTawaranId = $_POST['divisi_id_tawaran'] ?? null;
                    $this->pengajuanService->menawarkanDivisi($id, $divisiTawaranId, Auth::id());
                    Session::setFlash('success', 'Tawaran divisi alternatif berhasil dikirim ke mahasiswa.');
                }
            } catch (\Exception $e) {
                Session::setFlash('error', ErrorHandler::userMessage($e));
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . $id);
        }

        if (!$id) {
            return $this->redirect('sekretariat/pengajuan');
        }

        $pengajuan = $this->pengajuanModel->findDetailById($id);
        if (!$pengajuan) {
            return $this->redirect('sekretariat/pengajuan');
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
            $conditions .= ' AND (' . Sql::searchText('u.nama') . ' OR ' . Sql::searchText('mp.universitas')
                . ' OR ' . Sql::searchText('d.nama_divisi') . ')';
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
            'subtitle' => 'Kelola data mahasiswa yang sedang menjalani masa magang saat ini.',
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

        $conditions = "WHERE d.jenis_dokumen = 'laporan' AND d.is_current = TRUE";
        $params = [];
        if (!empty($q)) {
            $conditions .= ' AND (' . Sql::searchText('u.nama') . ' OR ' . Sql::searchText('p.nomor_pengajuan') . ')';
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
            'subtitle' => 'Kelola laporan akhir magang yang telah diunggah oleh peserta.',
            'currentPage' => 'dokumen',
            'dokumenList' => $dokumenList,
            'q' => $q,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    /**
     * Tulis satu baris CSV setelah menetralkan sel yang bisa ditafsirkan
     * Excel sebagai rumus. Nama mahasiswa berisi "=cmd|..." misalnya, akan
     * dieksekusi saat berkasnya dibuka bila tidak dinetralkan lebih dulu.
     */
    private function tulisBarisCsv($output, array $kolom) {
        $aman = array_map(function ($nilai) {
            $teks = (string)$nilai;
            if ($teks !== '' && strpos("=+-@\t\r", $teks[0]) !== false) {
                return "'" . $teks;
            }
            return $teks;
        }, $kolom);

        fputcsv($output, $aman);
    }

    /**
     * Penyaring periode dan divisi yang dipakai bersama oleh halaman Laporan
     * dan ekspor CSV, agar angka di layar selalu sama dengan isi berkas.
     */
    private function filterLaporan() {
        $dari = trim($_GET['dari'] ?? '');
        $sampai = trim($_GET['sampai'] ?? '');
        $divisi = trim($_GET['divisi'] ?? '');

        $klausa = [];
        $params = [];

        if ($dari !== '') {
            $klausa[] = "DATE(p.created_at) >= ?";
            $params[] = $dari;
        }
        if ($sampai !== '') {
            $klausa[] = "DATE(p.created_at) <= ?";
            $params[] = $sampai;
        }
        if ($divisi !== '') {
            $klausa[] = "p.divisi_id_preferensi = ?";
            $params[] = $divisi;
        }

        return [implode(' AND ', $klausa), $params];
    }

    public function laporan() {
        $db = \App\Core\Database::getInstance()->getConnection();

        list($kondisi, $params) = $this->filterLaporan();
        $where = $kondisi === '' ? '' : "WHERE {$kondisi}";
        $onTambahan = $kondisi === '' ? '' : "AND {$kondisi}";

        // Distribusi Status Pengajuan
        $stmt = $db->prepare("SELECT p.status, COUNT(*) as count FROM pengajuan p {$where} GROUP BY p.status");
        $stmt->execute($params);
        $statusRaw = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $distribusi_status = [];
        foreach ($statusRaw as $s) {
            $distribusi_status[$s['status']] = $s['count'];
        }

        // Pengajuan per Divisi (Preferensi).
        // Penyaring diletakkan di ON, bukan WHERE, supaya divisi tanpa pengajuan tetap tampil.
        $stmt = $db->prepare("
            SELECT d.nama_divisi, COUNT(p.id) as count
            FROM divisi d
            LEFT JOIN pengajuan p ON d.id = p.divisi_id_preferensi {$onTambahan}
            GROUP BY d.id, d.nama_divisi
            ORDER BY count DESC
        ");
        $stmt->execute($params);
        $distribusi_divisi = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT COUNT(*) FROM pengajuan p {$where}");
        $stmt->execute($params);
        $totalRows = (int)$stmt->fetchColumn();

        $divisiList = $db->query("SELECT id, nama_divisi FROM divisi ORDER BY nama_divisi ASC")->fetchAll(\PDO::FETCH_ASSOC);

        $this->renderView('sekretariat/laporan', [
            'title' => 'Laporan & Ekspor',
            'subtitle' => 'Ringkasan data pengajuan magang untuk keperluan pelaporan.',
            'currentPage' => 'laporan',
            'distribusi_status' => $distribusi_status,
            'distribusi_divisi' => $distribusi_divisi,
            'divisiList' => $divisiList,
            'totalRows' => $totalRows
        ]);
    }

    public function exportLaporan() {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $filter = $_GET['filter'] ?? 'semua';

        list($kondisi, $params) = $this->filterLaporan();
        $klausa = [];
        if ($kondisi !== '') {
            $klausa[] = $kondisi;
        }

        if ($filter === 'aktif') {
            $klausa[] = "p.status = 'sedang_magang'";
        } elseif ($filter === 'selesai') {
            $klausa[] = "p.status = 'selesai'";
        } elseif ($filter === 'ditolak') {
            $klausa[] = "p.status IN ('ditolak', 'mengundurkan_diri')";
        } elseif ($filter === 'baru') {
            $klausa[] = "p.status IN ('diajukan', 'dalam_verifikasi', 'revisi', 'diterima')";
        }

        $whereClause = empty($klausa) ? '' : 'WHERE ' . implode(' AND ', $klausa);

        $stmt = $db->prepare("
            SELECT p.nomor_pengajuan, u.nama, u.email, mp.universitas, mp.program_studi,
                   p.status, p.tanggal_mulai_rencana, p.tanggal_selesai_rencana,
                   dp.nama_divisi as divisi_preferensi, df.nama_divisi as divisi_final
            FROM pengajuan p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN mahasiswa_profiles mp ON u.id = mp.user_id
            LEFT JOIN divisi dp ON p.divisi_id_preferensi = dp.id
            LEFT JOIN divisi df ON p.divisi_id_final = df.id
            {$whereClause}
            ORDER BY p.created_at DESC
        ");
        $stmt->execute($params);
        $pengajuanRaw = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $filename = "laporan_magang_" . $filter . "_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');

        // BOM UTF-8 agar Excel membaca huruf beraksen dengan benar
        fwrite($output, "\xEF\xBB\xBF");

        $this->tulisBarisCsv($output, [
            'Nomor Pengajuan', 'Nama Mahasiswa', 'Email', 'Universitas', 'Program Studi',
            'Status', 'Tanggal Mulai', 'Tanggal Selesai', 'Divisi Preferensi', 'Divisi Final'
        ]);

        foreach ($pengajuanRaw as $row) {
            $this->tulisBarisCsv($output, [
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
            Session::setFlash('error', ErrorHandler::userMessage($e));
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
            Session::setFlash('error', ErrorHandler::userMessage($e));
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
                Session::setFlash('error', ErrorHandler::userMessage($e));
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
                Session::setFlash('error', ErrorHandler::userMessage($e));
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
                Session::setFlash('error', ErrorHandler::userMessage($e));
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
                Session::setFlash('error', ErrorHandler::userMessage($e));
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

                $this->pengajuanService->menawarkanDivisi($pengajuanId, $divisiTawaranId, Auth::id());
                Session::setFlash('success', 'Tawaran divisi berhasil dikirim ke mahasiswa.');
            } catch (\Exception $e) {
                Session::setFlash('error', ErrorHandler::userMessage($e));
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

                $this->pengajuanService->finalisasiTawaran($pengajuanId, Auth::id());
                Session::setFlash('success', 'Finalisasi penempatan berhasil. Status pengajuan menjadi diterima.');
            } catch (\Exception $e) {
                Session::setFlash('error', ErrorHandler::userMessage($e));
            }
            return $this->redirect('sekretariat/pengajuan/detail/' . ($pengajuanId ?? ''));
        }
        return $this->redirect('sekretariat/pengajuan');
    }
}
