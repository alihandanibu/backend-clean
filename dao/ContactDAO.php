<?php
require_once 'BaseDAO.php';

class ContactDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('contacts');
    }

    // KORISTI BASEDAO METODU UMESTO CUSTOM QUERY-JA
    public function findByStatus($status) {
        return $this->findByColumn('status', $status, 'created_at DESC');
    }
}
?>