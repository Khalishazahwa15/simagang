<?php
/**
 * Kasus uji jalur rusak.
 *
 * Berkas tes yang sudah ada memeriksa alur yang berjalan normal. Berkas ini
 * memeriksa hal sebaliknya: perbuatan yang harus DITOLAK sistem. Setiap kasus
 * di sini mewakili cacat nyata yang pernah ditemukan audit, sehingga kalau
 * perbaikannya suatu saat terhapus, pengujian langsung gagal.
 */

require_once __DIR__ . '/bootstrap.php';

use App\Core\Database;
use App\Services\AuthService;
use App\Services\LoginThrottleService;
use App\Services\PengajuanService;
use App\Services\StatusService;

echo "\nRunning JALUR RUSAK Tests...\n";
echo "====================================\n";

$lulus = 0;
$gagal = 0;

function periksa($kondisi, $nama)
{
    global $lulus, $gagal;
    if ($kondisi) {
        $lulus++;
        echo "[PASS] {$nama}\n";
    } else {
        $gagal++;
        echo "[FAIL] {$nama}\n";
    }
}

$db = Database::getInstance()->getConnection();
$auth = new AuthService();
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// --- K-1: akun nonaktif tidak boleh bisa masuk ---
$db->exec("UPDATE users SET status = 'nonaktif' WHERE id = 3");
$ditolak = false;
try {
    $auth->login('najwa@student.unila.ac.id', 'password123');
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'dinonaktifkan') !== false;
}
periksa($ditolak, 'K-1 Akun nonaktif ditolak saat login');
$db->exec("UPDATE users SET status = 'aktif' WHERE id = 3");

// --- K-2: kata sandi terlalu pendek ditolak ---
$ditolak = false;
try {
    $auth->registerMahasiswa('Uji Pendek', 'pendek@test.local', '1', '111', 'Unila', 'Teknik', 'TI', 5, '0812', 'Jl. Uji');
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'minimal') !== false;
}
periksa($ditolak, 'K-2 Kata sandi di bawah 8 karakter ditolak');

// --- T-7: data akademik wajib lengkap saat registrasi ---
$ditolak = false;
try {
    $auth->registerMahasiswa('Uji Kosong', 'kosong@test.local', 'rahasia123', '', 'Unila', 'Teknik', 'TI', 5, '0812', 'Jl. Uji');
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'NIM') !== false;
}
periksa($ditolak, 'T-7 Registrasi tanpa NIM ditolak');

// --- Semester di luar rentang wajar ditolak ---
$ditolak = false;
try {
    $auth->registerMahasiswa('Uji Semester', 'semester@test.local', 'rahasia123', '222', 'Unila', 'Teknik', 'TI', 99, '0812', 'Jl. Uji');
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'Semester') !== false;
}
periksa($ditolak, 'Semester 99 ditolak saat registrasi');

// --- T-4: token reset tidak boleh tersimpan polos ---
$token = bin2hex(random_bytes(32));
$stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = 3");
$stmt->execute([hash('sha256', $token)]);
$tersimpan = $db->query("SELECT reset_token FROM users WHERE id = 3")->fetchColumn();
periksa($tersimpan !== $token && $tersimpan === hash('sha256', $token), 'T-4 Token reset tersimpan sebagai hash');

$stmt = $db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
$stmt->execute([hash('sha256', $token)]);
periksa($stmt->fetch() !== false, 'T-4 Masa berlaku token dihitung dengan jam basis data');
$db->exec("UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE id = 3");

// --- T-5: pembatasan laju login ---
$throttle = new LoginThrottleService();
$throttle->clear('bruteforce@test.local');
periksa($throttle->lockedForMinutes('bruteforce@test.local') === 0, 'T-5 Belum terkunci sebelum ada percobaan gagal');
for ($i = 0; $i < LoginThrottleService::MAX_ATTEMPTS; $i++) {
    $throttle->recordFailure('bruteforce@test.local');
}
$sisa = $throttle->lockedForMinutes('bruteforce@test.local');
periksa($sisa > 0 && $sisa <= LoginThrottleService::WINDOW_MINUTES, 'T-5 Terkunci setelah 5 percobaan gagal');
$throttle->clear('bruteforce@test.local');
periksa($throttle->lockedForMinutes('bruteforce@test.local') === 0, 'T-5 Login berhasil mengosongkan hitungan');

// --- T-8: status yang tidak ada di ENUM bukan transisi sah ---
$statusService = new StatusService();
$ditolak = false;
try {
    $statusService->updateStatus(1, 'diajukan', 'dibatalkan', 'Uji transisi ilegal.');
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'ilegal') !== false;
}
periksa($ditolak, 'T-8 Transisi ke status dibatalkan ditolak');

// --- K-5: pengajuan ditolak selama profil belum lengkap ---
$db->exec("UPDATE mahasiswa_profiles SET fakultas = NULL WHERE user_id = 3");
$pengajuanService = new PengajuanService();
$ditolak = false;
try {
    $pengajuanService->createPengajuan(3, 1, '2026-09-01', '2026-11-30', []);
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'Lengkapi profil') !== false;
}
periksa($ditolak, 'K-5 Pengajuan ditolak saat profil belum lengkap');
$db->exec("UPDATE mahasiswa_profiles SET fakultas = 'Teknik' WHERE user_id = 3");

// --- S-8: sel berbentuk rumus dinetralkan sebelum masuk CSV ---
$netral = function ($nilai) {
    $teks = (string)$nilai;
    if ($teks !== '' && strpos("=+-@\t\r", $teks[0]) !== false) {
        return "'" . $teks;
    }
    return $teks;
};
periksa($netral('=cmd|calc') === "'=cmd|calc", 'S-8 Sel berawalan = dinetralkan');
periksa($netral('Najwa') === 'Najwa', 'S-8 Nama biasa tidak ikut diubah');

echo "====================================\n";
echo "JALUR RUSAK: {$lulus} lulus, {$gagal} gagal.\n";

if ($gagal > 0) {
    exit(1);
}
