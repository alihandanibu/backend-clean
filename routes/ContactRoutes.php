<?php

use OpenApi\Annotations as OA;

require_once __DIR__ . '/../data/Roles.php';


/**
 * @OA\Post(
 *     path="/contact",
 *     summary="Submit contact form",
 *     tags={"Contact"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","message"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="subject", type="string"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact message submitted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="contact_id", type="integer")
 *         )
 *     )
 * )
 */
\Flight::route('POST /contact', function() {
    // Public endpoint - no authentication required
    $input = \Flight::request()->data;
    $contactService = \Flight::ContactService();
    $result = $contactService->submitContact($input);
    echo json_encode($result);
});

/**
 * @OA\Get(
 *     path="/users/{userId}/contacts",
 *     summary="Get contacts for a user",
 *     tags={"Contact"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of contacts",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="contacts", type="array", @OA\Items(type="object"))
 *         )
 *     )
 * )
 */
\Flight::route('GET /users/@userId/contacts', function($userId) {
    // Only admin can view submitted contact messages
    \Flight::AuthMiddleware()->authorizeRole(Roles::ADMIN);
    
    $contactService = \Flight::ContactService();
    $result = $contactService->getContactsByUser($userId);
    echo json_encode($result);
});

/**
 * @OA\Delete(
 *     path="/users/{userId}/contacts/{contactId}",
 *     summary="Delete contact",
 *     tags={"Contact"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="contactId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
\Flight::route('DELETE /users/@userId/contacts/@contactId', function($userId, $contactId) {
    // Only admin can delete contact messages
    \Flight::AuthMiddleware()->authorizeRole(Roles::ADMIN);
    
    $contactService = \Flight::ContactService();
    $result = $contactService->deleteContact($userId, $contactId);
    echo json_encode($result);
});
?>
