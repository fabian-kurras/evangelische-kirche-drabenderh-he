<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(403);
    exit('CSRF token mismatch');
}

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    http_response_code(400);
    exit('Invalid ID');
}

$stmt = $pdo->prepare('SELECT filename FROM images WHERE id = ?');
$stmt->execute([$id]);
$image = $stmt->fetch();

if (!$image) {
    http_response_code(404);
    exit('Image not found');
}

// Delete file
$filePath = __DIR__ . '/../uploads/images/' . $image['filename'];
if (file_exists($filePath)) {
    @unlink($filePath);
}

// Delete from DB
$stmt = $pdo->prepare('DELETE FROM images WHERE id = ?');
$stmt->execute([$id]);

header('Location: /evangelische-kirche-drabenderhöhe/admin/panel.php');
exit;
?>
