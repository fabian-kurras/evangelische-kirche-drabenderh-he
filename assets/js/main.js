(function(){
  const newsList = document.getElementById('news-list');
  const eventsList = document.getElementById('events-list');
  
  let currentCalendarMonth = new Date();

  async function fetchAll(){
    try{
      const res = await fetch('/evangelische-kirche-drabenderhöhe/api/get_items.php?type=all');
      const data = await res.json();
      renderNews(data.news);
      renderEvents(data.events);
    }catch(e){
      console.error('Fetch error:', e);
      if(newsList) newsList.textContent = 'Fehler beim Laden';
      if(eventsList) eventsList.textContent = 'Fehler beim Laden';
    }
  }

  function renderNews(news){
    if (!newsList) return;
    if (!news || news.length === 0) { newsList.innerHTML = '<p>Keine Nachrichten</p>'; return; }
    // Limit to 3 on homepage
    const limited = news.slice(0, 3);
    newsList.innerHTML = limited.map(n => `
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
  
  function renderEvents(events){
    if (!eventsList) return;
    if (!events || events.length === 0) { eventsList.innerHTML = '<p>Keine Termine</p>'; return; }
    // Limit to 3 on homepage
    const limited = events.slice(0, 3);
    eventsList.innerHTML = limited.map(e => `
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
    
    let html = `<div style="font-size:0.9rem">
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
    fetchAll();
  };
  
  window.calNext = function() {
    currentCalendarMonth.setMonth(currentCalendarMonth.getMonth() + 1);
    fetchAll();
  };

  function escapeHTML(s){
    if (!s) return '';
    return String(s).replace(/[&<>"']/g, function(c){
      return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
  }

  fetchAll();
  setInterval(fetchAll, 15000);
})();