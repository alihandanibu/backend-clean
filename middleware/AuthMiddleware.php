<?php
namespace middleware;

require_once __DIR__ . '/../config/Database.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    
    public function extractToken($server) {
        // Check Authorization header
        if (isset($server['HTTP_AUTHORIZATION'])) {
            $authHeader = $server['HTTP_AUTHORIZATION'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }
            return $authHeader;
        }
        
        // Check Authentication header (custom)
        if (isset($server['HTTP_AUTHENTICATION'])) {
            return $server['HTTP_AUTHENTICATION'];
        }
        
        return null;
    }

    public function verifyToken($token) {
        if (!$token) {
            \Flight::halt(401, json_encode(['valid' => false, 'message' => 'Missing authentication token']));
        }

        try {
            $decoded_token = JWT::decode($token, new Key(\Database::JWT_SECRET(), 'HS256'));
            \Flight::set('user', $decoded_token->user);
            \Flight::set('jwt_token', $token);
            return ['valid' => true, 'user' => $decoded_token->user];
        } catch (\Exception $e) {
            \Flight::halt(401, json_encode(['valid' => false, 'message' => $e->getMessage()]));
        }
    }

    public function authorizeRole($requiredRole) {
        $user = \Flight::get('user');
        if (!$user || $user->role !== $requiredRole) {
            \Flight::halt(403, json_encode(['message' => 'Access denied: insufficient privileges']));
        }
    }

    public function authorizeRoles($roles) {
        $user = \Flight::get('user');
        if (!$user || !in_array($user->role, $roles)) {
            \Flight::halt(403, json_encode(['message' => 'Forbidden: role not allowed']));
        }
    }

    public function authorizePermission($permission) {
        $user = \Flight::get('user');
        if (!$user || !isset($user->permissions) || !in_array($permission, $user->permissions)) {
            \Flight::halt(403, json_encode(['message' => 'Access denied: permission missing']));
        }
    }
}
?>