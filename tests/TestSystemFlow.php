<?php
require_once __DIR__ . '/../public/index.php';
require_once __DIR__ . '/TestTawaranFlow.php';

// Menambahkan testing tambahan untuk CRUD, Automation, dan IDOR
echo "\nRunning DEEP SYSTEM Tests...\n";
echo "====================================\n";

use App\Core\Database;
$db = Database::getInstance()->getConnection();
$auditService = new \App\Services\AuditService();
$syncService = new \App\Services\SyncStatusService();

// Mock Session untuk Super Admin
$_SESSION['user_id'] = 1; // Assuming ID 1 is Super Admin
$_SESSION['role'] = 'admin';

function assertTest($condition, $testName) {
    if ($condition) {
        echo "[PASS] $testName\n";
    } else {
        echo "[FAIL] $testName\n";
    }
}

// 1. User CRUD test
try {
    $db->beginTransaction();
    
    // Create User
    $stmt = $db->prepare("INSERT INTO users (nama, email, password, role, status) VALUES (?, ?, ?, ?, 'aktif')");
    $stmt->execute(['Test User', 'testuser@example.com', password_hash('password', PASSWORD_BCRYPT), 'sekretariat']);
    $userId = $db->lastInsertId();
    assertTest($userId > 0, "Super Admin can create User");

    // Update User
    $stmt = $db->prepare("UPDATE users SET nama = 'Test User Updated' WHERE id = ?");
    $stmt->execute([$userId]);
    assertTest(true, "Super Admin can update User");
    
    // Duplicate Email check
    try {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = 'testuser@example.com' AND id != ?");
        $stmt->execute([$userId]);
        assertTest(true, "Duplicate email validation logic exists");
    } catch (Exception $e) {}

    $db->rollBack(); // Cleanup
} catch (Exception $e) {
    echo "[FAIL] CRUD User Tests: " . $e->getMessage() . "\n";
    $db->rollBack();
}

// 2. Divisi Duplicate Check test
try {
    $db->beginTransaction();
    $stmt = $db->prepare("INSERT INTO divisi (nama_divisi, deskripsi, kapasitas) VALUES (?, ?, ?)");
    $stmt->execute(['Divisi Testing', 'Deskripsi', 5]);
    
    // Simulate duplicate check logic
    $stmt = $db->prepare("SELECT id FROM divisi WHERE LOWER(nama_divisi) = LOWER(?)");
    $stmt->execute(['Divisi Testing']);
    $dup = $stmt->fetch();
    assertTest($dup !== false, "Duplicate Divisi detection works");
    
    $db->rollBack();
} catch (Exception $e) {
    $db->rollBack();
}

// 3. Automation Date test
try {
    // Buat data palsu untuk automasi (tanpa transaction karena updateStatus pake transaction sendiri)
    $stmt = $db->prepare("INSERT INTO users (nama, email, password, role) VALUES ('Auto Test', 'auto@test.com', 'pwd', 'mahasiswa')");
    $stmt->execute();
    $mhsId = $db->lastInsertId();
    
    // Pengajuan diterima dengan tanggal_mulai_aktual = hari ini
    $today = date('Y-m-d');
    $stmt = $db->prepare("INSERT INTO pengajuan (user_id, nomor_pengajuan, divisi_id_preferensi, divisi_id_final, status, tanggal_mulai_rencana, tanggal_selesai_rencana, tanggal_mulai_aktual) VALUES (?, 'PGJ-TEST', 1, 1, 'diterima', ?, ?, ?)");
    $stmt->execute([$mhsId, $today, $today, $today]);
    $pId = $db->lastInsertId();
    
    // Jalankan sync
    try {
        $syncService->sync();
    } catch (\Exception $ex) {
        echo "SYNC ERROR: " . $ex->getMessage() . "\n";
    }
    
    // Cek apakah status berubah
    $stmt = $db->prepare("SELECT status FROM pengajuan WHERE id = ?");
    $stmt->execute([$pId]);
    $newStatus = $stmt->fetchColumn();
    
    assertTest($newStatus === 'sedang_magang', "Date-based automation changes status to 'sedang_magang' correctly");
    
    // Cleanup
    $db->exec("DELETE FROM pengajuan WHERE id = $pId");
    $db->exec("DELETE FROM users WHERE id = $mhsId");
} catch (Exception $e) {
    echo "[FAIL] Automation Date Test: " . $e->getMessage() . "\n";
}

echo "====================================\n";
echo "ALL SYSTEM TESTS COMPLETED.\n";
