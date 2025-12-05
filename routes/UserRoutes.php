<?php

use OpenApi\Annotations as OA;

require_once __DIR__ . '/../data/Roles.php';


/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of all users",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="users", type="array", @OA\Items(type="object"))
 *         )
 *     )
 * )
 */
\Flight::route('GET /users', function() {
    // Only admins and users can see all users
    \Flight::AuthMiddleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    $userService = \Flight::UserService();
    $result = $userService->getAllUsers();
    echo json_encode($result);
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User details",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="user", type="object")
 *         )
 *     )
 * )
 */
\Flight::route('GET /users/@id', function($id) {
    // Any authenticated user can view user details
    \Flight::AuthMiddleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    $userService = \Flight::UserService();
    $result = $userService->getUserById($id);
    echo json_encode($result);
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update user",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="bio", type="string"),
 *             @OA\Property(property="location", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('PUT /users/@id', function($id) {
    // Only admin can update any user, or user can update themselves
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $id) {
        \Flight::halt(403, json_encode(['message' => 'You can only update your own profile']));
    }
    
    $input = \Flight::request()->data;
    $userService = \Flight::UserService();
    $result = $userService->updateUser($id, $input);
    echo json_encode($result);
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete user",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('DELETE /users/@id', function($id) {
    // Only admin can delete users
    \Flight::AuthMiddleware()->authorizeRole(Roles::ADMIN);
    
    $userService = \Flight::UserService();
    $result = $userService->deleteUser($id);
    echo json_encode($result);
});
?>
