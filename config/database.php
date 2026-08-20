<?php
// 'mysql' atau 'pgsql'
define('DB_DRIVER', \App\Core\Env::get('DB_DRIVER', 'mysql'));

define('DB_HOST', \App\Core\Env::get('DB_HOST', '127.0.0.1'));
define('DB_PORT', (int) \App\Core\Env::get('DB_PORT', DB_DRIVER === 'pgsql' ? 5432 : 3306));
define('DB_USER', \App\Core\Env::get('DB_USER', 'root'));
define('DB_PASS', \App\Core\Env::get('DB_PASS', ''));
define('DB_NAME', \App\Core\Env::get('DB_NAME', 'simagang'));
define('DB_CHARSET', 'utf8mb4');

// Supabase menolak koneksi tanpa SSL.
define('DB_SSLMODE', \App\Core\Env::get('DB_SSLMODE', 'require'));

// PostgreSQL memisahkan ruang tabel per schema, bukan per basis data.
define('DB_SCHEMA', \App\Core\Env::get('DB_SCHEMA', 'public'));

/** DSN PDO. Nama basis data boleh dikosongkan untuk menyambung ke server saja. */
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

/** Menyetel schema aktif. Tanpa ini kueri jatuh ke public. */
function terapkan_schema(PDO $pdo) {
    if (DB_DRIVER !== 'pgsql') {
        return;
    }

    if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', DB_SCHEMA)) {
        throw new RuntimeException('Nama schema tidak sah: ' . DB_SCHEMA);
    }

    $pdo->exec('SET search_path TO "' . DB_SCHEMA . '"');
}
