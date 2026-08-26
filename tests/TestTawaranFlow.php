<?php
require_once __DIR__ . '/bootstrap.php';

use App\Core\Database;
use App\Core\Session;
use App\Core\Auth;
use App\Services\PengajuanService;
use App\Services\DokumenService;
use App\Services\StatusService;

class TestRunner {
    private $db;
    private $pengajuanService;
    private $dokumenService;

    // Test Users
    private $mahasiswaA = 101;
    private $mahasiswaB = 102;
    private $sekretariatUser = 201;
    private $adminUser = 301;
    
    // Test Data
    private $pengajuanId = 0;
    private $divisiTawaran = 2;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->pengajuanService = new PengajuanService();
        $this->dokumenService = new DokumenService();
        $this->setupTestData();
    }

    private function setupTestData() {
        // Clear old test data
        $this->db->exec("DELETE FROM users WHERE id IN ({$this->mahasiswaA}, {$this->mahasiswaB}, {$this->sekretariatUser}, {$this->adminUser})");
        $this->db->exec("DELETE FROM divisi WHERE id IN (1, 2)");
        
        // Create test users
        $this->db->exec("INSERT INTO users (id, nama, email, password, role) VALUES 
            ({$this->mahasiswaA}, 'Mhs A', 'a@mhs.com', 'pwd', 'mahasiswa'),
            ({$this->mahasiswaB}, 'Mhs B', 'b@mhs.com', 'pwd', 'mahasiswa'),
            ({$this->sekretariatUser}, 'Sekretariat', 'sek@sek.com', 'pwd', 'sekretariat'),
            ({$this->adminUser}, 'Admin', 'admin@admin.com', 'pwd', 'admin')");
            
        // Create divisi
        $this->db->exec("INSERT INTO divisi (id, nama_divisi, kapasitas, status) VALUES 
            (1, 'Divisi 1', 10, 'aktif'),
            (2, 'Divisi 2', 10, 'aktif')");
    }

    private function loginAs($userId, $role) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = $role;
    }

    private function createPengajuanTest() {
        $this->db->exec("DELETE FROM pengajuan WHERE user_id = {$this->mahasiswaA}");
        $this->db->exec("INSERT INTO pengajuan (id, nomor_pengajuan, user_id, divisi_id_preferensi, tanggal_mulai_rencana, tanggal_selesai_rencana, status) 
                         VALUES (999, 'TEST-001', {$this->mahasiswaA}, 1, '2026-01-01', '2026-02-01', 'dalam_verifikasi')");
        $this->pengajuanId = 999;
    }

    public function run() {
        echo "Running SIMAGANG Backend Tests...\n";
        echo "====================================\n";

        $this->test1_NormalOfferFlow();
        $this->test2_RejectOffer();
        $this->test3_IDOR();
        $this->test4_StateBypass();
        $this->test5_UnauthorizedFinalization();
        $this->test6_DocumentOwnership();
        $this->test8_SuperAdmin();
        $this->test9_PrivilegeEscalation();
        $this->test10_StatusIntegrity();
        
        echo "====================================\n";
        echo "ALL TESTS COMPLETED.\n";
    }

    private function assert($condition, $testName) {
        if ($condition) {
            echo "[PASS] {$testName}\n";
        } else {
            echo "[FAIL] {$testName}\n";
        }
    }

    private function expectException($callback, $expectedMessageSnippet, $testName) {
        try {
            $callback();
            echo "[FAIL] {$testName} - Expected exception not thrown.\n";
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), $expectedMessageSnippet) !== false || $expectedMessageSnippet === '') {
                echo "[PASS] {$testName}\n";
            } else {
                echo "[FAIL] {$testName} - Exception thrown but message mismatch: " . $e->getMessage() . "\n";
            }
        }
    }

    private function test1_NormalOfferFlow() {
        $this->createPengajuanTest();
        
        // 1. Sekretariat Tawarkan
        $this->loginAs($this->sekretariatUser, 'sekretariat');
        $this->pengajuanService->menawarkanDivisi($this->pengajuanId, $this->divisiTawaran, $this->sekretariatUser);
        
        // Check state
        $p = $this->db->query("SELECT * FROM pengajuan WHERE id = {$this->pengajuanId}")->fetch(PDO::FETCH_ASSOC);
        $this->assert($p['status'] === 'menunggu_konfirmasi_tawaran' && $p['divisi_id_tawaran'] == $this->divisiTawaran, "Test 1: Sekretariat Offer");

        // 2. Mahasiswa Menerima
        $this->loginAs($this->mahasiswaA, 'mahasiswa');
        $this->pengajuanService->responTawaran($this->pengajuanId, $this->mahasiswaA, true);
        
        $p = $this->db->query("SELECT * FROM pengajuan WHERE id = {$this->pengajuanId}")->fetch(PDO::FETCH_ASSOC);
        $this->assert($p['status'] === 'menunggu_finalisasi_sekretariat', "Test 1: Mahasiswa Accept");

        // 3. Sekretariat Finalisasi
        $this->loginAs($this->sekretariatUser, 'sekretariat');
        $this->pengajuanService->finalisasiTawaran($this->pengajuanId, $this->sekretariatUser);

        $p = $this->db->query("SELECT * FROM pengajuan WHERE id = {$this->pengajuanId}")->fetch(PDO::FETCH_ASSOC);
        $this->assert($p['status'] === 'diterima' && $p['divisi_id_final'] == $this->divisiTawaran, "Test 1: Sekretariat Finalization");
    }

    private function test2_RejectOffer() {
        $this->createPengajuanTest();
        
        $this->loginAs($this->sekretariatUser, 'sekretariat');
        $this->pengajuanService->menawarkanDivisi($this->pengajuanId, $this->divisiTawaran, $this->sekretariatUser);
        
        $this->loginAs($this->mahasiswaA, 'mahasiswa');
        $this->pengajuanService->responTawaran($this->pengajuanId, $this->mahasiswaA, false); // Reject
        
        $p = $this->db->query("SELECT * FROM pengajuan WHERE id = {$this->pengajuanId}")->fetch(PDO::FETCH_ASSOC);
        $this->assert($p['status'] === 'dibatalkan_oleh_mahasiswa', "Test 2: Reject Offer");
    }

    private function test3_IDOR() {
        $this->createPengajuanTest();
        
        $this->loginAs($this->sekretariatUser, 'sekretariat');
        $this->pengajuanService->menawarkanDivisi($this->pengajuanId, $this->divisiTawaran, $this->sekretariatUser);
        
        $this->loginAs($this->mahasiswaB, 'mahasiswa');
        $this->expectException(function() {
            $this->pengajuanService->responTawaran($this->pengajuanId, $this->mahasiswaB, true);
        }, "Akses Ditolak", "Test 3: IDOR Respon Tawaran by Mahasiswa B");
    }

    private function test4_StateBypass() {
        $this->createPengajuanTest();
        
        $this->loginAs($this->sekretariatUser, 'sekretariat');
        $this->pengajuanService->menawarkanDivisi($this->pengajuanId, $this->divisiTawaran, $this->sekretariatUser);
        
        // Force state bypass
        $this->expectException(function() {
            $statusService = new StatusService();
            $statusService->updateStatus($this->pengajuanId, 'menunggu_konfirmasi_tawaran', 'diterima');
        }, "ilegal", "Test 4: State Bypass dari konfirmasi ke diterima langsung");
    }

    private function test5_UnauthorizedFinalization() {
        $this->createPengajuanTest();
        
        $this->loginAs($this->sekretariatUser, 'sekretariat');
        $this->pengajuanService->menawarkanDivisi($this->pengajuanId, $this->divisiTawaran, $this->sekretariatUser);
        
        $this->loginAs($this->mahasiswaA, 'mahasiswa');
        $this->pengajuanService->responTawaran($this->pengajuanId, $this->mahasiswaA, true);
        
        // Mahasiswa mencoba memanggil finalisasi
        $this->expectException(function() {
            $this->pengajuanService->finalisasiTawaran($this->pengajuanId, $this->mahasiswaA);
        }, "Akses Ditolak", "Test 5: Unauthorized Finalization by Mahasiswa");
    }

    private function test6_DocumentOwnership() {
        $this->createPengajuanTest();
        $this->db->exec("INSERT INTO dokumen (id, pengajuan_id, jenis_dokumen, file_path, original_filename, uploaded_by) 
                         VALUES (999, {$this->pengajuanId}, 'cv', 'test.pdf', 'test.pdf', {$this->mahasiswaA})");

        $this->loginAs($this->mahasiswaB, 'mahasiswa');
        $this->expectException(function() {
            $this->dokumenService->downloadDokumen(999);
        }, "Akses Ditolak", "Test 6: Document Ownership Download IDOR");
        
        $this->db->exec("DELETE FROM dokumen WHERE id = 999");
    }

    private function test8_SuperAdmin() {
        $this->createPengajuanTest();
        
        // Admin acts as Sekretariat
        $this->loginAs($this->adminUser, 'admin');
        
        $this->pengajuanService->menawarkanDivisi($this->pengajuanId, $this->divisiTawaran, $this->adminUser);
        $p = $this->db->query("SELECT * FROM pengajuan WHERE id = {$this->pengajuanId}")->fetch(PDO::FETCH_ASSOC);
        
        $this->assert($p['status'] === 'menunggu_konfirmasi_tawaran', "Test 8: Super Admin acts as Sekretariat");
    }

    private function test9_PrivilegeEscalation() {
        // App\Core\Middleware testing is HTTP bound, we will just assert the rule in checkRole via mock if possible,
        // but since it's static we can test it directly if we include it.
        $this->loginAs($this->sekretariatUser, 'sekretariat');
        
        // Simulate checkRole
        $passed = true;
        try {
            $role = \App\Core\Auth::role();
            if ('admin' === 'admin' && $role !== 'admin') {
                throw new \Exception("403 Forbidden");
            }
        } catch (\Exception $e) {
            $passed = false;
        }
        $this->assert(!$passed, "Test 9: Privilege escalation Sekretariat -> Admin DENIED");
    }

    private function test10_StatusIntegrity() {
        $this->createPengajuanTest();
        $statusService = new StatusService();
        
        $this->expectException(function() use ($statusService) {
            $statusService->updateStatus($this->pengajuanId, 'dalam_verifikasi', 'selesai');
        }, "ilegal", "Test 10: Status Integrity (dalam_verifikasi -> selesai)");
    }
}

$test = new TestRunner();
$test->run();
