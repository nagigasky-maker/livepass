# LIVE PASS

A PWA-first cultural platform for live events, articles, workshops, and exhibitions. Artists, venues, and fans connect through event discovery, ticket sales, and community features.

**Live:** [livepass.vercel.app](https://livepass.vercel.app)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | Vanilla HTML/CSS/JS (no framework) |
| Fonts | Syne 700/800, DM Sans 300/400/500, Noto Sans JP |
| Backend | Firebase (Auth, Firestore, Storage) |
| Hosting | Vercel (auto-deploy from `main`) |
| PWA | `manifest.json` + service worker |
| Payments | Stripe (env vars set, not yet implemented) |

### Firebase Project

- **Project ID:** `livepass-96f7b`
- **Region:** `asia-northeast1`
- **Plan:** Spark (free)
- **Auth:** Email/password enabled
- **Firestore:** Test mode (30-day expiry — set security rules before production)
- **Storage:** Test mode

---

## Project Structure

```
livepass/
├── firebase-init.js              # Firebase init (imported by all pages)
├── index.html                    # Root — redirects to /profile or /onboarding
├── manifest.json                 # PWA manifest
├── vercel.json                   # Vercel routing config
├── package.json
│
├── livepass_home.html            # HOME feed (magazine grid + comments)
├── livepass_profile.html         # Profile (PASS HOLDER + post list)
├── livepass_onboarding.html      # First-run (YOU WEAR THERE + 6 user types)
├── livepass_settings.html        # Account settings (plan, email, password)
│
├── livepass_compose_article.html # Article composer (6 formats + color picker)
├── livepass_compose_event.html   # Event composer
├── livepass_compose_workshop.html# Workshop composer
├── livepass_compose_exhibition.html # Exhibition composer
│
├── livepass_calendar.html        # Calendar (event details + credits)
├── livepass_messages.html        # DM (unread badges)
├── livepass_followers.html       # Follow/follower list
├── livepass_search.html          # Search
│
├── livepasslogo.png              # Header logo (yellow/orange 1000x160)
├── livepass-icon-{180,192,512}.png # PWA icons (purple bg)
├── assets/splash-logo.png        # Splash logo (1000x747)
├── livepass_card01-01.jpg        # PASS card 1 (LIVEPASS)
└── livepass_card02.jpg           # PASS card 2 (Jazzin UFO)
```

---

## Data Storage (current state)

| Data | Where | Status |
|---|---|---|
| Account name, style, plan | Firestore `users/{uid}` + localStorage | Synced |
| Email, password | Firebase Auth | Synced |
| Profile photo | localStorage only | Needs Storage migration |
| Posts (article/event/ws/ex) | localStorage only | Needs Firestore migration |
| Cover images, video | localStorage / IndexedDB | Needs Storage migration |
| Follow/followers | localStorage only | Needs Firestore migration |
| Comments | localStorage only | Needs Firestore migration |
| Likes/saves | localStorage only | Needs Firestore migration |
| DM | localStorage only | Needs Firestore migration |

---

## Design System

### Color Palette (Dutch)

```
Orange:  #FF4500
Blue:    #0040FF
Yellow:  #FFD100
Green:   #00BA4A
Crimson: #CC1F5A
Violet:  #7B2FFF
```

### Feed Layout

Magazine grid with 5 block patterns on loop, interleaved with "This Week" sections.

### Plans

| Plan | Price | Status |
|---|---|---|
| FREE | Free | Active |
| PRO | 980/mo | Locked (Stripe not implemented) |
| BIZ | 2,980/mo | Locked (Stripe not implemented) |

---

## Development

### Local Setup

1. Clone the repo
2. Serve with any static server (e.g. `npx serve .`)
3. Firebase config is embedded in `firebase-init.js` — no `.env` needed for dev

### Deployment

Push to `main` triggers auto-deploy on Vercel.

---

## Known Issues

1. **Settings email/password update** — `updateEmail`/`updatePassword` requires recent login; re-auth flow not implemented
2. **Onboarding account creation** — Creates temp email `{name}@livepass.app`; user must change in Settings (poor UX)
3. **Firestore test mode** — 30-day expiry; security rules needed before launch
4. **PWA cache on iOS** — Stale service worker persists; delete icon and re-add to fix
5. **Magazine grid pattern** — Loops after 14 posts; visually monotonous at scale
