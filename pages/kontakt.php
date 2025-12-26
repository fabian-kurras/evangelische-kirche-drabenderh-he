<?php require_once __DIR__ . '/../config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kontakt - <?=htmlspecialchars(SITE_NAME)?></title>
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
        <a href="/evangelische-kirche-drabenderhöhe/pages/neues.php">Neues</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/calendar.php">Kalender</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/kontakt.php">Kontakt</a>
        <a href="/evangelische-kirche-drabenderhöhe/pages/images.php">Galerie</a>
        <a href="/evangelische-kirche-drabenderhöhe/admin/index.php">Admin</a>
      </nav>
    </div>
  </header>
  <main class="container">
    <div class="main-grid">
      <section>
        <div class="card">
          <div class="card-body">
            <h2>Kontakt</h2>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
              <div>
                <h3>Nachricht</h3>
                <p><em>Kontaktformular in Vorbereitung</em></p>
              </div>
              <div>
                <h3>Kontaktinformation</h3>
                <div style="margin-bottom:20px">
                  <h4 style="margin-top:0">Adresse</h4>
                  <p style="margin:0">Evangelische Kirche Drabenderhöhe<br>Adresse der Kirche<br>PLZ Ort</p>
                </div>
                <div style="margin-bottom:20px">
                  <h4 style="margin-top:0">Telefon</h4>
                  <p style="margin:0">Telefonnummer hier</p>
                </div>
                <div>
                  <h4 style="margin-top:0">Email</h4>
                  <p style="margin:0">kontakt@kirche-drabenderhoehe.de</p>
                </div>
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