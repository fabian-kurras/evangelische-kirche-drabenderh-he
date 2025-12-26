<?php require_once __DIR__ . '/../config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bilder - <?=htmlspecialchars(SITE_NAME)?></title>
  <link rel="stylesheet" href="/evangelische-kirche-drabenderhöhe/assets/css/style.css">
</head>
<body>
  <header class="site-header second-color">
    <div class="wrap">
      <div style="display:flex;align-items:center;gap:12px">
        <a href="/evangelische-kirche-drabenderhöhe/" style="text-decoration:none;display:flex;align-items:center;gap:12px">
          <img class="site-logo" src="/evangelische-kirche-drabenderhöhe/assets/img/wappen.png" alt="Logo">
          <h1><?=htmlspecialchars(SITE_NAME)?></h1>
        </a>
      </div>
      <nav>
        <a href="/evangelische-kirche-drabenderhöhe/">Home</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/kontakt.php">Kontakt</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/pfarrblatt.php">Pfarrblatt</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/images.php">Bilder</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/themen.php">Themen</a>
        <a href="/evangelische-kirche-drabenderhöhe/admin/index.php">Admin</a>
      </nav>
    </div>
  </header>
  <main class="container">
    <div class="main-grid">
      <aside class="info-panel card">
        <div class="card-body">
          <h2>Navigation</h2>
          <ul style="list-style:none;padding:0">
            <li style="margin-bottom:8px"><a href="/evangelische-kirche-drabenderhöhe/" style="color:var(--accent);text-decoration:none">Home</a></li>
            <li style="margin-bottom:8px"><a href="/evangelische-kirche-drabenderhöhe/pages/kontakt.php" style="color:var(--accent);text-decoration:none">Kontakt</a></li>
            <li style="margin-bottom:8px"><a href="/evangelische-kirche-drabenderhöhe/pages/pfarrblatt.php" style="color:var(--accent);text-decoration:none">Pfarrblatt</a></li>
            <li style="margin-bottom:8px"><a href="/evangelische-kirche-drabenderhöhe/pages/images.php" style="color:var(--accent);text-decoration:none">Bilder</a></li>
            <li style="margin-bottom:8px"><a href="/evangelische-kirche-drabenderhöhe/pages/themen.php" style="color:var(--accent);text-decoration:none">Themen</a></li>
          </ul>
        </div>
      </aside>
      <section>
        <div class="card">
          <div class="card-body">
            <h2>Bilder</h2>
            <p>Schauen Sie sich unsere Bilder aus Gemeindeveranstaltungen und vom Gemeindeleben an.</p>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:16px;margin-top:20px">
              <div style="border:2px solid var(--accent);border-radius:10px;height:200px;background:#3a4750;display:flex;align-items:center;justify-content:center;color:#999">
                Bildplatzhalter 1
              </div>
              <div style="border:2px solid var(--accent);border-radius:10px;height:200px;background:#3a4750;display:flex;align-items:center;justify-content:center;color:#999">
                Bildplatzhalter 2
              </div>
              <div style="border:2px solid var(--accent);border-radius:10px;height:200px;background:#3a4750;display:flex;align-items:center;justify-content:center;color:#999">
                Bildplatzhalter 3
              </div>
              <div style="border:2px solid var(--accent);border-radius:10px;height:200px;background:#3a4750;display:flex;align-items:center;justify-content:center;color:#999">
                Bildplatzhalter 4
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