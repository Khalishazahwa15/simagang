<?php
/**
 * Membandingkan struktur basis data MySQL dengan PostgreSQL.
 *
 *   php tools/banding-skema.php
 *
 * Skema aplikasi ini ditulis dua kali: database/schema.sql untuk MySQL dan
 * database/schema.pgsql.sql untuk PostgreSQL. Perubahan yang hanya masuk ke
 * salah satunya tidak akan tertangkap pengujian — pengujian hanya menjalankan
 * satu penggerak dalam satu waktu, sehingga masing-masing tetap lulus di
 * lingkungannya sendiri. Perbedaannya baru terasa setelah dipasang.
 *
 * Alat ini membaca kedua basis data yang benar-benar berjalan, lalu
 * membandingkan daftar tabel, daftar kolom, dan apakah kolomnya boleh kosong.
 *
 * Keluar dengan kode 1 bila ada perbedaan, sehingga bisa dipasang di CI.
 */

define('ROOT_PATH', dirname(__DIR__));

/**
 * Baca berkas .env secara mandiri.
 *
 * Tidak memakai App\Core\Env karena alat ini perlu dua konfigurasi sekaligus
 * dalam satu proses, sedangkan Env menyimpannya sebagai variabel lingkungan
 * yang hanya muat satu nilai per kunci.
 */
function bacaEnv($namaBerkas) {
    $jalur = ROOT_PATH . '/' . $namaBerkas;
    if (!is_file($jalur)) {
        fwrite(STDERR, "Berkas {$namaBerkas} tidak ditemukan.\n");
        exit(1);
    }

    $nilai = [];
    foreach (file($jalur, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $baris) {
        $baris = trim($baris);
        if ($baris === '' || $baris[0] === '#' || strpos($baris, '=') === false) {
            continue;
        }
        list($kunci, $isi) = explode('=', $baris, 2);
        $nilai[trim($kunci)] = trim($isi);
    }
    return $nilai;
}

$my = bacaEnv('.env');
$pg = bacaEnv('.env.supabase');

try {
    $pdoMy = new PDO(
        'mysql:host=' . $my['DB_HOST'] . ';port=' . ($my['DB_PORT'] ?? '3306')
            . ';dbname=' . $my['DB_NAME'] . ';charset=utf8mb4',
        $my['DB_USER'], $my['DB_PASS'] ?? '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $pdoPg = new PDO(
        'pgsql:host=' . $pg['DB_HOST'] . ';port=' . $pg['DB_PORT']
            . ';dbname=' . $pg['DB_NAME'] . ';sslmode=' . ($pg['DB_SSLMODE'] ?? 'require'),
        $pg['DB_USER'], $pg['DB_PASS'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    fwrite(STDERR, "Koneksi gagal: " . $e->getMessage() . "\n");
    exit(1);
}

$schemaPg = $pg['DB_SCHEMA'] ?? 'public';

// Nama tabel dan kolom disamakan ke huruf kecil. MySQL di Windows menyimpannya
// apa adanya, PostgreSQL melipatnya ke huruf kecil.
$kolomMy = [];
$q = $pdoMy->query("SELECT TABLE_NAME, COLUMN_NAME, IS_NULLABLE
                    FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()");
foreach ($q as $r) {
    $kolomMy[strtolower($r['TABLE_NAME'])][strtolower($r['COLUMN_NAME'])] = $r['IS_NULLABLE'];
}

$kolomPg = [];
$stmt = $pdoPg->prepare("SELECT table_name, column_name, is_nullable
                         FROM information_schema.columns
                         WHERE table_schema = ?");
$stmt->execute([$schemaPg]);
foreach ($stmt as $r) {
    $kolomPg[strtolower($r['table_name'])][strtolower($r['column_name'])] = $r['is_nullable'];
}

$tabelMy = array_keys($kolomMy);
$tabelPg = array_keys($kolomPg);
sort($tabelMy);
sort($tabelPg);

$beda = [];

foreach (array_diff($tabelMy, $tabelPg) as $t) {
    $beda[] = "tabel {$t} hanya ada di MySQL";
}
foreach (array_diff($tabelPg, $tabelMy) as $t) {
    $beda[] = "tabel {$t} hanya ada di PostgreSQL";
}

foreach (array_intersect($tabelMy, $tabelPg) as $t) {
    $a = array_keys($kolomMy[$t]);
    $b = array_keys($kolomPg[$t]);
    sort($a);
    sort($b);

    foreach (array_diff($a, $b) as $k) {
        $beda[] = "{$t}.{$k} hanya ada di MySQL";
    }
    foreach (array_diff($b, $a) as $k) {
        $beda[] = "{$t}.{$k} hanya ada di PostgreSQL";
    }
    foreach (array_intersect($a, $b) as $k) {
        if ($kolomMy[$t][$k] !== $kolomPg[$t][$k]) {
            $beda[] = "{$t}.{$k} berbeda boleh-kosongnya "
                . "(MySQL {$kolomMy[$t][$k]}, PostgreSQL {$kolomPg[$t][$k]})";
        }
    }
}

$jumlahKolom = array_sum(array_map('count', $kolomMy));

echo "MySQL      : {$my['DB_NAME']} di {$my['DB_HOST']}\n";
echo "PostgreSQL : schema {$schemaPg} di {$pg['DB_HOST']}\n";
echo "Diperiksa  : " . count($tabelMy) . " tabel, {$jumlahKolom} kolom\n\n";

if (empty($beda)) {
    echo "SELARAS. Tidak ada perbedaan struktur.\n";
    exit(0);
}

echo "PERBEDAAN DITEMUKAN (" . count($beda) . "):\n";
foreach ($beda as $b) {
    echo "  - {$b}\n";
}
echo "\nPerbarui database/schema.sql dan database/schema.pgsql.sql agar sama.\n";
exit(1);
