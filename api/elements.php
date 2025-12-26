<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$page_slug = $_GET['page_slug'] ?? '';

switch ($action) {
    case 'get_elements':
        $stmt = $pdo->prepare("SELECT * FROM page_elements WHERE page_slug = ? ORDER BY sort_order ASC");
        $stmt->execute([$page_slug]);
        $elements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['elements' => $elements]);
        break;
    
    default:
        echo json_encode(['error' => 'Unknown action']);
}
?>
