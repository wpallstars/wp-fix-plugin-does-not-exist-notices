# Fix 'Plugin file does not exist' Notices — agent guide

WordPress plugin. Slug and text domain `wp-fix-plugin-does-not-exist-notices`,
namespace `WPALLSTARS\FixPluginDoesNotExistNotices`. Minimums: WordPress 6.2,
PHP 7.4. User docs: `README.md` (GitHub, written for people searching the
error) and `readme.txt` (WordPress.org format).

## Keep it small

The owner's direction: a small, lightweight plugin that helps with this one
problem. No settings page, stored options, dashboard boxes, promotions,
tracking or calls to outside services. Nothing loads outside the Plugins
screen. No scripts or styles unless there is no other way. Prefer deleting
code to adding it. Bigger ideas belong in SEO Pro Stack, which has the same
feature (Clean up deleted plugins).

## Files

- `wp-fix-plugin-does-not-exist-notices.php` — headers; loads the class in the admin only.
- `includes/Plugin.php` — the whole plugin: `load-plugins.php` finds missing active plugins (as core's `validate_active_plugins()` does, which runs later in the same request and switches them off) and cleans `recently_activated` and `uninstall_plugins`; `pre_current_active_plugins` prints the explanation.
- `build.sh` — release zip, using `.distignore`. `.github/workflows/release.yml` runs it for `vX.Y.Z` tags.

## Rules

- Do not change WordPress update behaviour (update transients, `plugins_api`, update checks). Updates come from the `GitHub Plugin URI` header. Never add an `Update URI` header.
- Capability checks before any change, escape on output, short plain admin copy in sentence case.
- Leave no PHP errors, warnings, notices or deprecations.
- `composer install` once, then `composer lint` (PHPCS, PHPStan) before every commit. Fix findings in the code.
- Test on a throwaway WordPress 6.2 / PHP 7.4 site (`wordpress:php7.4-apache` with `wp core download --version=6.2 --force`): activate a dummy plugin, delete its folder, open Plugins, check the notice and `wp-content/debug.log`.

## Releases

Version is in the main file's `Version:` header and `readme.txt` `Stable tag:`.
Add the entry to `CHANGELOG.md` and `readme.txt` (newest version only, under
10 KB). Publish the GitHub release with tag `vX.Y.Z` straight after the version
change reaches `main`; never put a pre-release version in `Version:` on `main`.
Releasing needs the owner's say.

## Git

GitHub (`origin`) is the primary remote. `gitea` is a backup only.
