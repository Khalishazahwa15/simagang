<?php
namespace App\Core;

class Logger {
    /**
     * Catat kegagalan ke storage/logs/error.log.
     * Dipakai untuk galat yang tidak boleh menghentikan permintaan
     * maupun mencemari keluaran halaman.
     */
    public static function error($context, $message) {
        $logDir = ROOT_PATH . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $line = "[" . date('Y-m-d H:i:s') . "] {$context}: {$message}\n";
        file_put_contents($logDir . '/error.log', $line, FILE_APPEND);
    }
}
