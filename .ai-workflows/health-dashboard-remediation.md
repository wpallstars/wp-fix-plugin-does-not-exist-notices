# Supervisor health dashboard remediation

Use this runbook when the repository supervisor health dashboard becomes stale.

## Signals

- The pinned supervisor issue has an old `last_refresh:` marker.
- The stats scheduler exists and is loaded, but wrapper runs overlap or remain
  active beyond the expected timeout.
- Recent stats logs stop before the affected repository is updated.

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

3. Terminate the stale wrapper process and remove the stale stats pidfile at
   `~/.aidevops/logs/stats.pid`.

4. Run one targeted health issue refresh for this repository:

   ```bash
   REPO_SLUG="wpallstars/wp-fix-plugin-does-not-exist-notices" \
   REPO_PATH="$HOME/Git/wordpress/wp-fix-plugin-does-not-exist-notices" \
   bash -lc '
   source "$HOME/.aidevops/agents/scripts/shared-constants.sh"
   source "$HOME/.aidevops/agents/scripts/worker-lifecycle-common.sh"
   source "$HOME/.aidevops/agents/scripts/stats-functions.sh"
   _update_health_issue_for_repo "$REPO_SLUG" "$REPO_PATH" "" "" ""
   '
   ```

5. Verify the pinned dashboard issue now has a fresh `last_refresh:` marker and
   recent `updated_at` timestamp.
