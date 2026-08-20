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

require_once APP_PATH . '/Core/autoload.php';

require_once APP_PATH . '/Core/Env.php';

// Berkas .env tambahan, dipakai untuk menjalankan pengujian di atas basis data
// lain tanpa menyentuh .env sehari-hari. Dimuat lebih dulu karena nilai yang
// masuk pertama yang dipakai.
if (getenv('SIMAGANG_ENV')) {
    \App\Core\Env::load(ROOT_PATH . '/' . basename(getenv('SIMAGANG_ENV')));
}
\App\Core\Env::load(ROOT_PATH . '/.env');

// Penetapan di sini menang atas isi .env: Env::load melewati putenv untuk
// kunci yang sudah ada di $_ENV/$_SERVER.

// Pengujian tidak boleh mengirim email ke siapa pun.
putenv('MAIL_NOTIFIKASI=false');
$_ENV['MAIL_NOTIFIKASI'] = 'false';
$_SERVER['MAIL_NOTIFIKASI'] = 'false';

// Pemisahan lingkungan uji, ditetapkan sebelum config/database.php membacanya.
// MySQL memisahkannya dengan basis data tersendiri; PostgreSQL memakai schema
// tersendiri karena Supabase hanya menyediakan satu basis data.
$penggerakUji = getenv('DB_DRIVER') ?: 'mysql';

if ($penggerakUji === 'pgsql') {
    $skemaUji = getenv('SIMAGANG_TEST_SCHEMA') ?: 'simagang_test';
    putenv('DB_SCHEMA=' . $skemaUji);
    $_ENV['DB_SCHEMA'] = $skemaUji;
    $_SERVER['DB_SCHEMA'] = $skemaUji;
} else {
    $namaDbUji = getenv('SIMAGANG_TEST_DB') ?: 'simagang_test';
    putenv('DB_NAME=' . $namaDbUji);
    $_ENV['DB_NAME'] = $namaDbUji;
    $_SERVER['DB_NAME'] = $namaDbUji;
}

// config/app.php membutuhkan SCRIPT_NAME saat dijalankan dari CLI
$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';

require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';

// --- Bangun ulang lingkungan uji ---
if (DB_DRIVER === 'pgsql') {
    if (DB_SCHEMA !== $skemaUji) {
        fwrite(STDERR, "Pengujian dihentikan: DB_SCHEMA menunjuk ke '" . DB_SCHEMA . "', bukan schema uji.\n");
        exit(1);
    }

    $server = new PDO(dsn_basis_data(), DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $server->exec('DROP SCHEMA IF EXISTS "' . $skemaUji . '" CASCADE');
    $server->exec('CREATE SCHEMA "' . $skemaUji . '"');
    terapkan_schema($server);
    $server->exec(file_get_contents(ROOT_PATH . '/database/schema.pgsql.sql'));

    $lingkunganUji = 'schema ' . $skemaUji . ' (PostgreSQL)';
} else {
    if (DB_NAME !== $namaDbUji) {
        fwrite(STDERR, "Pengujian dihentikan: DB_NAME menunjuk ke '" . DB_NAME . "', bukan basis data uji.\n");
        exit(1);
    }

    $server = new PDO(dsn_basis_data(''), DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $server->exec("DROP DATABASE IF EXISTS `{$namaDbUji}`");
    $server->exec("CREATE DATABASE `{$namaDbUji}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $skema = file_get_contents(ROOT_PATH . '/database/schema.sql');
    // Buang perintah pembuatan basis data bawaan; kita sudah membuatnya sendiri.
    $skema = preg_replace('/^\s*(CREATE DATABASE|USE)\b[^;]*;\s*/mi', '', $skema);

    $server->exec("USE `{$namaDbUji}`");
    $server->exec($skema);

    $lingkunganUji = 'basis data ' . $namaDbUji . ' (MySQL)';
}

// Isi data contoh memakai seeder yang sama dengan pengembangan
ob_start();
require_once ROOT_PATH . '/database/seeder.php';
ob_end_clean();

\App\Core\Session::init();

echo "Lingkungan uji: {$lingkunganUji} - dibangun ulang\n";
