<?php
namespace app\services;

use app\dao\ContactDAO;

class ContactService {
    private $contactDAO;

    public function __construct() {
        $this->contactDAO = new ContactDAO();
    }

    public function submitContact($data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            return ['success' => false, 'message' => 'Name, email, and message are required'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        $contactData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'] ?? 'Contact Form Submission',
            'message' => $data['message'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $contactId = $this->contactDAO->create($contactData);
        
        if ($contactId) {
            return ['success' => true, 'message' => 'Contact message submitted successfully', 'contact_id' => $contactId];
        }

        return ['success' => false, 'message' => 'Failed to submit contact message'];
    }

    public function getContactsByUser($userId) {
        $contacts = $this->contactDAO->getByUserId($userId);
        
        if (empty($contacts)) {
            return ['success' => true, 'contacts' => []];
        }

        return ['success' => true, 'contacts' => $contacts];
    }

    public function deleteContact($userId, $contactId) {
        $contact = $this->contactDAO->read($contactId);
        
        if (!$contact || $contact['user_id'] != $userId) {
            return ['success' => false, 'message' => 'Contact not found'];
        }

        $success = $this->contactDAO->delete($contactId);
        
        if ($success) {
            return ['success' => true, 'message' => 'Contact deleted successfully'];
        }

        return ['success' => false, 'message' => 'Delete failed'];
    }
}
?>
