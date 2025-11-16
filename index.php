<?php
require_once __DIR__ . '/vendor/autoload.php';

// Turn off FlightPHP error handler so you see real PHP errors
Flight::set('flight.handle_errors', false);

// Register services
Flight::register('UserService', 'app\services\UserService');
Flight::register('ProjectService', 'app\services\ProjectService');
Flight::register('SkillService', 'app\services\SkillService');
Flight::register('ExperienceService', 'app\services\ExperienceService');
Flight::register('ContactService', 'app\services\ContactService');
Flight::register('AuthMiddleware', 'app\middleware\AuthMiddleware');

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