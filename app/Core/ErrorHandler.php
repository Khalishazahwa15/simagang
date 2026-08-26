<?php
namespace App\Core;

class ErrorHandler {
    /**
     * Ubah exception menjadi pesan yang aman ditampilkan ke pengguna.
     * Galat basis data hanya dicatat ke log; pengguna cukup menerima pesan umum
     * agar struktur tabel dan kueri tidak bocor ke layar.
     */
    public static function userMessage($e) {
        if ($e instanceof \PDOException) {
            Logger::error('DATABASE', $e->getMessage());
            return 'Terjadi kesalahan pada sistem. Silakan coba lagi atau hubungi Administrator.';
        }

        return $e->getMessage();
    }
}
