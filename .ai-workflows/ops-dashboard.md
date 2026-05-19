# Supervisor dashboard recovery

The repository's aidevops supervisor dashboard is maintained outside the
WordPress plugin runtime by the local stats scheduler. If a dashboard freshness
alert reports that the pinned dashboard issue is stale while `stats.log` still
shows hourly `Health issues: updated ... repo(s)` messages, verify the local
health-issue cache before changing plugin code.

## Triage

1. Confirm the background scheduler job exists and is running.
2. Inspect `~/.aidevops/logs/stats.log` for the affected repository.
3. Check the pinned dashboard issue body for the `last_refresh:` marker.
4. Confirm the cache file naming expected by the current aidevops version.

## Cache migration failure mode

Older aidevops releases wrote role-qualified cache files such as
`health-issue-<user>-supervisor-<owner>-<repo>`. Current dashboard refresh code
expects `health-issue-<canonical-user>-<owner>-<repo>`. If only the older cache
file exists and the repository has no active PRs, assigned issues, or workers,
the activity guard can skip resolving the already-open dashboard issue, leaving
the existing dashboard stale.

Restore the expected cache entry to the pinned dashboard issue number, then run
the dashboard update path again. Verify that the pinned issue's `updated_at` and
`last_refresh:` marker are current before closing the freshness alert.
