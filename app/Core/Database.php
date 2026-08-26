<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = dsn_basis_data();
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Prevent SQL Injection
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            terapkan_schema($this->pdo);
        } catch (PDOException $e) {
            // Pesan aslinya memuat host, nama basis data, dan nama pengguna.
            Logger::error('KONEKSI DATABASE', $e->getMessage());

            http_response_code(503);
            if (PHP_SAPI === 'cli') {
                fwrite(STDERR, "Koneksi basis data gagal. Rincian ada di storage/logs/error.log\n");
                exit(1);
            }
            exit('Layanan sedang tidak dapat diakses. Silakan coba beberapa saat lagi.');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
