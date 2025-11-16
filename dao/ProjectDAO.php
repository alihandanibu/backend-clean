<?php
require_once 'BaseDAO.php';

class ProjectDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('projects');
    }

    // Svi CRUD operacije su nasljeđene iz BaseDAO

    // Dodatna specifična metoda - koristi BaseDAO helper
    public function findByUserId($user_id) {
        return $this->findByColumn('user_id', $user_id, 'created_at DESC');
    }
}
?>