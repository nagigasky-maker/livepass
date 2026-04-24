/* ─────────────────────────────────────────────
   LIVE PASS — Image cropper (touch-first)
   ─────────────────────────────────────────────
   Self-contained, dependency-free cropper with Instagram-style
   pan + pinch + slider zoom and a fixed aspect-ratio viewport.

   Usage:
     LivepassCropper.open({
       file,              // File | Blob
       aspectRatio: 1,    // e.g. 1 for avatar, 16/10 for cover
       outputWidth: 512,  // final rendered pixel width
       outputHeight: 512, //   "                      height
       quality: 0.88,     // JPEG quality
       title: 'アバターをトリミング',
       circle: true,      // overlay mask as a circle (avatar)
     }).then(({ blob, dataUrl }) => {
       // save blob + preview
     });

   The image is drawn into a target canvas using a simple affine:
     target(x,y) = image((x - cx + vw/2) / zoom,
                        (y - cy + vh/2) / zoom)
   where cx, cy is the pan offset and zoom is the current scale.
   The pan is clamped so the image never exposes the background.
   ───────────────────────────────────────────── */
(function(){
  const CSS = `
    #lpCropOverlay{position:fixed;inset:0;z-index:2000;background:rgba(0,0,0,.92);
      display:none;flex-direction:column;color:#fff;
      font-family:Arial,'Noto Sans JP',sans-serif;
      -webkit-tap-highlight-color:transparent;user-select:none;-webkit-user-select:none;
      touch-action:none}
    #lpCropOverlay.open{display:flex}
    .lp-crop-head{display:flex;align-items:center;justify-content:space-between;
      padding:calc(var(--safe-top,0px) + 12px) 18px 10px;gap:14px}
    .lp-crop-title{font-family:'IBM Plex Mono',ui-monospace,monospace;
      font-size:10px;letter-spacing:.28em;text-transform:uppercase;
      color:rgba(255,255,255,.8);flex:1;text-align:center}
    .lp-crop-btn{background:none;border:0;color:#fff;font-family:inherit;
      font-size:13px;font-weight:500;padding:8px 4px;cursor:pointer;
      letter-spacing:.02em;min-width:60px}
    .lp-crop-btn.primary{color:#3DAAFF;font-weight:700}
    .lp-crop-btn:active{opacity:.6}
    .lp-crop-stage{flex:1;position:relative;overflow:hidden}
    .lp-crop-canvas{position:absolute;inset:0;width:100%;height:100%}
    /* Cutout mask — darkens everything outside the viewport. */
    .lp-crop-mask{position:absolute;inset:0;pointer-events:none;
      background:rgba(0,0,0,.62)}
    .lp-crop-hole{position:absolute;
      box-shadow:0 0 0 9999px rgba(0,0,0,.62);
      border:1px solid rgba(255,255,255,.85);
      pointer-events:none}
    .lp-crop-hole.circle{border-radius:50%}
    .lp-crop-hole::before,.lp-crop-hole::after{
      content:"";position:absolute;left:0;right:0;
      border-top:0.5px solid rgba(255,255,255,.22)}
    .lp-crop-hole::before{top:33.3%}
    .lp-crop-hole::after{top:66.6%}
    .lp-crop-hole > span{position:absolute;top:0;bottom:0;
      border-left:0.5px solid rgba(255,255,255,.22)}
    .lp-crop-hole > span.v1{left:33.3%}
    .lp-crop-hole > span.v2{left:66.6%}
    .lp-crop-foot{padding:14px 24px calc(var(--safe-bot,0px) + 20px);
      display:flex;align-items:center;gap:14px}
    .lp-crop-foot svg{width:18px;height:18px;stroke:rgba(255,255,255,.6);
      fill:none;stroke-width:1.8;flex-shrink:0}
    .lp-crop-zoom{flex:1;-webkit-appearance:none;appearance:none;
      height:2px;background:rgba(255,255,255,.2);border-radius:99px;
      outline:none;margin:0}
    .lp-crop-zoom::-webkit-slider-thumb{-webkit-appearance:none;
      width:20px;height:20px;border-radius:50%;background:#fff;
      box-shadow:0 2px 6px rgba(0,0,0,.5);cursor:pointer}
    .lp-crop-zoom::-moz-range-thumb{width:20px;height:20px;border-radius:50%;
      background:#fff;border:0;box-shadow:0 2px 6px rgba(0,0,0,.5);cursor:pointer}
  `;

  function injectStyles(){
    if (document.getElementById('lpCropStyle')) return;
    const s = document.createElement('style');
    s.id = 'lpCropStyle';
    s.textContent = CSS;
    document.head.appendChild(s);
  }

  function ensureRoot(){
    let el = document.getElementById('lpCropOverlay');
    if (el) return el;
    el = document.createElement('div');
    el.id = 'lpCropOverlay';
    el.setAttribute('aria-hidden','true');
    el.innerHTML = `
      <div class="lp-crop-head">
        <button class="lp-crop-btn" data-act="cancel" type="button">キャンセル</button>
        <div class="lp-crop-title">トリミング</div>
        <button class="lp-crop-btn primary" data-act="done" type="button">完了</button>
      </div>
      <div class="lp-crop-stage">
        <canvas class="lp-crop-canvas"></canvas>
        <div class="lp-crop-mask"></div>
        <div class="lp-crop-hole"><span class="v1"></span><span class="v2"></span></div>
      </div>
      <div class="lp-crop-foot">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.5" y2="16.5" stroke-linecap="round"/></svg>
        <input class="lp-crop-zoom" type="range" min="1" max="4" step="0.01" value="1">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.5" y2="16.5" stroke-linecap="round"/><line x1="8" y1="11" x2="14" y2="11" stroke-linecap="round"/><line x1="11" y1="8" x2="11" y2="14" stroke-linecap="round"/></svg>
      </div>
    `;
    document.body.appendChild(el);
    return el;
  }

  // Load a File/Blob into an HTMLImageElement.
  function loadImage(fileOrBlob){
    return new Promise((resolve, reject) => {
      const url = URL.createObjectURL(fileOrBlob);
      const img = new Image();
      img.onload  = () => { URL.revokeObjectURL(url); resolve(img); };
      img.onerror = (e) => { URL.revokeObjectURL(url); reject(e); };
      img.src = url;
    });
  }

  function open(opts){
    const {
      file,
      aspectRatio  = 1,
      outputWidth  = 1024,
      outputHeight = Math.round(1024 / aspectRatio),
      quality      = 0.88,
      title        = 'トリミング',
      circle       = false,
    } = opts || {};

    return new Promise(async (resolve, reject) => {
      injectStyles();
      const root = ensureRoot();
      const stage  = root.querySelector('.lp-crop-stage');
      const canvas = root.querySelector('.lp-crop-canvas');
      const hole   = root.querySelector('.lp-crop-hole');
      const zoomEl = root.querySelector('.lp-crop-zoom');
      const titleEl= root.querySelector('.lp-crop-title');
      const btnCancel = root.querySelector('[data-act="cancel"]');
      const btnDone   = root.querySelector('[data-act="done"]');
      titleEl.textContent = title;
      hole.classList.toggle('circle', !!circle);

      let img;
      try { img = await loadImage(file); }
      catch(e) { reject(new Error('image load failed')); return; }

      // Hi-DPI canvas
      const dpr = window.devicePixelRatio || 1;
      const ctx = canvas.getContext('2d');

      // Viewport rectangle (the crop area) — centered in stage with a
      // small margin, keeping the requested aspect ratio.
      let stageW, stageH, viewW, viewH, viewX, viewY;
      let minZoom = 1, zoom = 1, cx = 0, cy = 0;

      function layout(){
        stageW = stage.clientWidth;
        stageH = stage.clientHeight;
        const pad = 20;
        const maxW = stageW - pad * 2;
        const maxH = stageH - pad * 2;
        const targetRatio = aspectRatio;
        if (maxW / maxH > targetRatio) {
          viewH = maxH;
          viewW = viewH * targetRatio;
        } else {
          viewW = maxW;
          viewH = viewW / targetRatio;
        }
        viewX = (stageW - viewW) / 2;
        viewY = (stageH - viewH) / 2;

        hole.style.left   = viewX + 'px';
        hole.style.top    = viewY + 'px';
        hole.style.width  = viewW + 'px';
        hole.style.height = viewH + 'px';

        // Size the canvas to the stage (so we can draw behind the hole).
        canvas.width  = Math.round(stageW * dpr);
        canvas.height = Math.round(stageH * dpr);
        canvas.style.width  = stageW + 'px';
        canvas.style.height = stageH + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        // Minimum zoom: image fills the viewport (cover behaviour).
        const fillX = viewW / img.naturalWidth;
        const fillY = viewH / img.naturalHeight;
        minZoom = Math.max(fillX, fillY);
        zoom = Math.max(zoom, minZoom);
        zoomEl.min = minZoom;
        zoomEl.max = minZoom * 4;
        zoomEl.step = (zoomEl.max - zoomEl.min) / 200;
        // Reset pan so image is centred in viewport on first layout.
        if (!layout._started) {
          cx = stageW / 2;
          cy = stageH / 2;
          zoom = minZoom;
          zoomEl.value = zoom;
          layout._started = true;
        }
        clampPan();
        draw();
      }

      // Keep the viewport fully covered by the image — don't expose bg.
      function clampPan(){
        const imgW = img.naturalWidth  * zoom;
        const imgH = img.naturalHeight * zoom;
        const leftMax  = viewX + imgW / 2;           // image left edge at viewport left
        const rightMin = viewX + viewW - imgW / 2;   // image right edge at viewport right
        if (imgW <= viewW) cx = viewX + viewW / 2;
        else cx = Math.min(leftMax, Math.max(rightMin, cx));
        const topMax    = viewY + imgH / 2;
        const bottomMin = viewY + viewH - imgH / 2;
        if (imgH <= viewH) cy = viewY + viewH / 2;
        else cy = Math.min(topMax, Math.max(bottomMin, cy));
      }

      function draw(){
        ctx.clearRect(0, 0, stageW, stageH);
        const w = img.naturalWidth  * zoom;
        const h = img.naturalHeight * zoom;
        ctx.drawImage(img, cx - w / 2, cy - h / 2, w, h);
      }

      // ── Interaction ──
      let pointers = new Map(); // id → {x,y}
      let startDist = 0, startZoom = 1;
      let startCx = 0, startCy = 0, startPx = 0, startPy = 0;

      function onPointerDown(e){
        stage.setPointerCapture(e.pointerId);
        pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
        if (pointers.size === 1) {
          startCx = cx; startCy = cy;
          startPx = e.clientX; startPy = e.clientY;
        } else if (pointers.size === 2) {
          const arr = Array.from(pointers.values());
          startDist = Math.hypot(arr[0].x - arr[1].x, arr[0].y - arr[1].y);
          startZoom = zoom;
        }
      }
      function onPointerMove(e){
        if (!pointers.has(e.pointerId)) return;
        pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
        if (pointers.size === 1) {
          cx = startCx + (e.clientX - startPx);
          cy = startCy + (e.clientY - startPy);
          clampPan(); draw();
        } else if (pointers.size === 2 && startDist > 0) {
          const arr = Array.from(pointers.values());
          const dist = Math.hypot(arr[0].x - arr[1].x, arr[0].y - arr[1].y);
          const k = dist / startDist;
          zoom = Math.max(minZoom, Math.min(minZoom * 6, startZoom * k));
          zoomEl.value = zoom;
          clampPan(); draw();
        }
      }
      function onPointerUp(e){
        pointers.delete(e.pointerId);
        if (pointers.size < 2) startDist = 0;
      }
      function onWheel(e){
        e.preventDefault();
        const k = Math.exp(-e.deltaY * 0.0015);
        zoom = Math.max(minZoom, Math.min(minZoom * 6, zoom * k));
        zoomEl.value = zoom;
        clampPan(); draw();
      }

      stage.addEventListener('pointerdown',   onPointerDown);
      stage.addEventListener('pointermove',   onPointerMove);
      stage.addEventListener('pointerup',     onPointerUp);
      stage.addEventListener('pointercancel', onPointerUp);
      stage.addEventListener('wheel',         onWheel, { passive:false });
      zoomEl.addEventListener('input', () => {
        zoom = parseFloat(zoomEl.value) || minZoom;
        clampPan(); draw();
      });

      function close(){
        root.classList.remove('open');
        root.setAttribute('aria-hidden','true');
        stage.removeEventListener('pointerdown',   onPointerDown);
        stage.removeEventListener('pointermove',   onPointerMove);
        stage.removeEventListener('pointerup',     onPointerUp);
        stage.removeEventListener('pointercancel', onPointerUp);
        stage.removeEventListener('wheel',         onWheel);
        zoomEl.replaceWith(zoomEl.cloneNode(true)); // drop listeners
        btnCancel.replaceWith(btnCancel.cloneNode(true));
        btnDone  .replaceWith(btnDone  .cloneNode(true));
        window.removeEventListener('resize', layout);
      }

      btnCancel.addEventListener('click', () => { close(); reject(new Error('cancelled')); });
      btnDone  .addEventListener('click', async () => {
        try {
          const out = document.createElement('canvas');
          out.width  = outputWidth;
          out.height = outputHeight;
          const octx = out.getContext('2d');
          octx.imageSmoothingQuality = 'high';
          // Map viewport → output canvas.
          // x in viewport [viewX, viewX+viewW] → [0, outputWidth]
          // The source rect on the image corresponds to:
          //   sourceX = (viewX - (cx - imgW/2)) / zoom
          //   sourceY = (viewY - (cy - imgH/2)) / zoom
          //   sourceW = viewW / zoom
          //   sourceH = viewH / zoom
          const imgW = img.naturalWidth  * zoom;
          const imgH = img.naturalHeight * zoom;
          const sx = (viewX - (cx - imgW / 2)) / zoom;
          const sy = (viewY - (cy - imgH / 2)) / zoom;
          const sw = viewW / zoom;
          const sh = viewH / zoom;
          octx.drawImage(img, sx, sy, sw, sh, 0, 0, outputWidth, outputHeight);
          const blob = await new Promise(r => out.toBlob(r, 'image/jpeg', quality));
          const dataUrl = out.toDataURL('image/jpeg', quality);
          close();
          resolve({ blob, dataUrl, width: outputWidth, height: outputHeight });
        } catch(err) {
          close();
          reject(err);
        }
      });

      window.addEventListener('resize', layout);
      root.classList.add('open');
      root.setAttribute('aria-hidden','false');
      // Layout needs the DOM to be rendered first.
      requestAnimationFrame(layout);
    });
  }

  window.LivepassCropper = { open };
})();
