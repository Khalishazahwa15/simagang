<?php
// Base URL detection for Laragon/localhost
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$path = str_replace(['/public/index.php', '/index.php'], '', $_SERVER['SCRIPT_NAME'] ?? '');
// Ensure path doesn't end with a slash if it's just the directory
$path = rtrim($path, '/');
$baseUrl = $protocol . '://' . $host . $path;

define('BASE_URL', $baseUrl);
define('APP_NAME', 'SIMAGANG Bappeda Provinsi Lampung');
define('UPLOAD_DIR', ROOT_PATH . '/storage/uploads');

/**
 * URL aset beserta penanda versinya.
 *
 * Penandanya diambil dari waktu ubah berkas, bukan time(). Dengan time(),
 * setiap kunjungan menghasilkan URL berbeda sehingga peramban tidak pernah
 * bisa menyimpan salinannya; sebaliknya tanpa penanda sama sekali, perubahan
 * CSS tidak sampai ke pengguna sampai mereka menekan Ctrl+F5.
 */
function aset($jalur) {
    $jalur = ltrim($jalur, '/');
    $berkas = ROOT_PATH . '/public/' . $jalur;
    $versi = is_file($berkas) ? filemtime($berkas) : null;

    return BASE_URL . '/' . $jalur . ($versi ? '?v=' . $versi : '');
}

// Header keamanan. Dikirim untuk seluruh permintaan web, bukan hanya halaman
// tertentu, sehingga tidak ada halaman yang terlewat.
if (PHP_SAPI !== 'cli' && !headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    // Seluruh gaya dan skrip berasal dari domain sendiri, kecuali gaya sebaris
    // yang masih banyak dipakai tampilan.
    header("Content-Security-Policy: default-src 'self'; "
        . "style-src 'self' 'unsafe-inline'; "
        . "script-src 'self' 'unsafe-inline'; "
        . "img-src 'self' data:; "
        . "form-action 'self'; "
        . "frame-ancestors 'self'; "
        . "base-uri 'self'");
}

// Environment & Error Handling
define('APP_ENV', \App\Core\Env::get('APP_ENV', 'production'));

if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/storage/logs/error.log');
}
