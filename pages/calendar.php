<?php require_once __DIR__ . '/../config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kalender - <?=htmlspecialchars(SITE_NAME)?></title>
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
      <aside class="info-panel card">
        <div class="card-body">
          <h2>Kalender</h2>
          <div id="calendar-widget" class="card" style="border:none;box-shadow:none;margin:0;padding:0">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
              <button onclick="calPrev()" style="padding:4px 8px;font-size:0.9rem">← Vorher</button>
              <span id="calendar-month" style="font-weight:bold"></span>
              <button onclick="calNext()" style="padding:4px 8px;font-size:0.9rem">Nächst →</button>
            </div>
            <div id="calendar-content" style="font-size:0.85rem">Lade…</div>
          </div>
        </div>
      </aside>
      <section>
        <div class="card">
          <div class="card-body">
            <h2>Alle Termine</h2>
            <div id="events-list">Lade…</div>
          </div>
        </div>
      </section>
    </div>
  </main>
  <script>
    (function(){
      const eventsList = document.getElementById('events-list');
      let currentCalendarMonth = new Date();
      
      async function fetchEvents(){
        try{
          const res = await fetch('/evangelische-kirche-drabenderhöhe/api/get_items.php?type=events');
          const data = await res.json();
          renderEvents(data.events);
        }catch(e){
          console.error('Fetch error:', e);
          eventsList.textContent = 'Fehler beim Laden';
        }
      }
      
      function renderEvents(events){
        if (!events || events.length === 0) { eventsList.innerHTML = '<p>Keine Termine</p>'; return; }
        eventsList.innerHTML = events.map(e => `
          <article class="event-item">
            <h3>${escapeHTML(e.title)}</h3>
            <p>${escapeHTML(e.description || '')}</p>
            <time>${escapeHTML(e.start_time)}${e.end_time ? ' — '+escapeHTML(e.end_time) : ''}</time>
            <div style="font-size:0.85rem;color:#aaa;margin-top:4px">
              ${e.author ? `Von ${escapeHTML(e.author)}` : ''}
            </div>
          </article>
        `).join('');
        generateCalendar(events);
      }
      
      function generateCalendar(events){
        const calendarDiv = document.getElementById('calendar-content');
        if (!calendarDiv) return;
        const year = currentCalendarMonth.getFullYear();
        const month = currentCalendarMonth.getMonth();
        const monthNames = ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
        
        const eventDates = new Set();
        (events || []).forEach(e => {
          if (e.start_time) {
            const d = new Date(e.start_time);
            if (d.getFullYear() === year && d.getMonth() === month) {
              eventDates.add(d.getDate());
            }
          }
        });
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        let html = `<div style="font-size:0.9rem;padding:12px 0">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:6px;flex-wrap:wrap">
            <button class="cal-nav" onclick="window.calPrev()" style="background:transparent;border:1px solid var(--accent);color:var(--accent);padding:4px 8px;font-size:0.8rem;border-radius:4px;cursor:pointer">‹ Vorher</button>
            <div style="font-weight:bold;text-align:center;flex:1;min-width:120px">${monthNames[month]} ${year}</div>
            <button class="cal-nav" onclick="window.calNext()" style="background:transparent;border:1px solid var(--accent);color:var(--accent);padding:4px 8px;font-size:0.8rem;border-radius:4px;cursor:pointer">Nächst ›</button>
          </div>
          <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;text-align:center">`;
        
        const dayLabels = ['So','Mo','Di','Mi','Do','Fr','Sa'];
        dayLabels.forEach(d => html += `<div style="font-weight:bold;color:#aaa;font-size:0.75rem;padding:4px">${d}</div>`);
        
        for (let i = 0; i < firstDay; i++) html += '<div></div>';
        for (let d = 1; d <= daysInMonth; d++) {
          const hasEvent = eventDates.has(d);
          html += `<div style="padding:6px;border-radius:4px;font-size:0.85rem;${hasEvent ? 'background:var(--accent);color:#fff;font-weight:bold' : 'color:#aaa'}">${d}</div>`;
        }
        html += '</div></div>';
        calendarDiv.innerHTML = html;
      }
      
      window.calPrev = function() {
        currentCalendarMonth.setMonth(currentCalendarMonth.getMonth() - 1);
        fetchEvents();
      };
      
      window.calNext = function() {
        currentCalendarMonth.setMonth(currentCalendarMonth.getMonth() + 1);
        fetchEvents();
      };
      
      function escapeHTML(s){
        if (!s) return '';
        return String(s).replace(/[&<>"']/g, function(c){
          return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
        });
      }
      
      fetchEvents();
    })();
  </script>
</body>
</html>