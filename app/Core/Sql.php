<?php
namespace App\Core;

/**
 * Potongan SQL yang berbeda antara MySQL dan PostgreSQL.
 * Rinciannya di docs/MIGRASI_SUPABASE.md.
 */
class Sql {
    public static function isPgsql() {
        return defined('DB_DRIVER') && DB_DRIVER === 'pgsql';
    }

    /** Pencarian teks tanpa membedakan huruf besar-kecil. */
    public static function searchText($kolom) {
        return $kolom . (self::isPgsql() ? ' ILIKE ?' : ' LIKE ?');
    }

    /** PostgreSQL menolak LIKE pada kolom angka. */
    public static function searchNumber($kolom) {
        return self::isPgsql()
            ? 'CAST(' . $kolom . ' AS TEXT) LIKE ?'
            : 'CAST(' . $kolom . ' AS CHAR) LIKE ?';
    }

    public static function nowPlusHours($jam) {
        $jam = (int) $jam;
        return self::isPgsql()
            ? "NOW() + INTERVAL '" . $jam . " hours'"
            : 'DATE_ADD(NOW(), INTERVAL ' . $jam . ' HOUR)';
    }

    /** Menambah satu placeholder pada kueri. */
    public static function nowMinusMinutesParam() {
        return self::isPgsql()
            ? "NOW() - INTERVAL '1 minute' * CAST(? AS INT)"
            : 'DATE_SUB(NOW(), INTERVAL ? MINUTE)';
    }

    /** Menambah satu placeholder pada kueri. */
    public static function plusMinutesParam($ungkapan) {
        return self::isPgsql()
            ? $ungkapan . " + INTERVAL '1 minute' * CAST(? AS INT)"
            : 'DATE_ADD(' . $ungkapan . ', INTERVAL ? MINUTE)';
    }

    public static function secondsFromNow($ungkapan) {
        return self::isPgsql()
            ? 'EXTRACT(EPOCH FROM (' . $ungkapan . ' - NOW()))'
            : 'TIMESTAMPDIFF(SECOND, NOW(), ' . $ungkapan . ')';
    }

    /** Ruang tabel aktif, untuk kueri ke information_schema. */
    public static function currentSchema() {
        return self::isPgsql() ? 'current_schema()' : 'DATABASE()';
    }

    /** PostgreSQL memerlukan nama sequence-nya. */
    public static function lastInsertId(\PDO $pdo, $tabel) {
        return self::isPgsql()
            ? $pdo->lastInsertId($tabel . '_id_seq')
            : $pdo->lastInsertId();
    }

    /** Mengosongkan tabel beserta anaknya dan mengembalikan penomoran id. */
    public static function truncate(\PDO $pdo, array $tabel) {
        if (self::isPgsql()) {
            $pdo->exec('TRUNCATE TABLE ' . implode(', ', $tabel) . ' RESTART IDENTITY CASCADE');
            return;
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tabel as $nama) {
            $pdo->exec('TRUNCATE TABLE ' . $nama);
        }
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}
