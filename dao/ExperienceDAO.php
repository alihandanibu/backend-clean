<?php
require_once 'BaseDAO.php';

class ExperienceDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('experiences');
    }

    // Jednostavna metoda - koristi BaseDAO helper
    public function findByUserId($user_id) {
        return $this->findByColumn('user_id', $user_id, 'start_date DESC');
    }
    
    // Svi CRUD operacije su već u BaseDAO:
    // - create(), findAll(), findById(), update(), delete()
}
?>