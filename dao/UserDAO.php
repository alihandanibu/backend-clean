<?php
namespace dao;

class UserDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('users');
    }

    // Svi CRUD operacije su nasljeđene iz BaseDAO:
    // - create() za POST
    // - findAll() za GET all  
    // - findById() za GET by ID
    // - update() za PUT/PATCH
    // - delete() za DELETE

    // Dodatna specifična metoda
    public function findByEmail($email) {
        $result = $this->findByColumn('email', $email, 'id DESC');
        return $result ? $result[0] : null;
    }
}
?>