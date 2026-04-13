# LIVE PASS — Deployment Wake-up Log

This file exists purely to trigger Vercel auto-deployment when
webhook delivery from GitHub to Vercel has stalled.

## Deployment history

- **v68** (2026-04-13): First wake-up attempt. Ignored by Vercel.
- **v69** (2026-04-13): Second wake-up AFTER Git integration was
  reconnected in the Vercel dashboard. If this commit ALSO fails
  to deploy, the issue is deeper than the webhook — it's something
  about how Vercel's project state got stuck.

## If this file keeps updating but Vercel still serves old content

You need to manually Redeploy with the latest commit specified:

1. Vercel dashboard → livepass → Deployments
2. Click the "⋯" menu on the top Production deployment
3. Select "Redeploy"
4. **Crucial**: in the Redeploy dialog, change the "Commit" field
   to the LATEST commit hash of main (not the default one shown)
5. Uncheck "Use existing Build Cache"
6. Click Redeploy

If that ALSO fails, use Vercel CLI:

```bash
npm install -g vercel
vercel login
git clone https://github.com/nagigasky-maker/livepass.git
cd livepass
vercel --prod
```

This bypasses the broken webhook pipeline entirely.
