<?php
// Test database connection
require_once 'config/Database.php';

echo "Testing database connection...\n";

try {
    $db = Database::connect();
    echo "Connection successful!\n";
    echo "Database: portfolio\n";
    echo "Host: localhost:3307\n";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
