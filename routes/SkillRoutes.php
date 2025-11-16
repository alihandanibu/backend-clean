<?php

use OpenApi\Annotations as OA;


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
\Flight::get('/users/@userId/skills', function($userId) {
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
\Flight::post('/users/@userId/skills', function($userId) {
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
\Flight::put('/users/@userId/skills/@skillId', function($userId, $skillId) {
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
\Flight::delete('/users/@userId/skills/@skillId', function($userId, $skillId) {
    $skillService = \Flight::SkillService();
    $result = $skillService->deleteSkill($userId, $skillId);
    echo json_encode($result);
});
?>
