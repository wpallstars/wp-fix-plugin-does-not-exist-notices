# Supervisor health dashboard remediation

Use this runbook when the repository supervisor health dashboard becomes stale.

## Signals

- The pinned supervisor issue has an old `last_refresh:` marker.
- The stats scheduler exists and is loaded, but wrapper runs overlap or remain
  active beyond the expected timeout.
- Recent stats logs stop before the affected repository is updated.

A loaded scheduler with a successful exit status does not prove that every
repository refreshed. Per-repository timeouts (`rc=124`) and a deferred repository
pass can leave an individual dashboard stale while the wrapper finishes normally.

## Remediation

1. Confirm the scheduler and stats log are present:

   ```bash
   launchctl list | grep -i aidevops-stats-wrapper
   ls -la ~/Library/LaunchAgents/com.aidevops.aidevops-stats-wrapper.plist
   tail -40 ~/.aidevops/logs/stats.log
   ```

2. Check for an active `stats-wrapper.sh` process that has exceeded `STATS_TIMEOUT`.
   The default timeout is `600` seconds and is defined near the top of
   `~/.aidevops/agents/scripts/stats-wrapper.sh`; an operator can confirm the
   runtime value with a dry run:

   ```bash
   STATS_DRY_RUN=1 bash ~/.aidevops/agents/scripts/stats-wrapper.sh --dry-run
   ```

3. Choose recovery from the evidence, not the dashboard age alone:

   - If the wrapper is confirmed stale, preserve its PID, elapsed time and recent
     log evidence before terminating it. Remove `~/.aidevops/logs/stats.pid` only
     after confirming it belongs to that stopped process, not a live successor.
   - If recent runs finish normally, do not kill the scheduler or remove its
     pidfile. Inspect `rc=124`, the reported `stage`, and repository-pass deferrals.
     A timeout in `data-gather` or `body-publication` can prevent a fresh body;
     a timeout in `maintenance` can occur after publication. Check the actual
     dashboard marker to distinguish these outcomes.

   The dry run above skips API writes. It proves the functions can load, not that
   the remote dashboard has refreshed.

4. Run one targeted health issue refresh for this repository:

   ```bash
   REPO_SLUG="<owner/repo>" \
   REPO_PATH="<your/local/path>" \
   bash -lc '
   source "$HOME/.aidevops/agents/scripts/shared-constants.sh"
   source "$HOME/.aidevops/agents/scripts/worker-lifecycle-common.sh"
   source "$HOME/.aidevops/agents/scripts/stats-functions.sh"
   _update_health_issue_for_repo "$REPO_SLUG" "$REPO_PATH" "" "" ""
   '
   ```

5. Verify the pinned dashboard issue now has a fresh `last_refresh:` marker and
   recent `updated_at` timestamp. For this repository's alert target:

   ```bash
   gh api repos/wpallstars/wp-fix-plugin-does-not-exist-notices/issues/10 \
     --jq '{updated_at, refresh: (.body | capture("last_refresh: (?<timestamp>[^\\n]+)").timestamp)}'
   ```

   Verify both fields; comments can change `updated_at` without refreshing the
   dashboard body. Keep the alert open if the targeted refresh fails or times out,
   and record the failed stage instead of manually advancing the marker.
