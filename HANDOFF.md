# EXPASS — Session Handoff Document

Last updated: 2026-04-20 (end of this session)

Repository: `nagigasky-maker/livepass` · Production domain: `https://expass.app`  
Active feature branch: `claude/fix-image-api-error-8MzaF` (merged into `main` on every completed task)

---

## 1. How to resume

**Anything useful has been committed + pushed.** The chat can die — the
repository is the source of truth. To resume in a fresh session:

1. Open this file (`HANDOFF.md`) first.
2. Check `git log --oneline -40` on `main` to see recent work.
3. Verify `git status` is clean; the hooks refuse to stop with dirty trees.
4. Continue with the "Open requests" section at the bottom.

Both `main` and `claude/fix-image-api-error-8MzaF` are kept in sync. Merges
are fast-forward merges with `--no-ff` so history is traceable.

---

## 2. What's in the repo (architectural overview)

- **Pure static HTML + vanilla JS PWA.** No build step. Vercel rewrites
  pretty URLs to `livepass_*.html`. See `vercel.json`.
- **Auth**: Firebase Auth (email/password) — `firebase-init.js` boots the
  SDK and exposes it on `window.FB`. `onAuthStateChanged` syncs the user
  profile into localStorage (only when the fields are empty — Settings
  edits win).
- **Data**: Firestore (`posts/{id}`, `users/{uid}`, `reservations/{id}`,
  `subscriptions/{id}`). localStorage is a fast cache for the feed.
  Per-user filters now apply on SCREEN so multi-account device doesn't
  leak NOBBY's posts to a new signup.
- **Storage**: Firebase Storage for post covers/avatars. Top cover images
  and GIFs live in IndexedDB (`livepass_covers`, `livepass_media`) so the
  ~5MB localStorage quota doesn't drop them.
- **Payments**: Stripe in test mode. `api/checkout.js` creates Checkout
  Sessions, `api/webhook.js` verifies signatures, `api/stripe-config.js`
  exposes the publishable key to the client, and the reservation card
  form on `livepass_calendar.html` uses Stripe Elements (PCI SAQ-A).
- **AI**: `api/ai-write.js` proxies Anthropic Messages API (for article
  body generation and event-supplement markdown).

Pages of note:

| Route | File | Status |
|---|---|---|
| `/screen` (= /) | `livepass_home.html` | User-article feed, TOP cover upload, + button |
| `/calendar` | `livepass_calendar.html` | Event list, TOP cover upload, + button, Stripe Elements |
| `/profile` / `/collection` | `livepass_profile.html` | Profile, SUB button in topbar, subtabs swapped for heart toggle |
| `/saved` | `livepass_profile.html` | Same file, `body.on-saved-route` flips PASS→SAVED panel |
| `/search` / `/discovery` | `livepass_search.html` | Account search, manual add form, follow |
| `/settings` | `livepass_settings.html` | Account info, multi-account switcher, signout |
| `/compose/{article,event,workshop,exhibition,record}` | `livepass_compose_*.html` | Post creation forms |
| `/atelier` | `livepass_atelier.html` | Tool grid. Frame locked (row 2). Record Booth live |
| `/atelier/record` | `livepass_atelier_record.html` | Record display + `+ create` entry to compose_record |
| `/atelier/frame` | `livepass_atelier_frame.html` | Frame studio (currently gated/locked on Atelier) |
| `/login` | `livepass_login.html` | `expass.GIF` full-bleed background, white-tinted wxpasslogo01 |
| `/onboarding` | `livepass_onboarding.html` | First-time signup flow |

---

## 3. Security posture (as of this hand-off)

### ✅ Shipped in this session

- **Security headers** in `vercel.json`: CSP, HSTS (2 years +preload),
  Referrer-Policy, Permissions-Policy, X-Frame-Options, X-Content-Type-Options.
  CSP whitelists only Firebase, Stripe, Anthropic, Google Fonts.
- **Firestore rules** committed as `firestore.rules`:
  - `users/{uid}` owner-only writes
  - `posts/{id}` public read, authorUid-only writes
  - `reservations/{id}` owner-only
  - `subscriptions/{id}` server-only writes
  - Default deny everywhere else
- **Storage rules** committed as `storage.rules`: size + mime enforced,
  public read, authed writes, default deny.
- **Stripe Elements** on the reservation card form. Card number / expiry
  / CVC never touch our DOM. We store only `last4` + `paymentMethod.id`.
  PCI scope dropped from SAQ-D to SAQ-A.
- **Stripe webhook signature verification** works (`constructEvent` with
  raw body buffer, `bodyParser:false`).
- **Sign-out PII cleanup**: loops through every identity key
  (`livepass_account_name/_ja/_en`, role/kind/style/goals, avatar,
  signed_in, uid, plan, email/temp_email/last_email).
- **SCREEN per-user filter** — new signups don't inherit NOBBY's posts.
- **Account-scoped filter in home.html fb-auth-ready** — local articles
  are kept as source of truth; Firestore results only layered on top
  when author matches.
- **Deleted-posts blacklist** (`livepass_deleted_ids`) — Firestore resync
  can no longer resurrect locally-deleted events/articles.

### ⏳ Still on the user's side (ops-level)

1. **Vercel env vars**: confirmed that `STRIPE_SECRET_KEY`,
   `STRIPE_WEBHOOK_SECRET`, `STRIPE_PUBLISHABLE_KEY` are set on Vercel
   (verified live in session — Stripe Elements renders in production).
   `ANTHROPIC_API_KEY` likely set too (AI features work).
2. **Firebase rules deployment**: the `firestore.rules` / `storage.rules`
   files are in the repo but NOT auto-deployed by Vercel. Deploy with
   `firebase deploy --only firestore:rules,storage` from a local clone
   (needs `firebase-tools` and login). OR paste them into the Firebase
   Console → Rules tab. **Do this before launch.**
3. **Stripe webhook endpoint** created in Stripe Dashboard pointing to
   `https://expass.app/api/webhook` — confirmed in session. Signing
   secret copied into Vercel. ✅
4. **AppCheck** not enabled yet. Optional hardening — stops random
   browsers from calling Firestore/Storage directly without going
   through the app.

### 🟡 Known trade-offs

- `firebase-init.js` still exposes the Firebase API key. This is by
  design (browser key is public; access control lives in the Rules).
- `'unsafe-inline'` + `'unsafe-eval'` in CSP's script-src are needed
  because most pages have inline `<script>` blocks. Long-term: extract
  to external files and move to nonces.
- `livepass_articles` + post covers live in localStorage — when the
  quota (~5MB) fills up with large GIFs/photos, saves silently fail.
  Top covers and GIFs already migrated to IndexedDB; the remaining
  article covers still hit localStorage. Migrating them is the next
  big structural improvement (see Pending §5).

---

## 4. Feature state

### Pages that match the unified design (floating + button, flat black
cards, Arial/Noto-Sans-JP, mint accents):
`SCREEN`, `CALENDAR`, `PROFILE`, `SEARCH`, `SETTINGS`, `ATELIER`,
`COMPOSE/*`.

### Compose pages
- **article** — cinema/PRO layouts unlocked for prototype. GIFs store
  JPEG first-frame poster in localStorage + full animated GIF in
  IndexedDB; SCREEN swaps the animated version in after render.
- **event/workshop/exhibition** — Stripe-elements-backed reservation
  form. Level selector (ALL LEVELS/BEGINNER/INTERMEDIATE/ADVANCED)
  added to workshop. Custom category field (free text) added to workshop.
- **record** — `livepass_compose_record.html`. Submit saves to
  `livepass_records` AND `livepass_articles` (type=`record`, layout=
  `square`). Redirects to `/screen` after post.

### Atelier (`/atelier`)
- Row 1: Record Booth (active)
- Row 2: 額装 (LOCKED — `pointer-events:none`, dashed, padlock icon,
  `SOON` label). Unlock by removing `locked` class + `aria-disabled`
  + restoring `<a href>`.
- Row 3: Coming Soon (always locked)

### Login page
- Full-bleed `/expass.GIF` background + dark scrim
- `/wxpasslogo01.png` at 120px / 42% max, `filter:brightness(0) invert(1)`
  for white
- Hero copy: `YOU WERE THERE` / `Sign in`
- Corner mark removed

### Multi-account on this device
- Settings has an ACCOUNTS section: create extra accounts, switch with
  a tap, delete entries. Storage: `livepass_accounts_list`
- NOTE: the content data (articles/events/saves) is still **global** —
  switching accounts does NOT switch posts. Per-uid namespacing is
  pending (§5).

---

## 5. Pending (open requests from the user)

These came up toward the end of the session and are **not yet
implemented** — just scoped:

### A. Two account tiers (artist vs. regular user)
**Requested layout:**
- **Artist** — invite-only, full current experience
- **Regular** — SCREEN + CALENDAR (own + followed) + COLLECTION, no
  Atelier entry, Discovery with Following/Discover subtabs
- **Other-user profile** — cover image + avatar-left / name+style+DM+
  Follow-right layout + SCREEN/CALENDAR subtabs

**Recommended phased approach (captured in chat):**

1. Settings toggle for `livepass_role: 'artist'|'user'` + gate Atelier
   + `+` record option on `role==='artist'` (15-20 min, **most impact
   for least effort**)
2. Follow-merged feed on SCREEN / Calendar (30-45 min)
3. Discovery subtabs (45 min)
4. Other-user profile layout (1 hr)
5. Real invite system with Firestore `invites/{code}` (deferred)

### B. Music compose form
- Agreed to defer ("あとででOK").
- Recommended path: extend Record Booth with `<input type="file"
  accept="audio/*">` and a small waveform / play control, rather than
  a separate `/compose/music`. Jackets already exist; we'd add audio
  blob (IndexedDB via `MediaDB.put('audio_' + id, blob)`) + player in
  the lightbox.

### C. Record edit layout tweaks
Still outstanding from the earlier "レコードの編集画面" ask:
- Title as heading (large)
- Artist name (medium) — above center
- Label name (small) — below center
- Text color picker
  
These apply to **`livepass_compose_record.html`** (compose) and should
likely also propagate to the preview + published record card. Partial
scaffolding is already there (preview has pl-title / pl-artist /
pl-labelname). Pending: make vertical positions match the spec, add
the text-color picker.

### D. Dark/Light theme toggle
Deferred — it would be a sizable refactor (every page uses
`!important` light-mode overrides on top of a dark base). Proper fix:
CSS custom properties + `html[data-theme]` attribute + nonce-based
stylesheet swap.

### E. uid-prefixed localStorage keys
Multi-account pollution root cause. Required before we ship
per-account content separation cleanly. Est: 1 hour.

---

## 6. Recent commits (last ~40) on `main`

Most recent first:
```
a5e557b Merge: record submit → SCREEN (resolve conflict)
c5c375d Merge: lock 額装 on row 2
e7f41fa Merge: Atelier redesign + keep main's record editor entry
47dac6c Merge: Record Booth editor MVP + CREATE entry (parallel session)
d5126d5 Record label layout + remove personal sample data (parallel session)
8b98485 Merge: remove NOBBY/AMD samples
82c98ef Record Booth editor MVP + booth integration (parallel session)
749099a Atelier: trim copy + register /compose/record route (parallel session)
... (see `git log --oneline -40`)
```

Several of the record-booth commits came from a parallel claude session
(branch `claude/compose-record-editor`). They've been merged cleanly.

---

## 7. How to test locally (paranoid dev loop)

```bash
# Preview branch (safe)
git checkout claude/fix-image-api-error-8MzaF

# Try changes
# ... edit files ...
git add -A && git commit -m "..."
git push origin claude/fix-image-api-error-8MzaF

# Merge to production
git checkout main
git pull --rebase origin main
git merge claude/fix-image-api-error-8MzaF --no-ff -m "Merge: ..."
git push origin main

# Vercel auto-deploys from main in ~1 min
# Production URL: https://expass.app
```

On iPhone PWA, there's no DevTools. Use the in-app diagnostic panel
that appears on empty SCREEN (prints account_name / FB uid /
localStorage counts). Remove it before launch (inline script at the
bottom of `livepass_home.html` under `initDiagPanel`).

---

## 8. Files that really matter (if debugging)

| Concern | File |
|---|---|
| Auth identity | `firebase-init.js` |
| Feed filter | `livepass_home.html` → `getUserArticles`, `fb-auth-ready` handler |
| Calendar ownership | `livepass_calendar.html` → `getUserSchedule`, `isMine` |
| Stripe payment | `api/stripe-config.js` + `livepass_calendar.html` card sheet |
| Webhook | `api/webhook.js` |
| Multi-account switcher | `livepass_settings.html` (near bottom) |
| Top cover IDB | `CoverDB` in `livepass_home.html` / `CalCoverDB` in `livepass_calendar.html` |
| Post deletion blacklist | `livepass_deleted_ids` key, used in home.html's fb-auth-ready |

---

## 9. If something is broken on production right now

1. Check Vercel Deployments tab — is the latest main deployment Ready?
2. Check Stripe Dashboard → Webhooks → recent deliveries → any 4xx/5xx?
3. Check browser Safari Developer Console (or the in-app diag panel
   if you can't connect a Mac) for the actual JS error.
4. Revert to a known-good commit with
   `git revert <bad-sha>` then push main.

---

End of handoff. Next session: pick up from §5 "Open requests".
