# CLAUDE.md — Developer Context for LIVE PASS

## What is this project?

LIVE PASS is a PWA cultural platform (events, articles, workshops, exhibitions). Vanilla HTML/CSS/JS frontend, Firebase backend, hosted on Vercel.

**Live URL:** livepass.vercel.app
**Firebase project:** livepass-96f7b (Spark/free, asia-northeast1)

## Architecture

- **No framework.** Every page is a standalone `.html` file with inline `<style>` and `<script>`.
- **`firebase-init.js`** is the shared Firebase module imported by all 33+ pages via `<script type="module">`. It exports `window.FB` with auth, db, storage, and helper functions.
- **Data flow:** `onAuthStateChanged` syncs Firestore `users/{uid}` to localStorage. If localStorage already has a value, Firestore does NOT overwrite it (intentional — lets users edit locally before sync).
- **Most data is still in localStorage.** Only account name/style/plan are in Firestore. Posts, media, follows, comments, likes, DMs are all localStorage-only and need migration.

## Key Files

| File | Purpose |
|---|---|
| `firebase-init.js` | Firebase init + auth state listener + helpers |
| `index.html` | Root redirect (→ profile or onboarding) |
| `livepass_home.html` | Main feed — magazine grid, comments, action bar |
| `livepass_profile.html` | User profile — PASS HOLDER cards + post grid |
| `livepass_compose_article.html` | Article composer (6 formats, color picker, video/GIF) |
| `livepass_settings.html` | Account settings (name, plan, email, password) |
| `livepass_onboarding.html` | First-run flow (6 user types) |
| `vercel.json` | Vercel routing rules |
| `manifest.json` | PWA manifest |

## Design System

- **Fonts:** Syne 700/800 (headings), DM Sans 300/400/500 (meta), Noto Sans JP (Japanese)
- **Colors:** Dutch palette — `#FF4500` orange, `#0040FF` blue, `#FFD100` yellow, `#00BA4A` green, `#CC1F5A` crimson, `#7B2FFF` violet
- **Feed:** Magazine grid (5 block patterns, looping) with "This Week" sections interleaved
- **Plans:** FREE / PRO (980/mo) / BIZ (2,980/mo) — PRO/BIZ locked behind Stripe (not implemented)

## Common Patterns

### Adding Firebase functionality
1. Use `window.FB.db` (Firestore), `window.FB.auth` (Auth), `window.FB.storage` (Storage)
2. Import from CDN v12.12.0 — do NOT add npm packages for Firebase
3. Always update both Firestore AND localStorage to keep them in sync

### Working with posts
- Posts are stored in localStorage keys: `livepass_articles`, `livepass_events`, `livepass_workshops`, `livepass_exhibitions`
- Each is a JSON array of objects
- Cover images are stored as data URLs in localStorage (or IndexedDB for large files)
- The migration to Firestore is the #1 priority

### Deployment
- Push to `main` auto-deploys on Vercel
- No build step — plain static files
- Vercel routing is in `vercel.json`

## Known Gotchas

1. **Firebase Auth re-auth** — `updateEmail`/`updatePassword` throws if user hasn't logged in recently. Re-auth flow is NOT implemented.
2. **Temp email on signup** — Onboarding creates `{name}@livepass.app` as a placeholder. User must manually change in Settings.
3. **Firestore test mode** — Expires after 30 days. Security rules MUST be set before production launch.
4. **iOS PWA cache** — Old service workers persist aggressively. Only fix is delete-and-readd the home screen icon.
5. **localStorage size limits** — Data URLs for images/video can hit the ~5MB localStorage cap. This is why Storage migration is critical.

## Priority Roadmap

### Must-do (pre-launch)
1. Unify profile post detail modal with HOME (comments + adaptive action bar)
2. Migrate posts to Firestore (replace `localStorage.setItem` with `addDoc`)
3. Migrate images/video to Firebase Storage (replace data URLs)
4. Custom domain + Vercel DNS

### Soon after launch
5. Follow/followers → Firestore
6. DM → Firestore (realtime)
7. Comments/likes/saves → Firestore
8. Stripe ticket sales (event → payment → QR ticket)
9. Stripe subscriptions (PRO/BIZ monthly billing)

### Later (app store)
10. Capacitor native wrap → TestFlight / App Distribution
11. App Store / Google Play submission
12. Push notifications (FCM)
