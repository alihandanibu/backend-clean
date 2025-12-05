<?php
// Test DAO layer
require_once 'vendor/autoload.php';
require_once 'dao/UserDAO.php';
require_once 'dao/ProjectDAO.php';
require_once 'dao/SkillDAO.php';

echo "Testing DAOs\n";
echo "------------\n\n";

// Test UserDAO
echo "1. UserDAO - get all users:\n";
$userDAO = new UserDAO();
$users = $userDAO->get_all(0, 3);
print_r($users);
echo "\n";

// Test ProjectDAO
echo "2. ProjectDAO - get all projects:\n";
$projectDAO = new ProjectDAO();
$projects = $projectDAO->get_all(0, 3);
print_r($projects);
echo "\n";

// Test SkillDAO
echo "3. SkillDAO - get all skills:\n";
$skillDAO = new SkillDAO();
$skills = $skillDAO->get_all(0, 3);
print_r($skills);
echo "\n";

echo "DAO tests completed.\n";
?>
