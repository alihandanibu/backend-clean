<?php

use OpenApi\Annotations as OA;


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
\Flight::get('/users', function() {
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
\Flight::get('/users/@id', function($id) {
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
\Flight::put('/users/@id', function($id) {
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
\Flight::delete('/users/@id', function($id) {
    $userService = \Flight::UserService();
    $result = $userService->deleteUser($id);
    echo json_encode($result);
});
?>
