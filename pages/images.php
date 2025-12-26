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
      <section id="images-section">
        <div class="card">
          <div class="card-body">
            <h2>Bilder</h2>
            <div id="elements-container">Lade…</div>
          </div>
        </div>
      </section>
    </div>
  </main>
  <script>
    (function(){
      const container = document.getElementById('elements-container');
      
      async function loadImages(){
        try {
          const res = await fetch('/evangelische-kirche-drabenderhöhe/api/get_items.php?type=images');
          const data = await res.json();
          renderImages(data.images);
        } catch(e) {
          console.error('Fetch error:', e);
          container.textContent = 'Fehler beim Laden';
        }
      }
      
      function escapeHTML(s){
        if (!s) return '';
        return String(s).replace(/[&<>"']/g, function(c){
          return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
        });
      }
      
      function renderImages(images){
        if (!images || images.length === 0) {
          container.innerHTML = '<p style="color:#aaa">Keine Bilder vorhanden</p>';
          return;
        }
        
        let html = '<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:20px;margin:20px 0;align-items:start">';
        images.forEach(img => {
          html += `
            <div style="border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.2);width:100%;">
              <img src="/evangelische-kirche-drabenderhöhe/uploads/images/${escapeHTML(img.filename)}" alt="${escapeHTML(img.title)}" style="width:100%;display:block;object-fit:cover;">
              <div style="padding:12px;background:#fafafa;color:#333;">
                <strong>${escapeHTML(img.title)}</strong><br>
                <small style="color:#666">${escapeHTML(img.description)}</small>
              </div>
            </div>
          `;
        });
        html += '</div>';
        
        container.innerHTML = html;
      }
      
      loadImages();
    })();
  </script>
</body>
</html>