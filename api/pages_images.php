<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$db = Database::getInstance();

switch ($action) {
    case 'list_images':
        $stmt = $db->prepare("SELECT * FROM images ORDER BY created_at DESC");
        $stmt->execute();
        echo json_encode(['images' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;
    
    case 'get_page':
        $slug = $_GET['slug'] ?? '';
        $stmt = $db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['page' => $page ?: ['slug' => $slug, 'title' => '', 'content' => '']]);
        break;
    
    case 'list_pages':
        $stmt = $db->prepare("SELECT id, slug, title FROM pages ORDER BY slug");
        $stmt->execute();
        echo json_encode(['pages' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;
    
    default:
        echo json_encode(['error' => 'Unknown action']);
}
?>
