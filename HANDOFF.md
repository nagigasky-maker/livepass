# LIVE PASS Handoff Document (2026-04-16)

## TL;DR

Firebase connected. UI restyled (magazine grid + Syne/DM Sans + color cards). Comment system added. Next: profile unification + Firestore post storage + domain acquisition.

---

## Current Production State

| Item | Value |
|---|---|
| Production URL | `livepass.vercel.app` |
| Latest commit | `4134709` on `main` |
| Firebase | `livepass-96f7b` (Spark plan / free) |
| Firebase Auth | Email/password enabled |
| Firestore | asia-northeast1, test mode |
| Firebase Storage | Test mode |
| Stripe | Env vars set only (not implemented) |

---

## Firebase Config

```javascript
const firebaseConfig = {
  apiKey: "AIzaSyAoEzBo25_Y-Xpda6uuTMZXwgzg2kLVHT4",
  authDomain: "livepass-96f7b.firebaseapp.com",
  projectId: "livepass-96f7b",
  storageBucket: "livepass-96f7b.firebasestorage.app",
  messagingSenderId: "574964996020",
  appId: "1:574964996020:web:5b3c702c88c5717c90c4e1"
};
```

- `firebase-init.js` — loaded by all 33 pages via `<script type="module">`
- CDN v12.12.0
- `window.FB` exports auth/db/storage/helper functions
- `onAuthStateChanged` syncs Firestore to localStorage (does NOT overwrite existing localStorage values)

---

## Data Storage Status

| Data | Location | Status |
|---|---|---|
| Account name, style, plan | **Firestore** `users/{uid}` + localStorage | Persisted |
| Email, password | **Firebase Auth** | Persisted |
| Profile photo | localStorage only | Needs Firebase Storage migration |
| Posts (article/event/ws/ex) | localStorage only | Needs Firestore migration |
| Cover images, video | localStorage / IndexedDB | Needs Firebase Storage migration |
| Follow/followers | localStorage only | Needs Firestore migration |
| Comments | localStorage only | Needs Firestore migration |
| Likes/saves | localStorage only | Needs Firestore migration |
| DM | localStorage only | Needs Firestore migration |

---

## Outstanding Tasks (by priority)

### Tier 1 — Required for launch (target: 2026-05-04)

| # | Task | Details |
|---|---|---|
| 1 | **Unify profile post detail** | Add comment section + adaptive color action bar to `livepass_profile.html` `#postModal`, matching HOME (`livepass_home.html`) behavior |
| 2 | **Posts to Firestore** | Replace `localStorage.setItem` with `Firestore collection.addDoc` for `livepass_articles`, `livepass_events`, etc. Enable shared feed across all users |
| 3 | **Media to Firebase Storage** | Send `downscaleImageFile()` output to `Storage.ref().put()`, store URL in Firestore. Remove data URL dependency from localStorage |
| 4 | **Custom domain + Vercel** | Acquire `livepass.app` (or similar), add to Vercel Settings > Domains |

### Tier 2 — Post-launch

| # | Task |
|---|---|
| 5 | Follow/followers to Firestore |
| 6 | DM to Firestore (realtime chat) |
| 7 | Comments, likes, saves to Firestore |
| 8 | Stripe ticket sales (event > payment > QR ticket) |
| 9 | Stripe PRO/BIZ subscription billing |

### Tier 3 — App Store

| # | Task |
|---|---|
| 10 | Capacitor native wrap > TestFlight / App Distribution |
| 11 | App Store / Google Play review submission |
| 12 | Push notifications (FCM) |

---

## Known Bugs and Caveats

1. **Settings email/phone/password fields** — Editable, but Firebase Auth `updateEmail`/`updatePassword` requires recent login. Re-authentication flow is not implemented.
2. **Onboarding account creation** — Currently auto-creates `{name}@livepass.app` temp email. User must change to their real email in Settings (UX is lacking).
3. **Firestore test mode** — 30-day expiry. Security rules must be configured before production.
4. **PWA cache on iOS** — Stale service workers persist. After changes, delete home screen icon and re-add.
5. **Magazine grid `BLOCK_PATTERN`** — Loops after 14 user posts. Display works but becomes visually repetitive.

---

## Session Commit History

```
4134709 feat: comment system + adaptive action bar colors
6764a08 style: article body text bolder (400>500, 14px)
455dd36 fix: stop Firebase overwriting local name/role edits
b9b294c fix: remove hardcoded NOBBY defaults + editable settings fields
819bf34 feat: Firebase integration — Auth + Firestore + Storage
eeda1c1 fix(profile): remove ALL MUST DANCE card
323c91f feat(profile): replace PASS HOLDER cards
f36dfe1 feat: onboarding options + profile-first + settings cleanup
4aca824 style(onboarding): widen letter-spacing YOU WEAR THERE
d9ef276 style(onboarding): shrink splash logo 160>80px
cf371a0 feat(onboarding): redesign splash — new logo + manifesto
dc17b66 feat: new yellow/orange PWA icon + larger header logo
0109441 fix: new logo + fix white-on-bright text in detail modal
a69383c style(home): shrink schedule date
c492185 fix(home): fix articles not rendering — color cards + SVG removal
f5a900e fix(home): auto-assign Dutch palette colors to no-cover articles
ed91fdd style: slim down bottom nav (44>36px)
3db0a67 style(home): color cards + This Week interleaved
02c5a73 style(home): magazine grid — irregular hand-placed blocks
d733484 style(compose): Format/Color section headers + color picker
6d42e03 style(home): restyle to magazine layout — Syne/DM Sans
0ba1ea6 fix(profile): fix + button by resolving isOtherUser reference error
70a252f feat: BIZ formats, drag-reorder, DM badges, credits size, workshop cats
343b88b feat: video/GIF/slideshow support + unified media display
573f3de feat: share sheet UI + avatar unification
0475a31 feat(brand): update header logo + dedicated PWA icons
244c21c chore(verify): unlock PRO/BIZ plans for manual verification
0a1546b feat(article): add PRO format system + HOME auto-layout engine
```

---

## Design System Reference

**Fonts:** Syne 700/800 (headings) + DM Sans 300/400/500 (meta) + Noto Sans JP (Japanese)

**Color Palette (Dutch):**

| Name | Hex |
|---|---|
| Orange | `#FF4500` |
| Blue | `#0040FF` |
| Yellow | `#FFD100` |
| Green | `#00BA4A` |
| Crimson | `#CC1F5A` |
| Violet | `#7B2FFF` |

**Feed:** Magazine grid (5 pattern loop + This Week interleaving)

**Plans:** FREE / PRO (980/mo) / BIZ (2,980/mo) — PRO/BIZ gated
