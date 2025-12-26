<?php
// Diagnostic page — remove or protect after debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/plain; charset=utf-8');

$out = [];
$out[] = 'Request URI: ' . ($_SERVER['REQUEST_URI'] ?? '');
$out[] = 'PHP SAPI: ' . php_sapi_name();
$out[] = 'PHP version: ' . PHP_VERSION;
$out[] = 'display_errors: ' . ini_get('display_errors');
$out[] = 'session_status: ' . session_status();
$out[] = 'files present:';
$files = ['index.php','panel.php','auth_check.php','logout.php'];
foreach ($files as $f) {
    $path = __DIR__ . '/' . $f;
    $out[] = " - $f: " . (file_exists($path) ? 'exists' : 'MISSING');
}
// Check config and DB
$out[] = 'config.php: ' . (file_exists(__DIR__ . '/../config.php') ? 'exists' : 'MISSING');
try {
    require_once __DIR__ . '/../db.php';
    $out[] = 'DB connection: ok';
    // sample query
    $stmt = $pdo->query('SELECT 1')->fetch();
    $out[] = 'DB test query result: ' . json_encode($stmt);
} catch (Throwable $e) {
    $out[] = 'DB connection failed: ' . $e->getMessage();
}

// Check for recent PHP error log (if accessible)
$errlog = ini_get('error_log');
$out[] = 'error_log: ' . ($errlog ?: 'none');
if ($errlog && file_exists($errlog)) {
    $out[] = '--- last 50 lines of error log ---';
    $lines = array_slice(file($errlog), -50);
    $out = array_merge($out, $lines);
}

echo implode("\n", $out);
?>