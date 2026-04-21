/* ─────────────────────────────────────────────────────────────
   EXPASS · Paywall modal + invite code redeem
   ─────────────────────────────────────────────────────────────

   Shared UI invoked by `window.openPaywall(feature)` from any page
   that gates a feature behind a paid plan (販売 / 無制限予約 / etc).

   At launch we don't sell subscriptions yet — the Stripe checkout
   goes live on event day. Until then the modal is purely
   informational + an invite-code input for the 30-artist seed.

   Usage:
     <script src="/livepass_paywall.js" defer></script>
     ...
     btn.addEventListener('click', () => window.openPaywall('sell'));

   Invite redeem:
     POST /api/redeem-invite { code, uid } → { ok, plan, expiresAt }
   Client stores plan in localStorage.livepass_plan and mirrors to
   Firestore users/{uid}.plan when logged in.
   ───────────────────────────────────────────────────────────── */

(function(){
  if (window.__expassPaywallInit) return;
  window.__expassPaywallInit = true;

  // Inject CSS once
  const css = `
  #pwOverlay{
    position:fixed;inset:0;z-index:10000;
    background:rgba(0,0,0,.72);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
    display:none;align-items:flex-end;justify-content:center;
    padding:0 0 env(safe-area-inset-bottom,0);
    font-family:Arial,'Noto Sans JP',sans-serif;
  }
  #pwOverlay.open{display:flex;animation:pwOverlayIn .3s ease-out both}
  @keyframes pwOverlayIn{from{opacity:0}to{opacity:1}}
  .pw-sheet{
    position:relative;width:100%;max-width:560px;
    background:#0a0a0a;color:#EDEBE6;
    border-top-left-radius:20px;border-top-right-radius:20px;
    border:0.5px solid rgba(255,255,255,.08);
    padding:22px 22px calc(24px + env(safe-area-inset-bottom,0));
    max-height:92dvh;overflow-y:auto;-webkit-overflow-scrolling:touch;
    animation:pwSheetUp .35s cubic-bezier(.2,.8,.2,1) both;
  }
  @keyframes pwSheetUp{from{transform:translateY(100%)}to{transform:translateY(0)}}
  .pw-handle{width:44px;height:4px;border-radius:99px;background:rgba(237,235,230,.25);margin:0 auto 16px}
  .pw-head{display:flex;align-items:center;gap:12px;margin-bottom:6px}
  .pw-lock{
    width:36px;height:36px;border-radius:50%;
    background:rgba(245,200,66,.14);
    display:flex;align-items:center;justify-content:center;
    color:#F5C842;flex-shrink:0;
  }
  .pw-lock svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2}
  .pw-title{font-family:Arial,'Noto Sans JP',sans-serif;font-weight:900;
    font-size:16px;letter-spacing:.02em;color:#EDEBE6;line-height:1.2}
  .pw-sub{font-family:'IBM Plex Mono',monospace;font-size:8px;letter-spacing:.26em;
    text-transform:uppercase;color:#F5C842;margin-top:2px}
  .pw-body{font-size:12px;line-height:1.75;color:rgba(237,235,230,.72);margin:14px 0 4px}
  .pw-body b{color:#EDEBE6;font-weight:900}
  .pw-tiers{display:flex;flex-direction:column;gap:8px;margin:14px 0 14px}
  .pw-tier{
    display:flex;align-items:flex-start;gap:10px;
    padding:10px 12px;border-radius:10px;
    background:rgba(255,255,255,.03);border:0.5px solid rgba(255,255,255,.06);
  }
  .pw-tier .pw-pill{
    flex-shrink:0;padding:3px 8px;border-radius:99px;
    font-family:'IBM Plex Mono',monospace;font-size:8px;letter-spacing:.18em;
    font-weight:800;text-transform:uppercase;
  }
  .pw-tier.artist   .pw-pill{background:rgba(0,229,160,.18);color:#00E5A0}
  .pw-tier.pro      .pw-pill{background:rgba(61,170,255,.18);color:#3DAAFF}
  .pw-tier.business .pw-pill{background:rgba(245,200,66,.18);color:#F5C842}
  .pw-tier-txt{font-size:10.5px;line-height:1.5;color:rgba(237,235,230,.75)}
  .pw-perks{
    background:rgba(255,255,255,.02);border:0.5px solid rgba(255,255,255,.05);
    border-radius:10px;padding:12px 14px;margin:0 0 14px;
  }
  .pw-perks-head{font-family:'IBM Plex Mono',monospace;font-size:8px;
    letter-spacing:.26em;text-transform:uppercase;color:rgba(237,235,230,.4);margin-bottom:6px}
  .pw-perks ul{list-style:none;padding:0;margin:0}
  .pw-perks li{font-size:11px;line-height:1.8;padding-left:14px;position:relative;color:rgba(237,235,230,.72)}
  .pw-perks li::before{content:"·";position:absolute;left:2px;top:0;color:#00E5A0;font-weight:900}
  .pw-release{
    background:linear-gradient(135deg,rgba(61,170,255,.08),rgba(0,229,160,.04));
    border:0.5px solid rgba(61,170,255,.22);border-radius:12px;padding:14px 14px;margin-bottom:10px;
  }
  .pw-release-t{font-family:Arial,'Noto Sans JP',sans-serif;font-weight:700;font-size:12px;color:#EDEBE6;margin-bottom:4px}
  .pw-release-s{font-size:10px;color:rgba(237,235,230,.55);line-height:1.6}
  .pw-invite-wrap{margin-top:10px}
  .pw-invite-label{font-family:'IBM Plex Mono',monospace;font-size:8px;
    letter-spacing:.26em;text-transform:uppercase;color:rgba(237,235,230,.4);margin-bottom:6px}
  .pw-invite-row{display:flex;gap:8px}
  .pw-invite-input{
    flex:1;min-width:0;padding:11px 14px;
    background:rgba(255,255,255,.05);border:0.5px solid rgba(255,255,255,.12);border-radius:10px;
    font-family:'IBM Plex Mono',monospace;font-size:12px;letter-spacing:.08em;
    color:#EDEBE6;outline:none;text-transform:uppercase;
  }
  .pw-invite-input:focus{border-color:#00E5A0;background:rgba(0,229,160,.04)}
  .pw-invite-btn{
    flex-shrink:0;padding:11px 18px;border-radius:10px;
    background:#00E5A0;color:#000;
    font-family:Arial,'Noto Sans JP',sans-serif;font-weight:900;font-size:11px;
    letter-spacing:.12em;text-transform:uppercase;
  }
  .pw-invite-btn:disabled{background:rgba(237,235,230,.12);color:rgba(237,235,230,.35)}
  .pw-msg{font-size:10.5px;margin-top:8px;min-height:14px;line-height:1.5}
  .pw-msg.err{color:#FF6B66}
  .pw-msg.ok{color:#00E5A0}
  .pw-close{
    display:block;width:100%;margin-top:14px;padding:13px;
    background:transparent;border:0.5px solid rgba(237,235,230,.18);border-radius:99px;
    color:rgba(237,235,230,.65);
    font-family:Arial,'Noto Sans JP',sans-serif;font-weight:500;font-size:11px;letter-spacing:.14em;
  }
  `;
  const style = document.createElement('style');
  style.textContent = css;
  document.head.appendChild(style);

  // Inject DOM
  const overlay = document.createElement('div');
  overlay.id = 'pwOverlay';
  overlay.setAttribute('aria-hidden','true');
  overlay.innerHTML = `
    <div class="pw-sheet" role="dialog" aria-label="サブスクリプション案内">
      <div class="pw-handle"></div>
      <div class="pw-head">
        <div class="pw-lock">
          <svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        </div>
        <div>
          <div class="pw-sub">EXPASS · Subscription</div>
          <div class="pw-title" id="pwTitle">この機能はサブスクライバー限定です</div>
        </div>
      </div>
      <p class="pw-body">
        <b>EXPASS™</b> の有料プランで、<b>販売・無制限予約・手数料優遇</b> をご利用いただけます。
        どのプランが合うかは職種で選べます。
      </p>
      <div class="pw-tiers">
        <div class="pw-tier artist">
          <span class="pw-pill">Artist</span>
          <div class="pw-tier-txt">ミュージシャン・DJ・プロデューサー・ダンサー・カメラマン・芸術家・スケーター</div>
        </div>
        <div class="pw-tier pro">
          <span class="pw-pill">Pro</span>
          <div class="pw-tier-txt">オーガナイザー・スポーツ選手・クリエイター・プログラマー</div>
        </div>
        <div class="pw-tier business">
          <span class="pw-pill">Business</span>
          <div class="pw-tier-txt">企業・アパレル・飲食・小売店舗</div>
        </div>
      </div>
      <div class="pw-perks">
        <div class="pw-perks-head">特典</div>
        <ul>
          <li>SCREEN 上での物販・チケット販売</li>
          <li>予約・出演イベントの無制限受付</li>
          <li>手数料 5% (FREE = 10%)</li>
          <li>今後 Atelier 復活時の優先アクセス</li>
        </ul>
      </div>
      <div class="pw-release">
        <div class="pw-release-t">本格リリース：イベント当日</div>
        <div class="pw-release-s">
          決済フローはイベント日に起動します。
          先行アーティストには <b>招待コード</b> を個別配布。下に入力して適用できます。
        </div>
      </div>
      <div class="pw-invite-wrap">
        <div class="pw-invite-label">Invite code</div>
        <div class="pw-invite-row">
          <input id="pwInvite" class="pw-invite-input" type="text" placeholder="ARTIST30-XXXX" autocomplete="off" autocapitalize="characters">
          <button id="pwInviteBtn" class="pw-invite-btn" type="button">適用</button>
        </div>
        <div id="pwMsg" class="pw-msg"></div>
      </div>
      <button class="pw-close" id="pwClose" type="button">閉じる</button>
    </div>
  `;
  document.body.appendChild(overlay);

  const titleEl = overlay.querySelector('#pwTitle');
  const inputEl = overlay.querySelector('#pwInvite');
  const btnEl   = overlay.querySelector('#pwInviteBtn');
  const msgEl   = overlay.querySelector('#pwMsg');
  const closeEl = overlay.querySelector('#pwClose');

  const FEATURE_TITLES = {
    sell:      'この機能はサブスクライバー限定です',
    reserve:   '予約枠に達しました',
    profile:   '大アバターレイアウトはサブスク特典です',
    default:   'この機能はサブスクライバー限定です',
  };

  function open(feature){
    titleEl.textContent = FEATURE_TITLES[feature] || FEATURE_TITLES.default;
    msgEl.textContent = ''; msgEl.classList.remove('err','ok');
    inputEl.value = '';
    overlay.classList.add('open');
    overlay.setAttribute('aria-hidden','false');
    if (navigator.vibrate) navigator.vibrate(12);
  }
  function close(){
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden','true');
  }
  overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
  closeEl.addEventListener('click', close);
  window.addEventListener('keydown', e => {
    if (overlay.classList.contains('open') && e.key === 'Escape') close();
  });

  // Invite redeem
  btnEl.addEventListener('click', async () => {
    const code = (inputEl.value || '').trim().toUpperCase();
    if (!code) {
      msgEl.textContent = 'コードを入力してください。';
      msgEl.classList.remove('ok'); msgEl.classList.add('err');
      return;
    }
    msgEl.textContent = '確認中…'; msgEl.classList.remove('err','ok');
    btnEl.disabled = true;

    try {
      const uid = (window.FB && window.FB.currentUser && window.FB.currentUser.uid) ||
                  localStorage.getItem('livepass_uid') || null;
      const resp = await fetch('/api/redeem-invite', {
        method: 'POST',
        headers: { 'Content-Type':'application/json' },
        body: JSON.stringify({ code, uid })
      });
      const data = await resp.json().catch(()=>({}));

      if (!resp.ok || !data.ok) {
        msgEl.textContent = data.reason || 'コードが正しくないか、すでに使用済みです。';
        msgEl.classList.remove('ok'); msgEl.classList.add('err');
        btnEl.disabled = false;
        return;
      }

      // Success — persist plan locally + mirror to Firestore if we can
      try { localStorage.setItem('livepass_plan', data.plan || 'artist'); } catch(_){}
      if (data.planExpiresAt) {
        try { localStorage.setItem('livepass_plan_expires', String(data.planExpiresAt)); } catch(_){}
      }
      try {
        if (window.FB && window.FB.currentUser) {
          const ref = window.FB.doc(window.FB.db, 'users', window.FB.currentUser.uid);
          window.FB.updateDoc(ref, {
            plan: data.plan || 'artist',
            planExpiresAt: data.planExpiresAt || null,
            planSource: 'invite',
          }).catch(()=>{});
        }
      } catch(_){}

      msgEl.textContent = `${(data.plan || 'ARTIST').toUpperCase()} プランを適用しました。`;
      msgEl.classList.remove('err'); msgEl.classList.add('ok');
      if (navigator.vibrate) navigator.vibrate([8,50,12]);
      setTimeout(() => { close(); location.reload(); }, 1400);
    } catch(err) {
      msgEl.textContent = '通信に失敗しました。あとでもう一度お試しください。';
      msgEl.classList.remove('ok'); msgEl.classList.add('err');
      btnEl.disabled = false;
    }
  });
  inputEl.addEventListener('keydown', e => { if (e.key === 'Enter') btnEl.click(); });

  window.openPaywall = open;
})();
