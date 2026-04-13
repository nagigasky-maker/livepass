# LIVE PASS — Deployment Wake-up Log

This file exists purely to trigger Vercel auto-deployment when
webhook delivery from GitHub to Vercel has stalled.

## Deployment history

- **v68** (2026-04-13): Wake-up attempt after PRs #59-#68 failed to
  auto-deploy. Vercel dashboard shows only PRs through #58 as
  production deployments. GitHub Deployments tab confirms the
  same list. Something between GitHub webhook → Vercel deploy
  trigger got stuck. This commit forces a fresh main branch
  update to see if that re-triggers the pipeline.

## If this file keeps updating but Vercel still serves old content

You need to manually redeploy via the Vercel dashboard:
1. Go to vercel.com/dashboard → livepass project
2. Deployments tab → find the top deployment
3. Click the "⋯" 3-dot menu → Redeploy
4. In the dialog, **uncheck "Use existing Build Cache"**
5. Make sure Git ref is set to `main` with the latest commit
6. Click Redeploy

This will force Vercel to fetch the current state of main and
deploy it fresh, bypassing whatever webhook state is broken.
