<?php
require_once __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Turn off FlightPHP error handler so you see real PHP errors
Flight::set('flight.handle_errors', false);

// Register services (professor-style namespaces)
Flight::register('UserService', 'services\\UserService');
Flight::register('ProjectService', 'services\\ProjectService');
Flight::register('SkillService', 'services\\SkillService');
Flight::register('ExperienceService', 'services\\ExperienceService');
Flight::register('ContactService', 'services\\ContactService');
Flight::register('AuthMiddleware', 'middleware\\AuthMiddleware');

// Global JWT verification middleware using Flight::before()
Flight::before('start', function() {
    // Skip authentication for login/register/contact/docs and health check
    $url = Flight::request()->url;
    $method = Flight::request()->method;
    
    // Public routes (no auth required)
    $isPublicRoute = (
        // Health check - just root
        ($url === '/' && $method === 'GET') ||
        // Auth routes
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0 ||
        // Contact form is public POST only
        (strpos($url, '/contact') === 0 && $method === 'POST') ||
        // API docs
        strpos($url, '/public/v1/docs') === 0
    );
    
    if ($isPublicRoute) {
        return TRUE;
    }
    
    // Protected routes - require JWT token
    try {
        $authMiddleware = Flight::AuthMiddleware();
        $token = $authMiddleware->extractToken($_SERVER);
        if($authMiddleware->verifyToken($token))
            return TRUE;
    } catch (\Exception $e) {
        Flight::halt(401, json_encode(['message' => $e->getMessage()]));
    }
});

// Load routes
require_once __DIR__ . '/routes/AuthRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/ProjectRoutes.php';
require_once __DIR__ . '/routes/SkillRoutes.php';
require_once __DIR__ . '/routes/ExperienceRoutes.php';
require_once __DIR__ . '/routes/ContactRoutes.php';

// Root test route
Flight::route('GET /', function() {
    echo json_encode([
        "message" => "Portfolio Backend is WORKING!",
        "status" => "success",
        "timestamp" => date("Y-m-d H:i:s")
    ]);
});

// Start the framework
Flight::start();