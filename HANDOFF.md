# EXPASS — Session Handoff

Last updated: 2026-04-22

Repository: `nagigasky-maker/livepass` · Production: `https://expass.app`
Active branch: `claude/create-handoff-docs-MMJtF` · merged to `main` at every stop.

---

## 1. How to resume

1. Open this file first.
2. `git log --oneline -40` on `main` to see recent work.
3. `git status` must be clean.
4. Continue from **§5 Next session** at the bottom.

All work lives on `main`; the feature branch is a lightweight staging area
that gets `--no-ff` merged in. No hidden state.

---

## 2. Stack summary

- **Pure static HTML/JS PWA**, Vercel hosts. No build step.
- **Auth**: Firebase email/password (`firebase-init.js` → `window.FB`).
- **Data**: Firestore (`posts/{id}`, `users/{uid}`, `reservations/{id}`,
  `subscriptions/{id}`, `usernames/{nameLower}`). Rules file is
  `firestore.rules` (deployed by operator).
- **Storage**: Firebase Storage for post covers / videos, rules file
  `storage.rules`. Browser IndexedDB (`livepass_media`, `livepass_covers`,
  `livepass_avatars`) carries large blobs so localStorage's ~5 MB quota
  never fills up.
- **Payments**: Stripe test mode, `api/checkout.js` + `api/webhook.js`.
  EXPASS is the merchant of record (model A); each creator does not
  need their own 特商法 page. Single disclosure lives at
  `/terms/tokushou` (`livepass_terms_tokushou.html`).
- **AI**: `api/ai-write.js` → Anthropic Messages for body / event copy.
- **Invite codes**: `/api/redeem-invite` validates against three env-var
  buckets and hands out a plan for 365 days.

---

## 3. Pages (routes → files)

| Route | File | Notes |
|---|---|---|
| `/` `/screen` `/home` | `livepass_home.html` | Feed, 4-btn bottomnav, first-visit splash |
| `/collection` `/profile` | `livepass_profile.html` | 168px avatar (video avatar supported), other-user layout with `?user=` |
| `/calendar` | `livepass_calendar.html` | Reservations (launch-live) |
| `/discovery` `/search` | `livepass_search.html` | FOLLOWING / DISCOVERY sub-tabs |
| `/compose/article` | `livepass_compose_article.html` | Cover → Title → Category → Author → Body → Access → Collab → (hidden sale section) → Color preview |
| `/compose/event` `/workshop` `/exhibition` `/record` | `livepass_compose_*.html` | |
| `/settings` | `livepass_settings.html` | VERIFICATION / ACCOUNTS / SUBSCRIPTION / ACCOUNT |
| `/onboarding` | `livepass_onboarding.html` | Auto-sends email verification + claims username |
| `/login` | `livepass_login.html` | `expass.GIF` hero |
| `/loading` | `expass_loading.html` | CRT typewriter splash (4-5s) |
| `/invite/:code` | `livepass_invite.html` | Artist invite landing |
| `/card/:id`  (**NEW**) | `livepass_card_claim.html` | Artist card QR / NFC scan landing |
| `/terms/tokushou` | `livepass_terms_tokushou.html` | Single 特商法 page |
| `/atelier` `/atelier/record` `/atelier/frame` | hidden from nav, reachable by URL | Future UPDATE |

---

## 4. Where we stand right now

### Identity / verification
- Email verification: `sendEmailVerification` fires on signup,
  flag mirrored to `localStorage.livepass_email_verified`.
- Settings → VERIFICATION row shows status + resend button.
- Calendar reserve **blocked until verified**. Settings upgrade /
  invite redeem also blocked.
- **Username uniqueness**: `usernames/{nameLower}` doc collection,
  claimed in a probe-then-setDoc pair on signup. Rolls back the
  auth account if the write loses a race.
- **Invite codes**: 50 codes generated — 20 PRO + 30 STANDARD.
  Operator stores them in Vercel env vars `INVITE_CODES_PRO` /
  `INVITE_CODES_STANDARD`, hands codes to seed artists manually.

### Posting + media
- localStorage only carries metadata + URL strings. Covers that fail
  to upload to Firebase are stashed as blobs in IndexedDB
  (`cover_<id>`, `extra_<id>_<i>`, `media_<id>`). Feed hydrates via
  `MediaDB.get` / `CoverDB.get` on render.
- Video: creator-picked loop flag (`videoLoop`), honoured on SCREEN +
  edit preview. iOS autoplay policy hardened (attrs before src + RAF
  kick + IntersectionObserver only pauses when fully off-screen).
- GIF upload is refused at the cover picker, with a "使いたい場合は
  MP4 に変換してください" modal. Atelier GIF→MP4 tool is a future UPDATE.
- Detail view reserves aspect-ratio per layout so body text doesn't
  jump when the cover finishes decoding.

### Avatar
- Still image or ≤4 s MP4. Video blob lives in IndexedDB
  (`livepass_avatars` / `avatar_blob`); JPEG poster in
  `localStorage.livepass_avatar` powers non-video-aware slots.
  Profile page + Settings ACCOUNTS list swap `<img>` for
  `<video autoplay muted loop playsinline>` on the active account.

### Launch scope / paywall
- SCREEN sale section (物販 / 音源 / EXPASS / SUPPORTER / VIP) is
  `display:none` — return after Stripe Checkout is live.
- CALENDAR reservations are live (same-day payment; cancellation
  fee charges the stored card via Stripe).
- Tip + artist subscription panel (per-artist Patreon-style) is
  live; paid-plan gating for SUPPORTER / VIP on compose.
- TICKET / EXPASS / goods tap on the detail page opens a
  "近日公開" modal (`showComingSoon`).

### Artist card landing (**this session**)
- `/card/<CARD_ID>` plays a drop-in card animation, optional pulse,
  then CTA "アカウントに追加" that writes
  `users/{uid}.cards = arrayUnion(cardId)`. Unsigned visitors are
  sent to `/onboarding?card=<id>` with the card id stashed in
  `localStorage.livepass_pending_card` so home.html can claim on
  `fb-auth-ready`.
- Card artwork: `/expass4artist.png` (already in repo).
- `firestore.rules` updated: `cards` added to the users whitelist.

---

## 5. Next session — priority list

### A. 🚀 Launch-critical (must ship before event day)

| # | Item | Est. | Notes |
|---|---|---|---|
| A1 | **Upload `livepass_pending_card` auto-claim in home.html** | 30 m | Add fb-auth-ready handler like the pending-invite path. Currently the stash exists but no consumer. |
| A2 | **`/api/claim-card` server endpoint** | 45 m | Right now the client writes `users/{uid}.cards` directly. Move to a server endpoint (like `/api/redeem-invite`) so we can validate card ids against an env allowlist and stop forged ids. |
| A3 | **Firestore / Storage rules deploy** | 15 m | Files are in the repo (`firestore.rules`, `storage.rules`). Must be deployed from a local clone or pasted into Firebase Console before launch. Critical for both the `usernames/`, `cards[]`, Storage image-upload paths. |
| A4 | **Stripe Connect (Model A) setup** | 2-3 h | Destination Charge or Separate Charge + Transfer. EXPASS is the merchant of record, artists receive payouts. Needs Stripe Dashboard Connect platform onboarding + env vars + a simple `/api/checkout.js` extension. |
| A5 | **Vercel env vars loaded** | 15 m | `INVITE_CODES_PRO`, `INVITE_CODES_STANDARD`, `STRIPE_SECRET_KEY`, `STRIPE_WEBHOOK_SECRET`, `ANTHROPIC_API_KEY`, `FIREBASE_SERVICE_ACCOUNT` (when we move claim-card server-side). Confirm each one. |

### B. Identity hardening (staged)

| # | Item | Est. |
|---|---|---|
| B1 | **Stripe Identity** for paid sellers — `/api/identity-session` creates a Stripe Identity session, Stripe hosted page does the capture, webhook writes `users/{uid}.identityVerified`. Required before a creator can receive payouts. | 3-4 h |
| B2 | **Firebase Phone Auth** — reCAPTCHA v3 + SMS code flow, `users/{uid}.phoneVerified`. Optional second factor; surface in Settings VERIFICATION row. | 2-3 h |

### C. Media / perf polish (pre-launch if time allows)

| # | Item | Est. |
|---|---|---|
| C1 | **Video transcode on client** — `MediaRecorder` + canvas re-encode >20MB uploads to 720p ~2 Mbps. | 3 h |
| C2 | **GIF → MP4 converter in Atelier** — ffmpeg.wasm, tile under `/atelier/convert`. | 2 h |
| C3 | **Fade-in cover on SCREEN** so the bgColor placeholder doesn't flash during cover load. | 30 m |
| C4 | **Profile video-avatar everywhere** — nav bar `bn-avatar`, comment bylines, article author byline all still show the JPEG poster. | 1 h |

### D. Follow-up improvements

| # | Item | Est. |
|---|---|---|
| D1 | **Settings name-change claim** — currently the `livepass_account_name` field is free-form. Add `usernames/` claim dance on rename. | 30 m |
| D2 | **Invite redeem server-side** — move `users/{uid}.plan` writes to a firebase-admin endpoint, strip the plan whitelist further. | 2 h |
| D3 | **Cancel-fee Stripe Connect Transfer** — confirmed-no-show on Calendar reservations → `transfer.create` to the event author. | 1 h |
| D4 | **2nd COLLECTION card** — "2枚目" content (AMD referenced). User to supply copy. | — |

---

## 6. Environment variables checklist

On Vercel → Project Settings → Environment Variables:

```
STRIPE_SECRET_KEY           sk_test_... (test) / sk_live_... (prod)
STRIPE_WEBHOOK_SECRET       whsec_...
STRIPE_PUBLISHABLE_KEY      pk_test_... / pk_live_...
ANTHROPIC_API_KEY           sk-ant-...
INVITE_CODES_PRO            EXPASS-PRO-XXXXXX,…   (20 codes)
INVITE_CODES_STANDARD       EXPASS-STD-XXXXXX,…   (30 codes)
# Future:
FIREBASE_SERVICE_ACCOUNT    {"type":"service_account",…}  (for admin writes)
STRIPE_IDENTITY_SECRET      (optional, separate key)
```

Seed codes for this launch window:

```
# PRO (20) — major-artist seats
EXPASS-PRO-7ZH006, EXPASS-PRO-XKYO1F, EXPASS-PRO-RAQ19H,
EXPASS-PRO-7K5W1D, EXPASS-PRO-YT48IJ, EXPASS-PRO-UHX20M,
EXPASS-PRO-6KDNE0, EXPASS-PRO-XEKK4Q, EXPASS-PRO-WL60DZ,
EXPASS-PRO-ODIAD7, EXPASS-PRO-XE202L, EXPASS-PRO-EKPWWG,
EXPASS-PRO-OHMJYL, EXPASS-PRO-351HGX, EXPASS-PRO-FQWM92,
EXPASS-PRO-TT25VG, EXPASS-PRO-9GRJ3P, EXPASS-PRO-LD6JMC,
EXPASS-PRO-E3I7BW, EXPASS-PRO-NDPVY8

# STANDARD (30)
EXPASS-STD-C6GMCM, EXPASS-STD-ENDJYW, EXPASS-STD-B1GZ9A,
EXPASS-STD-HT889W, EXPASS-STD-GHSCPR, EXPASS-STD-4JOIA9,
EXPASS-STD-DSKME4, EXPASS-STD-C5Y9RC, EXPASS-STD-IK8BYU,
EXPASS-STD-T9Q95O, EXPASS-STD-VB6QIM, EXPASS-STD-XHB5IX,
EXPASS-STD-ZL222F, EXPASS-STD-8DYUGT, EXPASS-STD-R3CIK4,
EXPASS-STD-RIHGE5, EXPASS-STD-K0VXU1, EXPASS-STD-GLXK3W,
EXPASS-STD-8COEQY, EXPASS-STD-OX52ZS, EXPASS-STD-A4J6L9,
EXPASS-STD-JTL7CM, EXPASS-STD-4H8IUS, EXPASS-STD-N5RM1F,
EXPASS-STD-4KDYJE, EXPASS-STD-QXBGIX, EXPASS-STD-YQQ9E8,
EXPASS-STD-B4LGXL, EXPASS-STD-IKMGAD, EXPASS-STD-HQJ88W
```

Redemption URL: `https://expass.app/invite/<CODE>`

---

## 7. Files worth knowing

| Concern | File |
|---|---|
| Firebase bootstrap / auth exports | `firebase-init.js` |
| Feed render, video swap, observer | `livepass_home.html` |
| Detail modal (`openDetail`) | `livepass_home.html` |
| Compose entry | `livepass_compose_article.html` |
| Invite landing | `livepass_invite.html` |
| Card landing (**new**) | `livepass_card_claim.html` |
| Paywall modal | `livepass_paywall.js` |
| Splash | `expass_loading.html` |
| Rules | `firestore.rules`, `storage.rules` |
| Legal | `livepass_terms_tokushou.html` |

---

## 8. Break-glass

If production is broken:

1. Check Vercel Deployments — is the latest `main` green?
2. Firebase Console → Authentication → confirm `expass.app` is in the
   authorized domains list.
3. Stripe Dashboard → Webhooks → any 4xx/5xx deliveries?
4. If all else fails, revert with `git revert <bad-sha> && git push`.
   The last known-good tag (if tagged) lives at the top of main.
