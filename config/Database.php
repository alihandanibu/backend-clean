<?php
class Database {
    private $host = '127.0.0.1';
    private $port = '3307'; 
    private $db_name = 'portfolio';
    private $username = 'root';
    private $password = '';
    public $conn;

    // JWT Secret Key
    public static function JWT_SECRET() {
        return 'your_super_secret_key_portfolio_2025_xyz123';
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>