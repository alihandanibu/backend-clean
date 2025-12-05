<?php

use OpenApi\Annotations as OA;

require_once __DIR__ . '/../data/Roles.php';


/**
 * @OA\Get(
 *     path="/users/{userId}/projects",
 *     summary="Get projects for a user",
 *     tags={"Projects"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user projects",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="projects", type="array", @OA\Items(type="object"))
 *         )
 *     )
 * )
 */
\Flight::route('GET /users/@userId/projects', function($userId) {
    // Any authenticated user can view projects
    \Flight::AuthMiddleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    $projectService = \Flight::ProjectService();
    $result = $projectService->getProjectsByUser($userId);
    echo json_encode($result);
});

/**
 * @OA\Post(
 *     path="/users/{userId}/projects",
 *     summary="Add project for a user",
 *     tags={"Projects"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title"},
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="technologies", type="string"),
 *             @OA\Property(property="link", type="string", format="uri"),
 *             @OA\Property(property="start_date", type="string", format="date"),
 *             @OA\Property(property="end_date", type="string", format="date")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Project added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="project_id", type="integer")
 *         )
 *     )
 * )
 */
\Flight::route('POST /users/@userId/projects', function($userId) {
    // Only admin or the user themselves can add projects to their profile
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only add projects to your own profile']));
    }
    
    $input = \Flight::request()->data;
    $projectService = \Flight::ProjectService();
    $result = $projectService->addProject($userId, $input);
    echo json_encode($result);
});

/**
 * @OA\Put(
 *     path="/users/{userId}/projects/{projectId}",
 *     summary="Update project",
 *     tags={"Projects"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="projectId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="technologies", type="string"),
 *             @OA\Property(property="link", type="string", format="uri"),
 *             @OA\Property(property="start_date", type="string", format="date"),
 *             @OA\Property(property="end_date", type="string", format="date")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Project updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('PUT /users/@userId/projects/@projectId', function($userId, $projectId) {
    // Only admin or the user themselves can update their projects
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only update your own projects']));
    }
    
    $input = \Flight::request()->data;
    $projectService = \Flight::ProjectService();
    $result = $projectService->updateProject($userId, $projectId, $input);
    echo json_encode($result);
});

/**
 * @OA\Delete(
 *     path="/users/{userId}/projects/{projectId}",
 *     summary="Delete project",
 *     tags={"Projects"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="projectId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Project deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('DELETE /users/@userId/projects/@projectId', function($userId, $projectId) {
    // Only admin or the user themselves can delete their projects
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only delete your own projects']));
    }
    
    $projectService = \Flight::ProjectService();
    $result = $projectService->deleteProject($userId, $projectId);
    echo json_encode($result);
});
?>
