<?php
require_once __DIR__ . '/../vendor/autoload.php';

class Database {
    private $host = 'localhost';
    private $db_name = 'db_rental';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        return $this->conn;
    }
}
