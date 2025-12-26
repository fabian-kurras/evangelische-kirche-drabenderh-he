<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { die('Invalid CSRF'); }
$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
    $stmt->execute([$id]);
}
header('Location: /evangelische-kirche-drabenderhöhe/admin/panel.php');
exit;
?>