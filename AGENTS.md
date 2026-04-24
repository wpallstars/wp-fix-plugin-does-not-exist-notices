# AGENTS.md — AI Agent Guide for wp-fix-plugin-does-not-exist-notices

This file provides precise, verified guidance for AI agents working in this repository. Read it before editing any file to avoid `read:file_not_found` and `edit:not_read_first` errors.

## Repository Purpose

**Fix 'Plugin file does not exist' Notices** — a WordPress plugin that detects active plugins whose files are missing, adds them to the Plugins list with a "Remove Notice" action link, and safely removes their database references.

- GitHub: `wpallstars/wp-fix-plugin-does-not-exist-notices`
- Namespace: `WPALLSTARS\FixPluginDoesNotExistNotices`
- Text domain: `wp-fix-plugin-does-not-exist-notices`
- Current version: see `wp-fix-plugin-does-not-exist-notices.php` header

## Exact File Map (authoritative — verified against git ls-files)

**CRITICAL**: PHP class files in `includes/` use PascalCase. The docs and `require_once` paths must match exactly. On Linux (case-sensitive), wrong case = fatal error.

```
wp-fix-plugin-does-not-exist-notices.php   # Plugin entry point — sets Version, requires includes/Plugin.php
includes/Plugin.php                         # Main Plugin class — loads dependencies, inits components
includes/Core.php                           # Core class — all WordPress hooks and plugin logic
includes/Updater.php                        # Updater class — handles Git Updater / WP.org update sources
admin/lib/admin.php                         # Admin\Admin class — admin page rendering, enqueues
admin/lib/modal.php                         # Admin\Modal class — update source selector modal
admin/css/admin-styles.css                  # General admin styles
admin/css/update-source-selector.css        # Styles for the update source modal
admin/js/admin-scripts.js                   # General admin JS
admin/js/update-source-selector.js          # JS for the update source modal
languages/wp-fix-plugin-does-not-exist-notices.pot  # Translation template
build.sh                                    # Build/release script
scripts/deploy-local.sh                     # Local deployment helper
composer.json                               # Composer config (no vendor/ committed)
README.md                                   # Developer readme
readme.txt                                  # WordPress.org readme (keep in sync with README.md)
CHANGELOG.md                                # Changelog (keep in sync with README.md and readme.txt)
```

**Directories that DO NOT exist** (do not attempt to read or create files here):

- `admin/images/` — does not exist
- `admin/partials/` — does not exist
- `admin/settings/` — does not exist
- `admin/tools/` — does not exist
- `vendor/` — not committed; loaded at runtime if present

## Before Editing Any File

1. Read the file first: `Read includes/Core.php` — not `includes/core.php` (wrong case fails).
2. Re-read the file if another tool call may have modified it since your last read.
3. Verify the file path with `git ls-files '<pattern>'` when uncertain.

## Version Management

Version appears in **four places** — all must be updated together:

1. `wp-fix-plugin-does-not-exist-notices.php` — `Version:` header and `new WPALLSTARS\FixPluginDoesNotExistNotices\Plugin(__FILE__, '2.4.0')` (replace `2.4.0` with the new version)
2. `readme.txt` — `Stable tag:` and changelog section
3. `CHANGELOG.md` — top entry
4. `AGENTS.md` — this file, item 1 above (replace the hardcoded version string)

Note: `includes/Plugin.php` has no direct version constant; the version is passed in via the constructor call in the entry point file.

`README.md` changelog section must also stay in sync with `CHANGELOG.md`.

## Common Tasks

### Fix a bug in core plugin logic

Edit `includes/Core.php`. All WordPress hooks are registered in the constructor.

### Change admin UI or settings

Edit `admin/lib/admin.php` (Admin class) and/or `admin/lib/modal.php` (Modal class).
CSS: `admin/css/admin-styles.css` or `admin/css/update-source-selector.css`.
JS: `admin/js/admin-scripts.js` or `admin/js/update-source-selector.js`.

### Bump version for a release

1. Update version in `wp-fix-plugin-does-not-exist-notices.php` (both header and constructor call).
2. Update `Stable tag:` in `readme.txt`.
3. Add changelog entry to `readme.txt`, `CHANGELOG.md`, and `README.md`.
4. Update the version string in `AGENTS.md` (item 1 of the Version Management list above).

### Add or fix update source logic

Edit `includes/Updater.php`.

## Git Workflow

- Branch naming: `feature/`, `fix/`, `patch/`, `refactor/` prefixes
- One branch per issue; one PR per issue
- Push to both `github` and `gitea` remotes
- Do not include version numbers in branch names during development

## AI Workflow Documentation

Extended guidance is in `.ai-workflows/`:

- `folder-structure.md` — directory layout with correct filenames
- `git-workflow.md` — branching, commit messages, remote management
- `feature-development.md` — feature development process
- `bug-fixing.md` — bug investigation and fix process
- `release-process.md` — full release checklist including Git Updater notes
- `dev-prefs-memory.md` — persistent developer preferences
