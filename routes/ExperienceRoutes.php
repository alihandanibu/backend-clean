<?php

use OpenApi\Annotations as OA;

require_once __DIR__ . '/../data/Roles.php';


/**
 * @OA\Get(
 *     path="/users/{userId}/experiences",
 *     summary="Get experiences for a user",
 *     tags={"Experiences"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user experiences",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="experiences", type="array", @OA\Items(type="object"))
 *         )
 *     )
 * )
 */
\Flight::route('GET /users/@userId/experiences', function($userId) {
    // Any authenticated user can view experiences
    \Flight::AuthMiddleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    $experienceService = \Flight::ExperienceService();
    $result = $experienceService->getExperienceByUser($userId);
    echo json_encode($result);
});

/**
 * @OA\Post(
 *     path="/users/{userId}/experiences",
 *     summary="Add experience for a user",
 *     tags={"Experiences"},
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
 *             required={"title","company"},
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="company", type="string"),
 *             @OA\Property(property="start_date", type="string", format="date"),
 *             @OA\Property(property="end_date", type="string", format="date"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Experience added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="experience_id", type="integer")
 *         )
 *     )
 * )
 */
\Flight::route('POST /users/@userId/experiences', function($userId) {
    // Only admin or the user themselves can add experiences
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only add experiences to your own profile']));
    }
    
    $input = \Flight::request()->data;
    $experienceService = \Flight::ExperienceService();
    $result = $experienceService->addExperience($userId, $input);
    echo json_encode($result);
});

/**
 * @OA\Put(
 *     path="/users/{userId}/experiences/{experienceId}",
 *     summary="Update experience",
 *     tags={"Experiences"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="experienceId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="company", type="string"),
 *             @OA\Property(property="start_date", type="string", format="date"),
 *             @OA\Property(property="end_date", type="string", format="date"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Experience updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('PUT /users/@userId/experiences/@experienceId', function($userId, $experienceId) {
    // Only admin or the user themselves can update their experiences
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only update your own experiences']));
    }
    
    $input = \Flight::request()->data;
    $experienceService = \Flight::ExperienceService();
    $result = $experienceService->updateExperience($userId, $experienceId, $input);
    echo json_encode($result);
});

/**
 * @OA\Delete(
 *     path="/users/{userId}/experiences/{experienceId}",
 *     summary="Delete experience",
 *     tags={"Experiences"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="experienceId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Experience deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('DELETE /users/@userId/experiences/@experienceId', function($userId, $experienceId) {
    // Only admin or the user themselves can delete their experiences
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only delete your own experiences']));
    }
    
    $experienceService = \Flight::ExperienceService();
    $result = $experienceService->deleteExperience($userId, $experienceId);
    echo json_encode($result);
});
?>
