# Supervisor health dashboard remediation

Use this runbook when the repository supervisor health dashboard becomes stale.

## Signals

- The pinned supervisor issue has an old `last_refresh:` marker.
- The stats scheduler exists and is loaded, but wrapper runs overlap or remain active beyond the expected timeout.
- Recent stats logs stop before the affected repository is updated.

## Remediation

1. Confirm the launchd job and stats log are present.
2. Check for an active `stats-wrapper.sh` process that has exceeded `STATS_TIMEOUT`.
3. Terminate the stale wrapper process, remove the stale stats pidfile, and run one targeted health issue refresh for this repository.
4. Verify the pinned dashboard issue now has a fresh `last_refresh:` marker and recent `updated_at` timestamp.

## Verification evidence for issue #32

- Scheduler plist existed and launchd reported `com.aidevops.aidevops-stats-wrapper` loaded.
- The stats log showed repeated overlapping runs and a stale wrapper process, with the repository dashboard skipped before the targeted refresh.
- A targeted refresh updated dashboard issue #10 with `last_refresh: 2026-05-10T00:38:30Z`.
