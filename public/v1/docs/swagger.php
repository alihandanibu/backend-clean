<?php
// Suppress warnings so they don't break JSON output
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

require_once __DIR__ . '/../../../vendor/autoload.php';

use OpenApi\Generator;

$scanPaths = [];

// Only add paths that actually exist
$possiblePaths = [
    __DIR__ . '/../../../doc_setup.php',
    __DIR__ . '/../../../routes',
    __DIR__ . '/../../../services',
    __DIR__ . '/../../../middleware',
];

foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $scanPaths[] = $path;
    }
}

// Fallback if no paths exist
if (empty($scanPaths)) {
    $scanPaths = [__DIR__];
}

try {
    $openapi = Generator::scan($scanPaths);
    header('Content-Type: application/json');
    echo $openapi->toJson();
} catch (\Exception $e) {
    header('Content-Type: application/json', true, 500);
    echo json_encode([
        'error' => 'Failed to generate OpenAPI documentation',
        'message' => $e->getMessage(),
        'paths_scanned' => $scanPaths
    ], JSON_PRETTY_PRINT);
}