/* ─────────────────────────────────────────────
   LIVE PASS — QR helpers (generate + scan)
   ─────────────────────────────────────────────
   · Generate: rely on api.qrserver.com (free, no auth, returns PNG).
     CSP `img-src https:` already allows it. We don't ship a JS QR
     library because:
       - the QR spec is finicky enough that a tiny self-rolled
         generator can produce unscannable output,
       - the only thing our PASS HOLDER needs is a single static
         image — server-rendered is fine.
     Falls back to the uggc-renamed Google Charts endpoint if the
     primary host is unreachable.
   · Scan: BarcodeDetector API (Safari 17+ / Chrome 88+). Polls the
     <video> element ~60×/sec via requestAnimationFrame.

   Public API (window.LivepassQR):
     toImageURL(text, {size?})   → "https://api…&data=text&size=…"
     paintInto(el, text, {size?})→ inserts/updates <img> inside el
     scan(videoEl, onMatch, {facingMode?}) → { stop() }
   ───────────────────────────────────────────── */
(function(){
  function pickHost(){
    // Primary: qrserver.com (no auth, 256² supported, fast).
    return 'https://api.qrserver.com/v1/create-qr-code/';
  }

  function toImageURL(text, opts){
    const size = (opts && opts.size) || 280;
    const margin = (opts && opts.margin) ?? 4;
    const params = new URLSearchParams({
      data: String(text || ''),
      size: `${size}x${size}`,
      margin: String(margin),
      ecc: 'M',
      format: 'png',
      'bgcolor': '255-255-255',
      color:    '5-11-31',
    });
    return pickHost() + '?' + params.toString();
  }

  function paintInto(el, text, opts){
    if (!el) return null;
    let img = el.querySelector('img.lp-qr-img');
    if (!img) {
      img = document.createElement('img');
      img.className = 'lp-qr-img';
      img.alt = 'QR';
      img.style.cssText = 'width:100%;height:auto;display:block';
      el.appendChild(img);
    }
    img.src = toImageURL(text, opts);
    return img;
  }

  async function scan(videoEl, onMatch, opts){
    if (!('BarcodeDetector' in window)) {
      throw new Error('BarcodeDetectorが利用できません。Safari 17+ / Chrome をお使いください。');
    }
    const detector = new BarcodeDetector({ formats: ['qr_code'] });
    const stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: (opts && opts.facingMode) || 'environment' },
      audio: false,
    });
    videoEl.srcObject = stream;
    videoEl.setAttribute('playsinline','');
    videoEl.muted = true;
    await videoEl.play();
    let alive = true;
    let lastValue = '';
    let lastEmittedAt = 0;
    async function tick(){
      if (!alive) return;
      try {
        const codes = await detector.detect(videoEl);
        if (codes && codes.length) {
          const v = codes[0].rawValue;
          const now = Date.now();
          // Debounce: ignore the same code within 1.5s.
          if (v && (v !== lastValue || (now - lastEmittedAt) > 1500)) {
            lastValue = v;
            lastEmittedAt = now;
            try { onMatch(v); } catch(_){}
          }
        }
      } catch(_){}
      if (alive) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
    return {
      stop(){
        alive = false;
        try { stream.getTracks().forEach(t => t.stop()); } catch(_){}
        try { videoEl.srcObject = null; } catch(_){}
      },
    };
  }

  window.LivepassQR = { toImageURL, paintInto, scan };
})();
