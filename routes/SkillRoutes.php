<?php

use OpenApi\Annotations as OA;

require_once __DIR__ . '/../data/Roles.php';


/**
 * @OA\Get(
 *     path="/users/{userId}/skills",
 *     summary="Get skills for a user",
 *     tags={"Skills"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user skills",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="skills", type="array", @OA\Items(type="object"))
 *         )
 *     )
 * )
 */
\Flight::route('GET /users/@userId/skills', function($userId) {
    // Any authenticated user can view skills
    \Flight::AuthMiddleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    $skillService = \Flight::SkillService();
    $result = $skillService->getSkillsByUser($userId);
    echo json_encode($result);
});

/**
 * @OA\Post(
 *     path="/users/{userId}/skills",
 *     summary="Add skill for a user",
 *     tags={"Skills"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","level"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="level", type="string"),
 *             @OA\Property(property="category", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Skill added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="skill_id", type="integer")
 *         )
 *     )
 * )
 */
\Flight::route('POST /users/@userId/skills', function($userId) {
    // Only admin or the user themselves can add skills
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only add skills to your own profile']));
    }
    
    $input = \Flight::request()->data;
    $skillService = \Flight::SkillService();
    $result = $skillService->addSkill($userId, $input);
    echo json_encode($result);
});

/**
 * @OA\Put(
 *     path="/users/{userId}/skills/{skillId}",
 *     summary="Update skill",
 *     tags={"Skills"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="skillId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="level", type="string"),
 *             @OA\Property(property="category", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Skill updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('PUT /users/@userId/skills/@skillId', function($userId, $skillId) {
    // Only admin or the user themselves can update their skills
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only update your own skills']));
    }
    
    $input = \Flight::request()->data;
    $skillService = \Flight::SkillService();
    $result = $skillService->updateSkill($userId, $skillId, $input);
    echo json_encode($result);
});

/**
 * @OA\Delete(
 *     path="/users/{userId}/skills/{skillId}",
 *     summary="Delete skill",
 *     tags={"Skills"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="skillId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Skill deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('DELETE /users/@userId/skills/@skillId', function($userId, $skillId) {
    // Only admin or the user themselves can delete their skills
    $currentUser = \Flight::get('user');
    if ($currentUser->role !== Roles::ADMIN && $currentUser->id != $userId) {
        \Flight::halt(403, json_encode(['message' => 'You can only delete your own skills']));
    }
    
    $skillService = \Flight::SkillService();
    $result = $skillService->deleteSkill($userId, $skillId);
    echo json_encode($result);
});
?>
