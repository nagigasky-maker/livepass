/* ─────────────────────────────────────────────
   LIVE PASS — Category visibility helper
   ─────────────────────────────────────────────
   Filters both the + compose sheet and profile sub-sub-tabs based
   on the user's PROFILE CATEGORIES toggles (Settings page).

   Reads:
     localStorage.livepass_visible_categories   JSON array, subset of
        ['article','event','workshop','exhibition']
     users/{uid}.visibleCategories              (profile.html only,
        fetched for other-user views)

   Exposes `window.LivepassCategories`:
     · ALL              — canonical category list
     · load()           — current local array
     · applyToCompose() — hide/show .cs-opt buttons
     · applyToTabs(cats)— hide/show .sst-btn + #sstab-* + re-activate
                          the first visible one if needed

   The auto-apply for the compose sheet runs on DOMContentLoaded so
   every page that includes this file gets it for free. Profile page
   calls applyToTabs() explicitly after deciding whose categories
   apply (own vs. ?user=…).
   ───────────────────────────────────────────── */
(function(){
  const KEY = 'livepass_visible_categories';
  const ALL = ['article','event','workshop','exhibition'];

  function load(){
    try {
      const raw = localStorage.getItem(KEY);
      if (!raw) return ALL.slice();
      const arr = JSON.parse(raw);
      if (!Array.isArray(arr) || !arr.length) return ALL.slice();
      const filtered = arr.filter(c => ALL.includes(c));
      return filtered.length ? filtered : ALL.slice();
    } catch(_){ return ALL.slice(); }
  }

  function applyToCompose(cats){
    if (!cats) cats = load();
    document.querySelectorAll('.cs-opt[data-type]').forEach(btn => {
      const t = btn.dataset.type;
      if (ALL.includes(t)) btn.style.display = cats.includes(t) ? '' : 'none';
    });
  }

  function applyToTabs(cats){
    if (!cats) cats = load();
    ALL.forEach(c => {
      const visible = cats.includes(c);
      document.querySelectorAll(`.sst-btn[data-tab="${c}"]`).forEach(el => {
        el.style.display = visible ? '' : 'none';
      });
      const panel = document.getElementById(`sstab-${c}`);
      if (panel) panel.style.display = visible ? '' : 'none';
    });
    // Re-pick an active tab if the current one got hidden.
    const active = document.querySelector('.sst-btn.active');
    if (!active || !cats.includes(active.dataset.tab)) {
      document.querySelectorAll('.sst-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.sstab-panel').forEach(p => p.classList.remove('active'));
      const firstBtn = document.querySelector(`.sst-btn[data-tab="${cats[0]}"]`);
      const firstPanel = document.getElementById(`sstab-${cats[0]}`);
      if (firstBtn)   firstBtn.classList.add('active');
      if (firstPanel) firstPanel.classList.add('active');
    }
  }

  window.LivepassCategories = { ALL, load, applyToCompose, applyToTabs };

  // Auto-apply compose-sheet filter on every page.
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => applyToCompose());
  } else {
    applyToCompose();
  }
})();
