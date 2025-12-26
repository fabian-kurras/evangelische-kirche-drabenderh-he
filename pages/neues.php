<?php require_once __DIR__ . '/../config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Neues - <?=htmlspecialchars(SITE_NAME)?></title>
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
            <h2>Alle Nachrichten</h2>
            <div id="news-list" class="news-list">Lade…</div>
          </div>
        </div>
      </section>
    </div>
  </main>
  <script>
    (function(){
      const newsList = document.getElementById('news-list');
      async function fetchNews(){
        try{
          const res = await fetch('/evangelische-kirche-drabenderhöhe/api/get_items.php?type=news');
          const data = await res.json();
          renderNews(data.news);
        }catch(e){
          console.error('Fetch error:', e);
          newsList.textContent = 'Fehler beim Laden';
        }
      }
      function renderNews(news){
        if (!news || news.length === 0) { newsList.innerHTML = '<p>Keine Nachrichten</p>'; return; }
        newsList.innerHTML = news.map(n => `
          <article class="news-item card">
            ${n.image ? `<img class="news-image" loading="lazy" src="${escapeHTML(n.image)}" alt="${escapeHTML(n.title)}">` : ''}
            <div class="card-body">
              <h3>${escapeHTML(n.title)}</h3>
              <p>${escapeHTML(n.content).replace(/\n/g,'<br>')}</p>
              <div style="font-size:0.85rem;color:#aaa">
                <time>${escapeHTML(n.created_at || '')}</time>
                ${n.author ? ` — ${escapeHTML(n.author)}` : ''}
              </div>
            </div>
          </article>
        `).join('');
      }
      function escapeHTML(s){
        if (!s) return '';
        return String(s).replace(/[&<>"']/g, function(c){
          return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
        });
      }
      fetchNews();
    })();
  </script>
</body>
</html>