# LIVE PASS — Deployment Wake-up Log

This file exists purely to trigger Vercel auto-deployment.

## Deployment history

- **v68** (2026-04-13): First wake-up attempt. Ignored by Vercel.
- **v69** (2026-04-13): Second wake-up after Git integration reconnected.
- **v70** (2026-04-13): Third wake-up AFTER Pro plan upgrade. If Vercel
  auto-deploy was gated behind trial limits, this commit should now
  trigger a fresh production build from the latest main commit.
- **v71** (2026-04-15): Investigation across a fresh session. Verified via
  Vercel MCP that production is still stuck on `208af7c` (PR #58) from
  2d ago. PRs #59–#70 never materialized as deployments — not even as
  Canceled. Deploy Hook fires return `{"state":"PENDING"}` but no
  deployment entry is ever created. Checked every project setting:
  Production Branch = main ✓, Ignored Build Step = Automatic ✓,
  build/install/output commands = default ✓, Root Directory = `./` ✓,
  Require Verified Commits = OFF. Yet the AMD sibling branch
  `claude/build-allmustdance-site-jNfy4` deploys Claude commits as
  previews without issue. So the stall is main-branch-specific. Best
  remaining hypothesis: Vercel's internal SHA-dedupe cache mis-flagged
  `bd461b5` (or an ancestor) as already-deployed during the earlier
  wake-ups, so every subsequent main push is silently deduped. This
  commit introduces a fresh SHA from outside the previous session in
  hopes the webhook re-registers and the cache is bypassed. If this
  still fails, the last resort is Settings → Git → Disconnect and
  reconnect the GitHub integration.

## If auto-deploy still doesn't work

Use the Deploy Hook URL from Vercel Settings → Git → Deploy Hooks.
Opening it in a browser or curl'ing it triggers a manual deploy — but
note that during the 2026-04-15 investigation, even a PENDING hook
response failed to materialize into a deployment for main. In that
case, disconnect and reconnect the GitHub integration.
