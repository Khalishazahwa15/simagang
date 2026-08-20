<?php
namespace App\Core;

/**
 * Potongan SQL yang tidak sama antara MySQL dan PostgreSQL.
 *
 * Hampir seluruh kueri aplikasi ini berjalan apa adanya di kedua penggerak.
 * Yang dikumpulkan di sini hanya bagian yang benar-benar berbeda, supaya
 * perbedaannya berada di satu berkas alih-alih tersebar sebagai percabangan
 * di dalam controller.
 */
class Sql {
    public static function isPgsql() {
        return defined('DB_DRIVER') && DB_DRIVER === 'pgsql';
    }

    /**
     * Pembanding pencarian teks yang mengabaikan huruf besar-kecil.
     *
     * MySQL membandingkan LIKE tanpa peduli huruf besar-kecil, PostgreSQL
     * peduli. Tanpa ILIKE, mencari "najwa" tidak menemukan "Najwa" dan tidak
     * ada galat yang muncul — hasilnya sekadar kosong.
     */
    public static function searchText($kolom) {
        return $kolom . (self::isPgsql() ? ' ILIKE ?' : ' LIKE ?');
    }

    /**
     * Pembanding pencarian untuk kolom angka.
     *
     * PostgreSQL tidak mengizinkan LIKE langsung pada kolom integer, jadi
     * kolomnya perlu diubah ke teks lebih dulu. Kata kunci tipe hasil CAST
     * berbeda di kedua penggerak.
     */
    public static function searchNumber($kolom) {
        return self::isPgsql()
            ? 'CAST(' . $kolom . ' AS TEXT) LIKE ?'
            : 'CAST(' . $kolom . ' AS CHAR) LIKE ?';
    }

    /**
     * Waktu sekarang ditambah sejumlah jam tetap.
     */
    public static function nowPlusHours($jam) {
        $jam = (int) $jam;
        return self::isPgsql()
            ? "NOW() + INTERVAL '" . $jam . " hours'"
            : 'DATE_ADD(NOW(), INTERVAL ' . $jam . ' HOUR)';
    }

    /**
     * Waktu sekarang dikurangi sejumlah menit yang datang sebagai parameter.
     * Menambah satu placeholder pada kueri.
     */
    public static function nowMinusMinutesParam() {
        return self::isPgsql()
            ? "NOW() - INTERVAL '1 minute' * CAST(? AS INT)"
            : 'DATE_SUB(NOW(), INTERVAL ? MINUTE)';
    }

    /**
     * Sebuah ungkapan waktu ditambah sejumlah menit yang datang sebagai
     * parameter. Menambah satu placeholder pada kueri.
     */
    public static function plusMinutesParam($ungkapan) {
        return self::isPgsql()
            ? $ungkapan . " + INTERVAL '1 minute' * CAST(? AS INT)"
            : 'DATE_ADD(' . $ungkapan . ', INTERVAL ? MINUTE)';
    }

    /**
     * Selisih detik dari sekarang menuju sebuah ungkapan waktu.
     */
    public static function secondsFromNow($ungkapan) {
        return self::isPgsql()
            ? 'EXTRACT(EPOCH FROM (' . $ungkapan . ' - NOW()))'
            : 'TIMESTAMPDIFF(SECOND, NOW(), ' . $ungkapan . ')';
    }

    /**
     * Ruang tabel yang sedang aktif, untuk kueri ke information_schema.
     *
     * MySQL memisahkan tabel per basis data dan menyebutnya lewat DATABASE(),
     * PostgreSQL memisahkannya per schema.
     */
    public static function currentSchema() {
        return self::isPgsql() ? 'current_schema()' : 'DATABASE()';
    }

    /**
     * Id baris yang baru saja dibuat.
     *
     * PostgreSQL memerlukan nama sequence-nya; tanpa itu lastInsertId() tidak
     * mengembalikan id baris yang dimaksud.
     */
    public static function lastInsertId(\PDO $pdo, $tabel) {
        return self::isPgsql()
            ? $pdo->lastInsertId($tabel . '_id_seq')
            : $pdo->lastInsertId();
    }

    /**
     * Mengosongkan tabel beserta anak-anaknya dan mengembalikan penomoran id
     * ke awal. Dipakai seeder dan penyiapan pengujian.
     */
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
