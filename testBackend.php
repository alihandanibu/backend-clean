<?php
// testBackend.php
// Usage: php testBackend.php [http://localhost/portfolio-final/backend-backup/backend/]

function getBaseUrl() {
    global $argv;
    if (!empty($argv[1]) && preg_match('#^https?://#i', $argv[1])) {
        return rtrim($argv[1], '/') . '/';
    }
    $env = getenv('TEST_BASE_URL');
    if (!empty($env)) {
        return rtrim($env, '/') . '/';
    }
    return 'http://127.0.0.1:8000/';
}

function httpGet($url, &$httpCode = null, $timeout = 10) {
    if (function_exists('curl_version')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $body = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE) ?: 0;
        curl_close($ch);
        if ($body === false) {
            return ['error' => "cURL: $err"];
        }
        return ['body' => $body];
    } else {
        $ctx = stream_context_create(['http' => ['method' => 'GET', 'timeout' => $timeout, 'ignore_errors' => true]]);
        $body = @file_get_contents($url, false, $ctx);
        if ($body === false) {
            $httpCode = 0;
            return ['error' => 'file_get_contents failed'];
        }
        $httpCode = 0;
        if (!empty($http_response_header)) {
            foreach ($http_response_header as $h) {
                if (preg_match('#HTTP/\d+\.\d+\s+(\d+)#', $h, $m)) {
                    $httpCode = (int)$m[1];
                    break;
                }
            }
        }
        return ['body' => $body];
    }
}

$base = getBaseUrl();

$tests = [
    'Root API' => ['url' => $base, 'type' => 'json', 'expect' => ['WORKING']],
    'Docs HTML' => ['url' => $base . 'public/v1/docs/', 'type' => 'html', 'expect' => ['<html', 'swagger']],
    'Swagger JSON' => ['url' => $base . 'public/v1/docs/swagger.php', 'type' => 'openapi', 'expect' => ['openapi', 'swagger', 'info']]
];

$passed = 0;
$failed = 0;

foreach ($tests as $name => $test) {
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "TEST: $name\n";
    echo "URL:  {$test['url']}\n";
    echo str_repeat("-", 60) . "\n";
    
    $res = httpGet($test['url'], $httpCode);
    
    if (isset($res['error'])) {
        echo "❌ FAIL: {$res['error']}\n";
        $failed++;
        continue;
    }
    
    echo "HTTP Status: $httpCode\n";
    
    if ($httpCode !== 200) {
        echo "❌ FAIL: Expected 200, got $httpCode\n";
        echo "Body: " . substr($res['body'], 0, 300) . "...\n";
        $failed++;
        continue;
    }
    
    $body = $res['body'];
    $testPassed = true;
    
    // Type-specific validation
    if ($test['type'] === 'json') {
        $decoded = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "❌ FAIL: Invalid JSON - " . json_last_error_msg() . "\n";
            $testPassed = false;
        } else {
            echo "Response:\n" . json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            foreach ($test['expect'] as $keyword) {
                if (stripos(json_encode($decoded), $keyword) === false) {
                    echo "⚠️  WARNING: Missing expected keyword: '$keyword'\n";
                }
            }
        }
    } elseif ($test['type'] === 'html') {
        $foundHtml = false;
        foreach (['<html', '<!DOCTYPE', '<HTML'] as $tag) {
            if (stripos($body, $tag) !== false) {
                $foundHtml = true;
                break;
            }
        }
        if (!$foundHtml) {
            echo "❌ FAIL: Response doesn't look like HTML\n";
            echo "Body: " . substr($body, 0, 300) . "...\n";
            $testPassed = false;
        } else {
            echo "✓ HTML detected\n";
            foreach ($test['expect'] as $keyword) {
                if (stripos($body, $keyword) !== false) {
                    echo "✓ Found keyword: '$keyword'\n";
                } else {
                    echo "⚠️  Missing keyword: '$keyword'\n";
                }
            }
            echo "Preview: " . substr(strip_tags($body), 0, 200) . "...\n";
        }
    } elseif ($test['type'] === 'openapi') {
        $decoded = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "❌ FAIL: Invalid JSON - " . json_last_error_msg() . "\n";
            echo "Body: " . substr($body, 0, 500) . "...\n";
            $testPassed = false;
        } else {
            $foundSpec = false;
            foreach ($test['expect'] as $key) {
                if (isset($decoded[$key])) {
                    echo "✓ Found OpenAPI key: '$key'\n";
                    $foundSpec = true;
                    break;
                }
            }
            if (!$foundSpec) {
                echo "⚠️  WARNING: Doesn't look like OpenAPI spec\n";
            }
            echo "Keys: " . implode(', ', array_keys($decoded)) . "\n";
            if (isset($decoded['info']['title'])) {
                echo "API Title: {$decoded['info']['title']}\n";
            }
            if (isset($decoded['info']['version'])) {
                echo "API Version: {$decoded['info']['version']}\n";
            }
        }
    }
    
    if ($testPassed) {
        echo "✅ PASS\n";
        $passed++;
    } else {
        $failed++;
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "SUMMARY: ✅ $passed passed | ❌ $failed failed\n";
echo str_repeat("=", 60) . "\n";

exit($failed > 0 ? 1 : 0);