/* ─────────────────────────────────────────────
   LIVE PASS — Native share + toast helpers
   ─────────────────────────────────────────────
   Thin wrapper around navigator.share with a clipboard fallback,
   plus a tiny toast component for transient feedback. Included by
   any page that wants external share buttons (/home, /calendar).

   Exposes:
     window.shareContent(title, text, url) → Promise<bool>
     window.showToast(message)              → void
   ───────────────────────────────────────────── */
(function(){
  async function shareContent(title, text, url){
    const data = { title, text, url };
    if (navigator.share) {
      try {
        await navigator.share(data);
        return true;
      } catch (e) {
        if (e && e.name === 'AbortError') return false;
        console.warn('share failed:', e && e.message);
      }
    }
    // Fallback — copy the URL to the clipboard.
    try {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        await navigator.clipboard.writeText(url);
        showToast('リンクをコピーしました');
        return true;
      }
    } catch (e) {
      // Last-ditch: let the user copy manually.
    }
    try { prompt('リンクをコピー:', url); } catch(_){}
    return true;
  }

  function showToast(msg){
    let t = document.getElementById('lpToast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'lpToast';
      t.style.cssText =
        'position:fixed;left:50%;bottom:calc(var(--nav-h,60px) + var(--safe-bot,0px) + 40px);' +
        'transform:translate(-50%,10px);' +
        'background:rgba(237,235,230,.95);color:#000;' +
        'padding:10px 22px;border-radius:99px;' +
        "font-family:Arial,'Noto Sans JP',sans-serif;" +
        'font-size:12px;font-weight:600;letter-spacing:.02em;' +
        'z-index:1500;' +
        'opacity:0;pointer-events:none;' +
        'box-shadow:0 6px 24px rgba(0,0,0,.32);' +
        'transition:opacity .3s ease, transform .3s ease;';
      document.body.appendChild(t);
    }
    t.textContent = String(msg || '');
    // Force reflow so the transition plays even on rapid calls.
    t.offsetHeight;
    t.style.opacity = '1';
    t.style.transform = 'translate(-50%,0)';
    clearTimeout(t._tid);
    t._tid = setTimeout(() => {
      t.style.opacity = '0';
      t.style.transform = 'translate(-50%,10px)';
    }, 2000);
  }

  window.shareContent = shareContent;
  window.showToast    = showToast;
})();
