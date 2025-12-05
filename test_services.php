<?php
// Test Service layer
require_once 'vendor/autoload.php';
require_once 'services/UserService.php';
require_once 'services/ProjectService.php';
require_once 'services/SkillService.php';

echo "Testing Services\n";
echo "----------------\n\n";

// Test UserService
echo "1. UserService - get all users:\n";
$userService = new UserService();
$result = $userService->get_all(0, 3);
print_r($result);
echo "\n";

// Test ProjectService
echo "2. ProjectService - get all projects:\n";
$projectService = new ProjectService();
$result = $projectService->get_all(0, 3);
print_r($result);
echo "\n";

// Test SkillService
echo "3. SkillService - get all skills:\n";
$skillService = new SkillService();
$result = $skillService->get_all(0, 3);
print_r($result);
echo "\n";

echo "Service tests completed.\n";
?>
