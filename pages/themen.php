<?php require_once __DIR__ . '/../config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Themen - <?=htmlspecialchars(SITE_NAME)?></title>
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
            <h2>Themen</h2>
            <p>Hier finden Sie weitere Informationen zu verschiedenen Themen unserer Gemeinde:</p>
            <hr style="border:none;border-top:1px solid rgba(190,49,68,0.3);margin:24px 0">
            <h3>Predigten</h3>
            <p>Aktuelle Predigten und Andachten finden Sie hier regelmäßig.</p>
            <hr style="border:none;border-top:1px solid rgba(190,49,68,0.3);margin:24px 0">
            <h3>Bibelstellen</h3>
            <p>Interessante Bibelstellen und deren Auslegung.</p>
            <hr style="border:none;border-top:1px solid rgba(190,49,68,0.3);margin:24px 0">
            <h3>Veranstaltungen</h3>
            <p>Informationen zu regelmäßigen Veranstaltungen und Angeboten der Gemeinde.</p>
          </div>
        </div>
      </section>
    </div>
  </main>
  <script src="/evangelische-kirche-drabenderhöhe/assets/js/main.js"></script>
</body>
</html>