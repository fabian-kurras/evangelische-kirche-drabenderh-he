<?php
/**
 * Admin Panel Handlers
 * Centralized form processing for admin panel
 */

require_once __DIR__ . '/../db.php';

class AdminHandler {
    private $pdo;
    private $userId;
    private $csrf;
    private $error = '';
    private $success = '';
    
    public function __construct($pdo, $userId, $csrf) {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->csrf = $csrf;
    }
    
    public function getError() { return $this->error; }
    public function getSuccess() { return $this->success; }
    
    public function handlePost($post, $files) {
        if (!hash_equals($this->csrf, $post['csrf'] ?? '')) {
            $this->error = 'CSRF token mismatch';
            return;
        }
        
        if (isset($post['add_event'])) {
            $this->handleAddEvent($post);
        } elseif (isset($post['add_news'])) {
            $this->handleAddNews($post, $files);
        } elseif (isset($post['add_image'])) {
            $this->handleAddImage($post, $files);
        } elseif (isset($post['delete_element'])) {
            $this->handleDeleteElement($post);
        }
    }
    
    private function handleAddEvent($post) {
        $title = trim($post['title'] ?? '');
        $description = trim($post['description'] ?? '');
        $start_time_raw = $post['start_time'] ?? '';
        $end_time_raw = $post['end_time'] ?? null;
        
        if (strlen($title) < 3) {
            $this->error = 'Titel zu kurz';
            return;
        }
        
        try {
            $dt = new DateTime($start_time_raw);
            $start_time = $dt->format('Y-m-d H:i:s');
            $end_time = null;
            if ($end_time_raw) {
                $end_dt = new DateTime($end_time_raw);
                $end_time = $end_dt->format('Y-m-d H:i:s');
            }
            $stmt = $this->pdo->prepare('INSERT INTO events (title, description, start_time, end_time, created_by) VALUES (?,?,?,?,?)');
            $stmt->execute([$title, $description, $start_time, $end_time, $this->userId]);
            $this->success = 'Termin hinzugefügt';
        } catch (Exception $e) {
            $this->error = 'Ungültiges Datum/Zeit-Format';
        }
    }
    
    private function handleAddNews($post, $files) {
        $title = trim($post['title'] ?? '');
        $content = trim($post['content'] ?? '');
        
        if (strlen($title) < 3 || strlen($content) < 3) {
            $this->error = 'Titel oder Inhalt zu kurz';
            return;
        }
        
        $hasImageCol = (bool)$this->pdo->query("SHOW COLUMNS FROM news LIKE 'image'")->fetch();
        $imageFilename = null;
        
        if (!empty($files['image']) && $files['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $imageFilename = $this->processImageUpload($files['image'], 'uploads/news/', 2);
            if (!$imageFilename && !$this->error) {
                $this->error = 'Fehler beim Bild-Upload';
                return;
            }
        }
        
        if (!$this->error) {
            if ($hasImageCol) {
                $stmt = $this->pdo->prepare('INSERT INTO news (title, content, image, created_by) VALUES (?,?,?,?)');
                $stmt->execute([$title, $content, $imageFilename, $this->userId]);
            } else {
                $stmt = $this->pdo->prepare('INSERT INTO news (title, content, created_by) VALUES (?,?,?)');
                $stmt->execute([$title, $content, $this->userId]);
                if ($imageFilename) {
                    @unlink(__DIR__ . '/../uploads/news/' . $imageFilename);
                }
            }
            $this->success = 'Nachricht hinzugefügt';
        }
    }
    
    private function handleAddImage($post, $files) {
        $title = trim($post['image_title'] ?? '');
        $description = trim($post['image_description'] ?? '');
        
        if (strlen($title) < 2) {
            $this->error = 'Titel zu kurz';
            return;
        }
        
        if (empty($files['gallery_image']) || $files['gallery_image']['error'] === UPLOAD_ERR_NO_FILE) {
            $this->error = 'Bild erforderlich';
            return;
        }
        
        $imageFilename = $this->processImageUpload($files['gallery_image'], 'uploads/images/', 5);
        if (!$imageFilename) {
            return;
        }
        
        $stmt = $this->pdo->prepare('INSERT INTO images (title, filename, description, created_by) VALUES (?,?,?,?)');
        $stmt->execute([$title, $imageFilename, $description, $this->userId]);
        $this->success = 'Bild hinzugefügt';
    }
    
    private function handleDeleteElement($post) {
        $element_id = (int)($post['element_id'] ?? 0);
        if (!$element_id) return;
        
        $stmt = $this->pdo->prepare('SELECT image_filename FROM page_elements WHERE id = ?');
        $stmt->execute([$element_id]);
        $el = $stmt->fetch();
        if ($el && $el['image_filename']) {
            @unlink(__DIR__ . '/../uploads/images/' . $el['image_filename']);
        }
        $stmt = $this->pdo->prepare('DELETE FROM page_elements WHERE id = ?');
        $stmt->execute([$element_id]);
        $this->success = 'Element gelöscht';
    }
    
    private function processImageUpload($file, $uploadDir, $maxMB) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error = 'Fehler beim Upload';
            return null;
        }
        if ($file['size'] > $maxMB * 1024 * 1024) {
            $this->error = "Datei zu groß (max {$maxMB}MB)";
            return null;
        }
        
        $info = @getimagesize($file['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!$info || !isset($allowed[$info['mime']])) {
            $this->error = 'Ungültiges Bildformat (jpg/png/webp)';
            return null;
        }
        
        $ext = $allowed[$info['mime']];
        $fullDir = __DIR__ . '/../' . $uploadDir;
        if (!is_dir($fullDir)) mkdir($fullDir, 0755, true);
        
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $fullDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $this->error = 'Konnte Datei nicht speichern';
            return null;
        }
        
        return $filename;
    }
}
