<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/handlers.php';

// Process forms
$handler = new AdminHandler($pdo, $_SESSION['user_id'], $_SESSION['csrf'] ?? '');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $handler->handlePost($_POST, $_FILES);
}

// Fetch data
$events = $pdo->query('SELECT e.*, u.username FROM events e LEFT JOIN users u ON u.id = e.created_by ORDER BY start_time DESC')->fetchAll();
$news = $pdo->query('SELECT n.*, u.username FROM news n LEFT JOIN users u ON u.id = n.created_by ORDER BY created_at DESC')->fetchAll();
$images = $pdo->query('SELECT i.*, u.username FROM images i LEFT JOIN users u ON u.id = i.created_by ORDER BY created_at DESC')->fetchAll() ?: [];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Panel - <?=htmlspecialchars(SITE_NAME)?></title>
  <link rel="stylesheet" href="/evangelische-kirche-drabenderhöhe/assets/css/style.css">
  <style>
    :root { --bg: #1a1a1a; --fg: #e0e0e0; --accent: #be3144; --border: #333; --input-bg: #2a2a2a; }
    body { background: var(--bg); color: var(--fg); }
    main { background: var(--bg); color: var(--fg); }
    h1, h2, h3 { color: #fff; }
    a { color: var(--accent); }
    .admin-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; }
    .admin-section { background: var(--input-bg); padding: 16px; border-radius: 6px; border: 1px solid var(--border); }
    .admin-section h3 { margin-top: 0; color: #fff; }
    .item-list { list-style: none; padding: 0; margin: 0; }
    .item-list li { padding: 12px; background: var(--input-bg); margin-bottom: 8px; border-radius: 4px; border-left: 3px solid var(--accent); }
    input[type="text"], input[type="email"], input[type="datetime-local"], input[type="file"], textarea, select { width: 100%; padding: 8px; box-sizing: border-box; background: var(--input-bg); color: var(--fg); border: 1px solid var(--border); border-radius: 3px; }
    button { background: var(--accent); color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
    button:hover { opacity: 0.85; }
    hr { border-color: var(--border); }
    p[style*="background:#fee"] { background: #3a2020 !important; color: #ff6b6b !important; border-left-color: #ff6b6b !important; }
    p[style*="background:#efe"] { background: #203a20 !important; color: #6bff6b !important; border-left-color: #6bff6b !important; }
    header { border-bottom: 1px solid var(--border); padding-bottom: 16px; }
    small { color: #aaa; }
  </style>
</head>
<body>
  <main class="container">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <h1>Admin Panel</h1>
      <div>
        <span>Angemeldet als <?=htmlspecialchars($_SESSION['username'])?></span> 
        <a href="/evangelische-kirche-drabenderhöhe/admin/logout.php" style="margin-left: 12px;">Logout</a>
      </div>
    </header>

    <?php if ($handler->getError()): ?>
      <p style="background:#fee; color:#c00; padding:12px; border-radius:4px; border-left:3px solid #c00;">
        <?=htmlspecialchars($handler->getError())?>
      </p>
    <?php endif; ?>
    
    <?php if ($handler->getSuccess()): ?>
      <p style="background:#efe; color:#080; padding:12px; border-radius:4px; border-left:3px solid #080;">
        <?=htmlspecialchars($handler->getSuccess())?>
      </p>
    <?php endif; ?>

    <!-- CONTENT MANAGEMENT -->
    <h2>Inhalte verwalten</h2>
    
    <div class="admin-grid">
      <div class="admin-section">
        <h3>Neuer Termin</h3>
        <form method="post">
          <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
          <label>Titel<br><input name="title" required></label><br><br>
          <label>Beschreibung<br><textarea name="description" rows="3"></textarea></label><br><br>
          <label>Startzeit<br><input name="start_time" type="datetime-local" required></label><br><br>
          <label>Endzeit (optional)<br><input name="end_time" type="datetime-local"></label><br><br>
          <button name="add_event" style="width:100%;">Hinzufügen</button>
        </form>
      </div>

      <div class="admin-section">
        <h3>Neue Nachricht</h3>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
          <label>Titel<br><input name="title" required></label><br><br>
          <label>Inhalt<br><textarea name="content" required rows="4"></textarea></label><br><br>
          <label>Bild (optional)<br><input name="image" type="file" accept="image/*"></label>
          <small style="display:block; margin-top:4px;">Max 2MB, jpg/png/webp</small><br>
          <button name="add_news" style="width:100%;">Hinzufügen</button>
        </form>
      </div>
    </div>

    <div class="admin-grid">
      <div class="admin-section">
        <h3>Neues Bild (Galerie)</h3>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
          <label>Titel<br><input name="image_title" required></label><br><br>
          <label>Beschreibung<br><textarea name="image_description" rows="3"></textarea></label><br><br>
          <label>Bild<br><input name="gallery_image" type="file" accept="image/*" required></label>
          <small style="display:block; margin-top:4px;">Max 5MB, jpg/png/webp</small><br>
          <button name="add_image" style="width:100%;">Hinzufügen</button>
        </form>
      </div>

      <div></div>
    </div>

    <!-- EVENTS LIST -->
    <h2 style="margin-top:32px;">Termine</h2>
    <?php if ($events): ?>
      <ul class="item-list">
        <?php foreach ($events as $e): ?>
          <li>
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
              <div>
                <strong><?=htmlspecialchars($e['title'])?></strong><br>
                <small><?=htmlspecialchars($e['start_time'])?> von <?=htmlspecialchars($e['username'])?></small>
              </div>
              <form method="post" action="/evangelische-kirche-drabenderhöhe/admin/delete_event.php" style="display:inline;">
                <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
                <input type="hidden" name="id" value="<?=(int)$e['id']?>">
                <button type="submit" onclick="return confirm('Löschen?')" style="padding:4px 8px; font-size:0.9em;">Löschen</button>
              </form>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p><em>Keine Termine vorhanden</em></p>
    <?php endif; ?>

    <!-- NEWS LIST -->
    <h2 style="margin-top:32px;">Nachrichten</h2>
    <?php if ($news): ?>
      <ul class="item-list">
        <?php foreach ($news as $n): ?>
          <li>
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
              <div style="flex:1;">
                <strong><?=htmlspecialchars($n['title'])?></strong><br>
                <small><?=htmlspecialchars($n['created_at'])?> von <?=htmlspecialchars($n['username'])?></small>
              </div>
              <form method="post" action="/evangelische-kirche-drabenderhöhe/admin/delete_news.php" style="display:inline;">
                <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
                <input type="hidden" name="id" value="<?=(int)$n['id']?>">
                <button type="submit" onclick="return confirm('Löschen?')" style="padding:4px 8px; font-size:0.9em; white-space:nowrap;">Löschen</button>
              </form>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p><em>Keine Nachrichten vorhanden</em></p>
    <?php endif; ?>

    <!-- IMAGES LIST -->
    <h2 style="margin-top:32px;">Galerie</h2>
    <?php if ($images): ?>
      <ul class="item-list" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:12px; list-style:none;">
        <?php foreach ($images as $img): ?>
          <li style="position:relative; border:none; padding:0; margin:0; border-left:none;">
            <img src="/evangelische-kirche-drabenderhöhe/uploads/images/<?=htmlspecialchars($img['filename'])?>" alt="" style="width:100%; height:120px; object-fit:cover; border-radius:4px; display:block;">
            <div style="padding:8px 0;">
              <small><strong><?=htmlspecialchars($img['title'])?></strong></small><br>
              <small><?=htmlspecialchars($img['username'])?></small>
            </div>
            <form method="post" action="/evangelische-kirche-drabenderhöhe/admin/delete_image.php" style="display:inline;">
              <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
              <input type="hidden" name="id" value="<?=(int)$img['id']?>">
              <button type="submit" onclick="return confirm('Löschen?')" style="padding:4px; font-size:0.85em; width:100%;">Löschen</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p><em>Keine Bilder vorhanden</em></p>
    <?php endif; ?>

    <hr style="margin:32px 0;">
    <p><a href="/evangelische-kirche-drabenderhöhe/index.php">← Zur Webseite</a></p>
  </main>
</body>
</html>