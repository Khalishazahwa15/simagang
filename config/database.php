<?php
// Penggerak basis data. 'mysql' untuk pemasangan lama, 'pgsql' untuk Supabase.
define('DB_DRIVER', \App\Core\Env::get('DB_DRIVER', 'mysql'));

define('DB_HOST', \App\Core\Env::get('DB_HOST', '127.0.0.1'));
// Port sebelumnya ada di .env tapi tidak pernah ikut dirakit ke DSN. Tidak
// ketahuan selama MySQL memakai port bawaannya; Supabase tidak.
define('DB_PORT', (int) \App\Core\Env::get('DB_PORT', DB_DRIVER === 'pgsql' ? 5432 : 3306));
define('DB_USER', \App\Core\Env::get('DB_USER', 'root'));
define('DB_PASS', \App\Core\Env::get('DB_PASS', ''));
define('DB_NAME', \App\Core\Env::get('DB_NAME', 'simagang'));
define('DB_CHARSET', 'utf8mb4');

// Supabase menolak koneksi tanpa SSL.
define('DB_SSLMODE', \App\Core\Env::get('DB_SSLMODE', 'require'));

// PostgreSQL memisahkan ruang tabel dengan schema, bukan dengan basis data
// terpisah seperti MySQL. Supabase hanya menyediakan satu basis data, jadi
// pemisahan lingkungan uji dilakukan di sini.
define('DB_SCHEMA', \App\Core\Env::get('DB_SCHEMA', 'public'));

/**
 * DSN PDO untuk penggerak yang sedang dipakai.
 *
 * Nama basis data boleh dikosongkan; dipakai saat perlu tersambung ke server
 * lebih dulu sebelum basis datanya sendiri ada, misalnya oleh pengujian.
 */
function dsn_basis_data($namaBasisData = DB_NAME) {
    if (DB_DRIVER === 'pgsql') {
        $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT;
        if ($namaBasisData !== '') {
            $dsn .= ';dbname=' . $namaBasisData;
        }
        return $dsn . ';sslmode=' . DB_SSLMODE;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT;
    if ($namaBasisData !== '') {
        $dsn .= ';dbname=' . $namaBasisData;
    }
    return $dsn . ';charset=' . DB_CHARSET;
}

/**
 * Menyetel schema aktif untuk koneksi PostgreSQL.
 *
 * Tanpa ini seluruh kueri jatuh ke schema public, sehingga pengujian akan
 * menimpa data yang dipakai sehari-hari. Tidak berlaku untuk MySQL.
 */
function terapkan_schema(PDO $pdo) {
    if (DB_DRIVER !== 'pgsql') {
        return;
    }

    if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', DB_SCHEMA)) {
        throw new RuntimeException('Nama schema tidak sah: ' . DB_SCHEMA);
    }

    $pdo->exec('SET search_path TO "' . DB_SCHEMA . '"');
}
