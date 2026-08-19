<?php
/**
 * Penyiapan lingkungan pengujian.
 *
 * Seluruh berkas tes memuat berkas ini lebih dulu. Tujuannya satu: pengujian
 * tidak boleh menyentuh basis data yang dipakai sehari-hari. Sebelumnya tes
 * menulis langsung ke simagang_db sehingga meninggalkan pengguna dan log audit
 * palsu setiap kali dijalankan.
 *
 * Basis data uji dibuat ulang dari nol setiap kali dijalankan, memakai
 * database/schema.sql yang sama dengan produksi, lalu diisi seeder.
 * Nama basis datanya dapat diganti lewat variabel lingkungan SIMAGANG_TEST_DB.
 */

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

require_once APP_PATH . '/Core/Env.php';
\App\Core\Env::load(ROOT_PATH . '/.env');

// Paksa nama basis data uji sebelum config/database.php membaca konfigurasi.
// Env::load melewati putenv untuk kunci yang sudah ada di $_ENV/$_SERVER,
// jadi penetapan di sini menang atas isi .env.
$namaDbUji = getenv('SIMAGANG_TEST_DB') ?: 'simagang_test';
putenv('DB_NAME=' . $namaDbUji);
$_ENV['DB_NAME'] = $namaDbUji;
$_SERVER['DB_NAME'] = $namaDbUji;

// config/app.php membutuhkan SCRIPT_NAME saat dijalankan dari CLI
$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';

require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';

if (DB_NAME !== $namaDbUji) {
    fwrite(STDERR, "Pengujian dihentikan: DB_NAME menunjuk ke '" . DB_NAME . "', bukan basis data uji.\n");
    exit(1);
}

// --- Bangun ulang basis data uji ---
$dsnServer = 'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET;
$server = new PDO($dsnServer, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$server->exec("DROP DATABASE IF EXISTS `{$namaDbUji}`");
$server->exec("CREATE DATABASE `{$namaDbUji}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

$skema = file_get_contents(ROOT_PATH . '/database/schema.sql');
// Buang perintah pembuatan basis data bawaan; kita sudah membuatnya sendiri.
$skema = preg_replace('/^\s*(CREATE DATABASE|USE)\b[^;]*;\s*/mi', '', $skema);

$server->exec("USE `{$namaDbUji}`");
$server->exec($skema);

// Isi data contoh memakai seeder yang sama dengan pengembangan
ob_start();
require_once ROOT_PATH . '/database/seeder.php';
ob_end_clean();

\App\Core\Session::init();

echo "Basis data uji: {$namaDbUji} (dibangun ulang)\n";
