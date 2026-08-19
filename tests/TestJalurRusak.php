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

// --- Penguncian alur keputusan Sekretariat ---
$db->exec("DELETE FROM pengajuan");
$db->exec("INSERT INTO pengajuan (nomor_pengajuan, user_id, divisi_id_preferensi, tanggal_mulai_rencana, tanggal_selesai_rencana, status)
           VALUES ('UJI-ALUR-1', 3, 1, '2026-09-01', '2026-11-30', 'diajukan')");
$idUji = (int)$db->lastInsertId();

// Sekretariat bertindak sebagai pengambil keputusan
$_SESSION['user_id'] = 2;
$_SESSION['user_role'] = 'sekretariat';

$ditolak = false;
try {
    $pengajuanService->menetapkanDiterima($idUji, 1, null, '2026-09-01', '2026-11-30', '');
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'dalam verifikasi') !== false;
}
periksa($ditolak, 'Keputusan ditolak selama berkas belum diperiksa');

$statusService->updateStatus($idUji, 'diajukan', 'dalam_verifikasi', 'Uji.');

$ditolak = false;
try {
    $pengajuanService->menawarkanDivisi($idUji, 1, 2);
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'berbeda dari preferensi') !== false;
}
periksa($ditolak, 'Tawaran ke divisi preferensi sendiri ditolak');

// Divisi penempatan mengunci ke preferensi walau formulir mengirim divisi lain
$pengajuanService->menetapkanDiterima($idUji, 3, null, '2026-09-01', '2026-11-30', '');
$final = $db->query("SELECT divisi_id_final FROM pengajuan WHERE id = {$idUji}")->fetchColumn();
periksa((int)$final === 1, 'Penempatan mengikuti preferensi, bukan nilai kiriman formulir');

$ditolak = false;
try {
    $pengajuanService->menetapkanDitolak($idUji, 'Berubah pikiran', '');
} catch (\Exception $e) {
    $ditolak = strpos($e->getMessage(), 'dalam verifikasi') !== false;
}
periksa($ditolak, 'Keputusan tidak dapat diubah setelah ditetapkan');

// Data pendaftar ikut terbawa untuk halaman detail Sekretariat
$detail = (new \App\Models\Pengajuan())->findDetailById($idUji);
periksa(!empty($detail['mahasiswa_nama']) && !empty($detail['nim']) && !empty($detail['universitas']),
    'Detail pengajuan membawa identitas pendaftar');

$db->exec("DELETE FROM pengajuan");

// --- Pemberitahuan dan email perubahan status ---
$isiService = file_get_contents(__DIR__ . '/../app/Services/StatusService.php');
preg_match_all("/case '(\\w+)':/", $isiService, $cocok);
$tanpaEmail = [];
foreach ($cocok[1] as $st) {
    if (preg_match("/case '{$st}':(.*?)break;/s", $isiService, $blok)
        && strpos($blok[1], "'email' => true") === false) {
        $tanpaEmail[] = $st;
    }
}
periksa(empty($tanpaEmail),
    'Setiap status mengirim email ke mahasiswa' . ($tanpaEmail ? ' (belum: ' . implode(', ', $tanpaEmail) . ')' : ''));

// Status pembuka wajib tercatat dan diberitahukan, karena pembuatan pengajuan
// tidak melewati updateStatus()
$db->exec("DELETE FROM pengajuan");
$db->exec("INSERT INTO pengajuan (nomor_pengajuan, user_id, divisi_id_preferensi, tanggal_mulai_rencana, tanggal_selesai_rencana, status)
           VALUES ('UJI-AWAL-1', 3, 1, '2026-09-01', '2026-11-30', 'diajukan')");
$idAwal = (int)$db->lastInsertId();
$db->exec("DELETE FROM notifications");

$statusService->catatStatusAwal($idAwal);

$riwayat = $db->query("SELECT COUNT(*) FROM status_history WHERE pengajuan_id = {$idAwal}")->fetchColumn();
periksa((int)$riwayat === 1, 'Pengiriman pertama tercatat di riwayat status');

$keMahasiswa = $db->query("SELECT COUNT(*) FROM notifications WHERE pengajuan_id = {$idAwal} AND user_id = 3")->fetchColumn();
$keSekretariat = $db->query("SELECT COUNT(*) FROM notifications WHERE pengajuan_id = {$idAwal} AND user_id = 2")->fetchColumn();
periksa((int)$keMahasiswa >= 1, 'Mahasiswa diberi tahu pengajuannya masuk');
periksa((int)$keSekretariat >= 1, 'Sekretariat diberi tahu ada berkas baru');

// Sinkronisasi otomatis berjalan tanpa sesi pengguna
$kolom = $db->query("SELECT IS_NULLABLE FROM information_schema.COLUMNS
                     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'status_history'
                       AND COLUMN_NAME = 'changed_by'")->fetchColumn();
periksa($kolom === 'YES', 'changed_by boleh kosong untuk tindakan sistem');

$db->exec("DELETE FROM pengajuan");

// --- Temuan keamanan yang ditutup pada audit ketiga ---

// S-5: pemalsuan metode akan melewati verifikasi CSRF, karena token hanya
// diperiksa pada permintaan POST.
$isiApp = file_get_contents(__DIR__ . '/../app/Core/App.php');
periksa(strpos($isiApp, "\$_POST['_method']") === false,
    'S-5 Metode permintaan tidak dapat dipalsukan lewat _method');

// S-6: pesan galat koneksi memuat host, nama basis data, dan nama pengguna.
$isiDb = file_get_contents(__DIR__ . '/../app/Core/Database.php');
periksa(strpos($isiDb, 'die("Database Connection failed: " . $e->getMessage())') === false,
    'S-6 Detail koneksi tidak dibocorkan ke pengguna');

// Header keamanan dikirim untuk seluruh permintaan web
$isiConfig = file_get_contents(__DIR__ . '/../config/app.php');
$headerWajib = ['X-Content-Type-Options', 'X-Frame-Options', 'Referrer-Policy', 'Content-Security-Policy'];
$kurang = [];
foreach ($headerWajib as $h) {
    if (strpos($isiConfig, $h) === false) {
        $kurang[] = $h;
    }
}
periksa(empty($kurang), 'Header keamanan terpasang' . ($kurang ? ' (kurang: ' . implode(', ', $kurang) . ')' : ''));

// Pembatasan laju reset kata sandi tidak boleh ikut mengunci login
$throttle->clear('reset:uji@test.local');
$throttle->clear('uji@test.local');
for ($i = 0; $i < LoginThrottleService::MAX_ATTEMPTS; $i++) {
    $throttle->recordFailure('reset:uji@test.local');
}
periksa($throttle->lockedForMinutes('reset:uji@test.local') > 0, 'Permintaan reset kata sandi dibatasi');
periksa($throttle->lockedForMinutes('uji@test.local') === 0, 'Batas reset tidak ikut mengunci login');
$throttle->clear('reset:uji@test.local');

// Sinkronisasi tanggal tidak lagi memindai pada setiap permintaan
$penanda = ROOT_PATH . '/storage/sinkron-terakhir';
@unlink($penanda);
$sync = new \App\Services\SyncStatusService();
$sync->sync();
periksa(is_file($penanda), 'Pemindaian pertama menandai waktunya');
$waktu = filemtime($penanda);
$sync->sync();
periksa(filemtime($penanda) === $waktu, 'Pemindaian berikutnya dilewati selama masih dalam jeda');
@unlink($penanda);

// --- Periode magang yang mustahil harus ditolak ---
$db->exec("DELETE FROM pengajuan");
$berkasPalsu = [
    'surat_lamaran' => ['error' => UPLOAD_ERR_OK, 'tmp_name' => '', 'name' => 'a.pdf', 'size' => 1],
    'cv' => ['error' => UPLOAD_ERR_OK, 'tmp_name' => '', 'name' => 'b.pdf', 'size' => 1],
    'transkrip' => ['error' => UPLOAD_ERR_OK, 'tmp_name' => '', 'name' => 'c.pdf', 'size' => 1],
];

// Tanggal dibuat relatif terhadap hari ini supaya berkas ini tidak
// kedaluwarsa seiring waktu.
$plus = function ($hari) {
    return (new DateTime('today'))->modify("+{$hari} days")->format('Y-m-d');
};

$kasus = [
    [$plus(90), $plus(30), 'setelah tanggal mulai', 'Tanggal selesai sebelum tanggal mulai ditolak'],
    ['2020-01-01', '2020-03-01', 'masa lalu', 'Periode di masa lalu ditolak'],
    [$plus(30), $plus(30 + 400), 'maksimal satu tahun', 'Periode lebih dari setahun ditolak'],
    [$plus(30), $plus(33), 'minimal', 'Periode terlalu pendek ditolak'],
    ['bukan-tanggal', $plus(30), 'tanggal yang sah', 'Isian bukan tanggal ditolak'],
    [$plus(500), $plus(560), 'terlalu jauh ke depan', 'Tanggal mulai lebih dari setahun lagi ditolak'],
];

foreach ($kasus as $k) {
    list($mulai, $selesai, $petunjuk, $nama) = $k;
    $ditolak = false;
    try {
        $pengajuanService->createPengajuan(3, 1, $mulai, $selesai, $berkasPalsu);
    } catch (\Exception $e) {
        $ditolak = strpos($e->getMessage(), $petunjuk) !== false;
    }
    periksa($ditolak, $nama);
}

$db->exec("DELETE FROM pengajuan");

// --- Kontras warna teks terhadap ambang WCAG AA ---
$css = file_get_contents(__DIR__ . '/../public/assets/css/tokens.css');
preg_match_all('/--([\w-]+):\s*([^;]+);/', $css, $cocok, PREG_SET_ORDER);
$token = [];
foreach ($cocok as $c) {
    $token[$c[1]] = trim($c[2]);
}

$keRgb = function ($nilai, $dalam = 0) use (&$keRgb, $token) {
    $nilai = trim($nilai);
    if ($dalam > 8) {
        return null;
    }
    if (preg_match('/var\(--([\w-]+)\)/', $nilai, $m)) {
        return isset($token[$m[1]]) ? $keRgb($token[$m[1]], $dalam + 1) : null;
    }
    if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $nilai, $m)) {
        $h = $m[1];
        if (strlen($h) === 3) {
            $h = $h[0] . $h[0] . $h[1] . $h[1] . $h[2] . $h[2];
        }
        return [hexdec(substr($h, 0, 2)), hexdec(substr($h, 2, 2)), hexdec(substr($h, 4, 2))];
    }
    return null;
};

$luminansi = function ($rgb) {
    $k = function ($c) {
        $c = $c / 255;
        return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
    };
    return 0.2126 * $k($rgb[0]) + 0.7152 * $k($rgb[1]) + 0.0722 * $k($rgb[2]);
};

$pasangan = [
    ['text-primary', 'bg-main'],
    ['text-secondary', 'bg-main'],
    ['text-secondary', 'bg-soft'],
    ['text-muted', 'bg-main'],
    ['color-text-inverse', 'color-primary'],
    ['color-danger-ink', 'color-danger-soft'],
    ['color-warning-ink', 'color-warning-soft'],
    ['color-success-ink', 'color-success-soft'],
    ['color-info-ink', 'color-info-soft'],
    ['color-accent-dark', 'bg-main'],
    ['color-primary-dark', 'color-accent'],
];

$gagalKontras = [];
foreach ($pasangan as $p) {
    $a = $keRgb($token[$p[0]] ?? '');
    $b = $keRgb($token[$p[1]] ?? '');
    if (!$a || !$b) {
        $gagalKontras[] = $p[0] . ' (token tidak terbaca)';
        continue;
    }
    $la = $luminansi($a);
    $lb = $luminansi($b);
    $rasio = (max($la, $lb) + 0.05) / (min($la, $lb) + 0.05);
    if ($rasio < 4.5) {
        $gagalKontras[] = sprintf('%s di atas %s (%.2f)', $p[0], $p[1], $rasio);
    }
}
periksa(empty($gagalKontras),
    'Kontras teks memenuhi WCAG AA' . ($gagalKontras ? ' (gagal: ' . implode('; ', $gagalKontras) . ')' : ''));

// Warna aksen hanya untuk latar dan garis, tidak untuk teks: rasionya
// terhadap latar halaman hanya 2.88.
$berkasTampilan = glob(__DIR__ . '/../app/Views/*/*.php');
$pemakaianSalah = [];
foreach ($berkasTampilan as $bt) {
    if (preg_match('/(?<![-\w])color:\s*var\(--accent\)/', file_get_contents($bt))) {
        $pemakaianSalah[] = basename($bt);
    }
}
periksa(empty($pemakaianSalah),
    'Warna aksen tidak dipakai sebagai warna teks' . ($pemakaianSalah ? ' (' . implode(', ', $pemakaianSalah) . ')' : ''));

echo "====================================\n";
echo "JALUR RUSAK: {$lulus} lulus, {$gagal} gagal.\n";

if ($gagal > 0) {
    exit(1);
}
