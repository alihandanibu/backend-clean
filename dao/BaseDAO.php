<?php
require_once __DIR__ . '/../config/Database.php';

abstract class BaseDAO {
    protected $conn;
    protected $table;

    public function __construct($table) {
        $this->table = $table;
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ========== CRUD OPERATIONS ==========

    // CREATE (POST) - Create operacija
    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        
        $query = "INSERT INTO " . $this->table . " (" . $columns . ") VALUES (" . $placeholders . ")";
        $stmt = $this->conn->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // READ (GET) - Get all operacija
    public function findAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ (GET) - Get by ID operacija
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE (PUT/PATCH) - Update operacija
    public function update($id, $data) {
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = $key . " = :" . $key;
        }
        $setClause = implode(", ", $setClause);
        
        $query = "UPDATE " . $this->table . " SET " . $setClause . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute();
    }

    // DELETE (DELETE) - Delete operacija
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // ========== HELPER METHODS ==========

    // Pronalazi sve zapise po bilo kojoj koloni
    public function findByColumn($column, $value, $orderBy = 'id DESC') {
        $query = "SELECT * FROM " . $this->table . " WHERE " . $column . " = :value ORDER BY " . $orderBy;
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Broji zapise po koloni
    public function countByColumn($column, $value) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE " . $column . " = :value";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
?>