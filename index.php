<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=htmlspecialchars(SITE_NAME)?></title>
  <link rel="stylesheet" href="/evangelische-kirche-drabenderhöhe/assets/css/style.css">
</head>
<body>
  <header class="site-header second-color">
    <div class="wrap">
      <div style="display:flex;align-items:center;gap:12px">
        <img class="site-logo" src="/evangelische-kirche-drabenderhöhe/assets/img/wappen.png" alt="Logo">
        <h1><?=htmlspecialchars(SITE_NAME)?></h1>
      </div>
      <nav>
        <a href="/evangelische-kirche-drabenderhöhe/">Home</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/neues.php">Neues</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/calendar.php">Kalender</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/kontakt.php">Kontakt</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/images.php">Galerie</a>
        <a href="/evangelische-kirche-drabenderhöhe/admin/index.php">Admin</a>
      </nav>
    </div>
  </header>
  <div class="hero first-color card">
    <img class="hero-image" src="/evangelische-kirche-drabenderhöhe/assets/img/hero-placeholder.svg" alt="Landing banner placeholder">
  </div>
  <main class="container">
    <div class="main-grid">
      <aside class="info-panel card">
        <div class="card-body">
          <h2>Info</h2>
          <p><strong>Evangelische Kirche Drabenderhöhe</strong></p>
          <p>Willkommen auf unserer Webseite. Hier finden Sie alle aktuellen Nachrichten und Termine unserer Gemeinde.</p>
          <hr style="border:none;border-top:1px solid rgba(190,49,68,0.3);margin:12px 0">
          <p><strong>Kontakt</strong></p>
          <p>Telefon: <br>E-Mail: <br>Adresse: </p>
        </div>
      </aside>
      <section>
        <div class="main-grid" style="grid-template-columns:1fr 1fr;grid-column:1/-1">
          <div id="news" class="card">
            <div class="card-body">
              <h2>Nachrichten</h2>
              <div id="news-list" class="news-list">Lade…</div>
              <div style="margin-top:16px">
                <a href="/evangelische-kirche-drabenderhöhe/pages/neues.php" style="color:var(--accent);text-decoration:none">→ Alle Nachrichten anzeigen</a>
              </div>
            </div>
          </div>

          <div>
            <div id="events" class="card">
              <div class="card-body">
                <h2>Termine</h2>
                <div id="events-list">Lade…</div>
                <div style="margin-top:16px">
                  <a href="/evangelische-kirche-drabenderhöhe/pages/calendar.php" style="color:var(--accent);text-decoration:none">→ Alle Termine anzeigen</a>
                </div>
              </div>
            </div>
            <div id="calendar-widget" class="card" style="margin-top:16px">
              <div class="card-body">
                <h3>Kalender</h3>
                <div id="calendar-content">Lade…</div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
  <script src="/evangelische-kirche-drabenderhöhe/assets/js/main.js"></script>
</body>
</html>