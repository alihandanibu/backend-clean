<?php

use OpenApi\Annotations as OA;


/**
 * @OA\Post(
 *     path="/auth/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123"),
 *             @OA\Property(property="bio", type="string", example="Software developer"),
 *             @OA\Property(property="location", type="string", example="Sarajevo")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="user_id", type="integer")
 *         )
 *     )
 * )
 */
\Flight::post('/auth/register', function() {
    $input = \Flight::request()->data;
    $userService = \Flight::UserService();
    $result = $userService->register($input);
    echo json_encode($result);
});

/**
 * @OA\Post(
 *     path="/auth/login",
 *     summary="Login user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="token", type="string"),
 *             @OA\Property(property="user", type="object")
 *         )
 *     )
 * )
 */
\Flight::post('/auth/login', function() {
    $input = \Flight::request()->data;
    $userService = \Flight::UserService();
    $result = $userService->login($input['email'], $input['password']);
    echo json_encode($result);
});

/**
 * @OA\Post(
 *     path="/auth/verify",
 *     summary="Verify JWT token",
 *     tags={"Authentication"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Token is valid",
 *         @OA\JsonContent(
 *             @OA\Property(property="valid", type="boolean"),
 *             @OA\Property(property="user_id", type="integer")
 *         )
 *     )
 * )
 */
\Flight::post('/auth/verify', function() {
    $authMiddleware = \Flight::AuthMiddleware();
    $token = $authMiddleware->extractToken($_SERVER);
    
    if (!$token) {
        http_response_code(401);
        echo json_encode(['valid' => false, 'message' => 'Token not found']);
        return;
    }

    $result = $authMiddleware->verifyToken($token);
    echo json_encode($result);
});
?>
