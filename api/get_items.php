<?php
// Public API: return JSON list of events or news
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';
$type = $_GET['type'] ?? 'all';
if ($type === 'events' || $type === 'all') {
    $stmt = $pdo->query('SELECT e.id, e.title, e.description, e.start_time, e.end_time, u.username FROM events e LEFT JOIN users u ON u.id = e.created_by ORDER BY e.start_time ASC LIMIT 100');
    $events = $stmt->fetchAll();
    foreach ($events as &$e) {
        $e['author'] = $e['username'];
    }
} else {
    $events = [];
}
if ($type === 'news' || $type === 'all') {
    $stmt = $pdo->query('SELECT n.id, n.title, n.content, n.created_at, n.image, u.username FROM news n LEFT JOIN users u ON u.id = n.created_by ORDER BY n.created_at DESC LIMIT 50');
    $news = $stmt->fetchAll();
    // convert image filename to URL
    $base = '/evangelische-kirche-drabenderhöhe/uploads/news/';
    foreach ($news as &$n) {
        if (!empty($n['image'])) $n['image'] = $base . $n['image'];
        $n['author'] = $n['username'];
    }
} else {
    $news = [];
}
echo json_encode(['events' => $events, 'news' => $news], JSON_UNESCAPED_UNICODE);
?>