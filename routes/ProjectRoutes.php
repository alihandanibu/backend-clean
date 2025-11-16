<?php

use OpenApi\Annotations as OA;


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
\Flight::get('/users/@userId/projects', function($userId) {
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
\Flight::post('/users/@userId/projects', function($userId) {
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
\Flight::put('/users/@userId/projects/@projectId', function($userId, $projectId) {
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
\Flight::delete('/users/@userId/projects/@projectId', function($userId, $projectId) {
    $projectService = \Flight::ProjectService();
    $result = $projectService->deleteProject($userId, $projectId);
    echo json_encode($result);
});
?>
