<?php
namespace services;

use dao\UserDAO;

class UserService {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    public function register($data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Name, email, and password are required'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // POPRAVLJENO: findByEmail umjesto getByEmail
        if ($this->userDAO->findByEmail($data['email'])) {
             return ['success' => false, 'message' => 'Email already exists'];
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
        // POPRAVLJENO: findByEmail umjesto getByEmail
        $user = $this->userDAO->findByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid password'];
        }

        // Generate JWT token
        unset($user['password']);
        
        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) // valid for 1 day
        ];

        $token = \Firebase\JWT\JWT::encode(
            $jwt_payload,
            \Database::JWT_SECRET(),
            'HS256'
        );

        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ];
    }

    public function getUserById($userId) {
        // POPRAVLJENO: findById umjesto read
        $user = $this->userDAO->findById($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        unset($user['password']);
        return ['success' => true, 'user' => $user];
    }

    public function getAllUsers() {
        // POPRAVLJENO: findAll umjesto readAll
        $users = $this->userDAO->findAll();
        
        foreach ($users as &$user) {
            unset($user['password']);
        }

        return ['success' => true, 'users' => $users];
    }

    public function updateUser($userId, $data) {
        // POPRAVLJENO: findById umjesto read
        $user = $this->userDAO->findById($userId);
        
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