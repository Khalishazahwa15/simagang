<?php
/**
 * Pemasang basis data. Satu perintah untuk instalasi baru maupun instalasi
 * lama yang perlu dimutakhirkan, pada MySQL maupun PostgreSQL:
 *
 *   php database/pasang.php
 *
 * Aman dijalankan berulang. Tabel yang sudah ada tidak dibuat ulang dan
 * datanya tidak disentuh; gunakan database/seeder.php bila ingin mengisi
 * data contoh.
 */
require_once dirname(__DIR__) . '/app/Core/Env.php';
require_once dirname(__DIR__) . '/app/Core/Sql.php';

if (getenv('SIMAGANG_ENV')) {
    \App\Core\Env::load(dirname(__DIR__) . '/' . basename(getenv('SIMAGANG_ENV')));
}
\App\Core\Env::load(dirname(__DIR__) . '/.env');
require_once dirname(__DIR__) . '/config/database.php';

$akar = dirname(__DIR__);
$opsi = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    if (DB_DRIVER === 'pgsql') {
        // Supabase menyediakan basis datanya sendiri; kita hanya mengisi schema.
        $pdo = new PDO(dsn_basis_data(), DB_USER, DB_PASS, $opsi);
        terapkan_schema($pdo);
        $pdo->exec(file_get_contents($akar . '/database/schema.pgsql.sql'));
        echo "Skema PostgreSQL diterapkan pada schema '" . DB_SCHEMA . "'.\n";
    } else {
        $server = new PDO(dsn_basis_data(''), DB_USER, DB_PASS, $opsi);
        $server->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME
            . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $server->exec('USE `' . DB_NAME . '`');

        // Nama basis data diambil dari .env, bukan dari yang tertulis di skema.
        $skema = file_get_contents($akar . '/database/schema.sql');
        $skema = preg_replace('/^\s*(CREATE DATABASE|USE)\b[^;]*;\s*/mi', '', $skema);
        $server->exec($skema);
        echo "Skema MySQL diterapkan pada basis data '" . DB_NAME . "'.\n";

        // Menambal instalasi lama. Setiap perubahan diperiksa dulu, jadi
        // instalasi baru melewatinya tanpa efek.
        $server->exec(file_get_contents($akar . '/database/UPGRADE.sql'));
        echo "Pemutakhiran struktur diterapkan.\n";
    }

    $cek = new PDO(dsn_basis_data(), DB_USER, DB_PASS, $opsi);
    terapkan_schema($cek);
    $jumlah = $cek->query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "Selesai. Tabel users berisi {$jumlah} baris.\n";
    echo $jumlah == 0
        ? "Jalankan `php database/seeder.php` bila ingin mengisi data contoh.\n"
        : "";
} catch (Throwable $e) {
    fwrite(STDERR, "Gagal: " . $e->getMessage() . "\n");
    exit(1);
}
