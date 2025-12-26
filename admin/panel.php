<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../db.php';
// Handle forms
$err = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        $err = 'CSRF token mismatch';
    } else {
        if (isset($_POST['add_event'])) {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $start_time_raw = $_POST['start_time'] ?? '';
            $end_time_raw = $_POST['end_time'] ?: null;
            // validate
            if (strlen($title) < 3) {
                $err = 'Titel zu kurz';
            } else {
                try {
                    $dt = new DateTime($start_time_raw);
                    $start_time = $dt->format('Y-m-d H:i:s');
                    $end_time = null;
                    if ($end_time_raw) {
                        $end_dt = new DateTime($end_time_raw);
                        $end_time = $end_dt->format('Y-m-d H:i:s');
                    }
                    $stmt = $pdo->prepare('INSERT INTO events (title, description, start_time, end_time, created_by) VALUES (?,?,?,?,?)');
                    $stmt->execute([$title, $description, $start_time, $end_time, $_SESSION['user_id']]);
                    $success = 'Event added';
                } catch (Exception $e) {
                    $err = 'Ungültiges Datum/Zeit-Format';
                }
            }
        } elseif (isset($_POST['add_news'])) {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            if (strlen($title) < 3 || strlen($content) < 3) {
                $err = 'Titel oder Inhalt zu kurz';
            } else {
                // Check if `image` column exists
                $hasImageCol = (bool)$pdo->query("SHOW COLUMNS FROM news LIKE 'image'")->fetch();
                $imageFilename = null;
                // Handle upload if present
                if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $f = $_FILES['image'];
                    if ($f['error'] !== UPLOAD_ERR_OK) {
                        $err = 'Fehler beim Upload';
                    } elseif ($f['size'] > 2 * 1024 * 1024) {
                        $err = 'Datei zu groß (max 2MB)';
                    } else {
                        $info = @getimagesize($f['tmp_name']);
                        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                        if (!$info || !isset($allowed[$info['mime']])) {
                            $err = 'Ungültiges Bildformat';
                        } else {
                            $ext = $allowed[$info['mime']];
                            $uploadDir = __DIR__ . '/../uploads/news/';
                            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                            $imageFilename = bin2hex(random_bytes(8)) . '.' . $ext;
                            $dest = $uploadDir . $imageFilename;
                            if (!move_uploaded_file($f['tmp_name'], $dest)) {
                                $err = 'Konnte Datei nicht speichern';
                                $imageFilename = null;
                            }
                        }
                    }
                }
                if (!$err) {
                    if ($hasImageCol) {
                        $stmt = $pdo->prepare('INSERT INTO news (title, content, image, created_by) VALUES (?,?,?,?)');
                        $stmt->execute([$title, $content, $imageFilename, $_SESSION['user_id']]);
                    } else {
                        $stmt = $pdo->prepare('INSERT INTO news (title, content, created_by) VALUES (?,?,?)');
                        $stmt->execute([$title, $content, $_SESSION['user_id']]);
                        // If image uploaded but DB doesn't have column, remove the uploaded image to avoid orphan
                        if ($imageFilename) {
                            @unlink(__DIR__ . '/../uploads/news/' . $imageFilename);
                        }
                    }
                    $success = 'News added';
                }
            }
        }
    }
}
// fetch lists
$events = $pdo->query('SELECT e.*, u.username FROM events e LEFT JOIN users u ON u.id = e.created_by ORDER BY start_time DESC')->fetchAll();
$news = $pdo->query('SELECT n.*, u.username FROM news n LEFT JOIN users u ON u.id = n.created_by ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Panel - <?=htmlspecialchars(SITE_NAME)?></title>
  <link rel="stylesheet" href="/evangelische-kirche-drabenderhöhe/assets/css/style.css">
</head>
<body>
  <main class="container">
    <h1>Admin Panel</h1>
    <p>Angemeldet als <?=htmlspecialchars($_SESSION['username'])?> — <a href="/evangelische-kirche-drabenderhöhe/admin/logout.php">Logout</a></p>
    <?php if ($err): ?><p class="error"><?=htmlspecialchars($err)?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?=htmlspecialchars($success)?></p><?php endif; ?>

    <section>
      <h2>Neues Ereignis (Termin)</h2>
      <form method="post">
        <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
        <label>Titel<br><input name="title" required></label><br>
        <label>Beschreibung<br><textarea name="description"></textarea></label><br>
        <label>Startzeit<br><input name="start_time" type="datetime-local" required></label><br>
        <label>Endzeit (optional)<br><input name="end_time" type="datetime-local"></label><br>
        <button name="add_event">Hinzufügen</button>
      </form>
    </section>

    <section>
      <h2>Neue Nachricht</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
        <label>Titel<br><input name="title" required></label><br>
        <label>Inhalt<br><textarea name="content" required></textarea></label><br>
        <label>Bild (optional)<br><input name="image" type="file" accept="image/*"></label><br>
        <small>Max 2MB, jpg/png/webp</small><br>
        <button name="add_news">Hinzufügen</button>
      </form>
    </section>

    <hr>
    <section>
      <h2>Letzte Termine</h2>
      <ul>
        <?php foreach ($events as $e): ?>
          <li>
            <strong><?=htmlspecialchars($e['title'])?></strong> — <?=htmlspecialchars($e['start_time'])?> by <?=htmlspecialchars($e['username'])?> 
            <form method="post" action="/evangelische-kirche-drabenderhöhe/admin/delete_event.php" style="display:inline">
              <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
              <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
              <button type="submit" onclick="return confirm('Termin wirklich löschen?')">Löschen</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section>
      <h2>Letzte Nachrichten</h2>
      <ul class="admin-list">
        <?php foreach ($news as $n): ?>
          <li>
            <div style="display:flex;align-items:center">
              <?php if (!empty($n['image'])): ?>
                <img src="/evangelische-kirche-drabenderhöhe/uploads/news/<?=htmlspecialchars($n['image'])?>" alt="" />
              <?php endif; ?>
              <div>
                <strong><?=htmlspecialchars($n['title'])?></strong><br>
                <small><?=htmlspecialchars($n['created_at'])?> by <?=htmlspecialchars($n['username'])?></small>
              </div>
            </div>
            <div>
              <form method="post" action="/evangelische-kirche-drabenderhöhe/admin/delete_news.php" style="display:inline">
                <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
                <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                <button type="submit" onclick="return confirm('Nachricht wirklich löschen?')">Löschen</button>
              </form>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <p><a href="/evangelische-kirche-drabenderhöhe/index.php">Zur Webseite</a></p>
  </main>
</body>
</html>