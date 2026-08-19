<?php
namespace App\Core;

use PDO;

abstract class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getDb() {
        return $this->db;
    }

    private static $transactionDepth = 0;

    public function beginTransaction() {
        if (self::$transactionDepth == 0) {
            $this->db->beginTransaction();
        }
        self::$transactionDepth++;
    }

    public function commit() {
        self::$transactionDepth--;
        if (self::$transactionDepth == 0) {
            $this->db->commit();
        }
    }

    public function rollBack() {
        if (self::$transactionDepth > 0) {
            self::$transactionDepth = 0;
            $this->db->rollBack();
        }
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
