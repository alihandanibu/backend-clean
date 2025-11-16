<?php
namespace app\services;

use app\dao\UserDAO;
use app\middleware\AuthMiddleware;

class UserService {
    private $userDAO;
    private $authMiddleware;

    public function __construct() {
        $this->userDAO = new UserDAO();
        $this->authMiddleware = new AuthMiddleware();
    }

    public function register($data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Name, email, and password are required'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'bio' => $data['bio'] ?? null,
            'location' => $data['location'] ?? null
        ];

        $userId = $this->userDAO->create($userData);
        
        if ($userId) {
            return ['success' => true, 'message' => 'User registered successfully', 'user_id' => $userId];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }

    public function login($email, $password) {
        $user = $this->userDAO->getByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid password'];
        }

        $token = $this->authMiddleware->generateToken($user['id']);
        
        return [
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ];
    }

    public function getUserById($userId) {
        $user = $this->userDAO->read($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        unset($user['password']);
        return ['success' => true, 'user' => $user];
    }

    public function getAllUsers() {
        $users = $this->userDAO->readAll();
        
        foreach ($users as &$user) {
            unset($user['password']);
        }

        return ['success' => true, 'users' => $users];
    }

    public function updateUser($userId, $data) {
        $user = $this->userDAO->read($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $success = $this->userDAO->update($userId, $data);
        
        if ($success) {
            return ['success' => true, 'message' => 'User updated successfully'];
        }

        return ['success' => false, 'message' => 'Update failed'];
    }

    public function deleteUser($userId) {
        $success = $this->userDAO->delete($userId);
        
        if ($success) {
            return ['success' => true, 'message' => 'User deleted successfully'];
        }

        return ['success' => false, 'message' => 'Delete failed'];
    }
}
?>