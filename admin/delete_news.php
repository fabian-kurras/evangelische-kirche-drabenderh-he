<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { die('Invalid CSRF'); }
$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    // remove image file if set
    $stmt = $pdo->prepare('SELECT image FROM news WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row && !empty($row['image'])) {
        $file = __DIR__ . '/../uploads/news/' . $row['image'];
        if (file_exists($file)) @unlink($file);
    }
    $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
    $stmt->execute([$id]);
}
header('Location: /evangelische-kirche-drabenderhöhe/admin/panel.php');
exit;
?>