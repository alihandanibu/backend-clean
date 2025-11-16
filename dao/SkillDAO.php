<?php
require_once 'BaseDAO.php';

class SkillDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('skills');
    }

    // Svi CRUD operacije su nasljeđene iz BaseDAO

    // Dodatna specifična metoda - koristi BaseDAO helper
    public function findByUserId($user_id) {
        return $this->findByColumn('user_id', $user_id, 'proficiency DESC, name ASC');
    }

}
?>