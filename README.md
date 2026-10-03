# Fix "Plugin file does not exist" in WordPress

> The plugin `folder-name/file-name.php` has been deactivated due to an error: Plugin file does not exist.

Seeing this message on the WordPress Plugins screen? Your site is fine. This page explains what it means, how to fix it in a minute, and a small free plugin that explains it to whoever sees it next.

## What the message means

WordPress keeps a list of active plugins in the database. When a plugin's folder is deleted without using the Plugins screen, the list still points at it. That happens when a plugin is deleted by FTP or a host's file manager, during a migration or restore, or when a host or security tool removes a plugin.

The next time someone opens the Plugins screen, WordPress notices the file is missing, **switches the plugin off** and shows the message once. Nothing else is changed: your content, settings and other plugins are untouched.

## How to fix it

### If you deleted the plugin on purpose

There is nothing more to do. WordPress has already switched it off, and the message will not appear again after you reload the Plugins screen.

### If you did not mean to delete it

Install the plugin again (Plugins → Add New, or upload it), then activate it. Its settings are usually still in the database, so it picks up where it left off.

### If the message keeps coming back

Something keeps switching the plugin back on. Common causes:

- a deployment tool or a Git checkout that restores the database or `active_plugins` option;
- a must-use plugin (in `wp-content/mu-plugins`) that activates plugins;
- a persistent object cache (Redis, Memcached) holding an old copy of the options: flush it from your host's panel or with `wp cache flush`.

### With WP-CLI

```bash
wp option get active_plugins   # the list WordPress keeps, missing plugins included
wp eval 'deactivate_plugins( "folder-name/file-name.php", true );'
```

`wp plugin deactivate` does not work here: it only knows plugins whose files exist.

## The plugin

**Fix 'Plugin file does not exist' Notices** is a small plugin that helps when this happens:

- It explains the message on the Plugins screen, right below it, in plain words: which plugins WordPress could not find, that it has switched them off, and what to do next.
- It removes the entries deleted plugins leave behind in the "Recently active" list and the list of uninstall routines, which WordPress never clears.
- It runs only on the Plugins screen, for people who can manage plugins. It has no settings, stores nothing and contacts no outside services.

It works on single sites and multisite networks, with WordPress 6.2 or newer and PHP 7.4 or newer.

### Install

1. Download `wp-fix-plugin-does-not-exist-notices-X.Y.Z.zip` from the [latest release](https://github.com/wpallstars/wp-fix-plugin-does-not-exist-notices/releases/latest).
2. In WordPress, go to Plugins → Add New → Upload Plugin, choose the zip and activate it.

Updates arrive through [Git Updater](https://github.com/afragen/git-updater), or SEO Pro Stack's "Updates from GitHub", which both read the plugin's `GitHub Plugin URI` header.

## Want more?

This plugin does one job. [SEO Pro Stack](https://github.com/wpallstars/seoprostack), from the same maker, does this job too (Plugins → Clean up deleted plugins) along with the jobs of 40+ other single-purpose plugins, each a switch you turn on.

## Developers

- `wp-fix-plugin-does-not-exist-notices.php` loads `includes/Plugin.php` in the admin only.
- `includes/Plugin.php` is the whole plugin. On `load-plugins.php` it lists active plugins whose file is missing (the same check as core's `validate_active_plugins()`, which runs later in the same request) and removes leftover entries from `recently_activated` and `uninstall_plugins`. On `pre_current_active_plugins` it prints the explanation.
- Checks: `composer install`, then `composer lint` (PHPCS with the WordPress standard and PHP 7.4 compatibility, and PHPStan).
- Release zip: `./build.sh` builds `wp-fix-plugin-does-not-exist-notices-X.Y.Z.zip` with the files not listed in `.distignore`.

Contributions are welcome: open an issue first (see `CONTRIBUTING.md`).

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## License

GPL-2.0-or-later. By Marcus Quinn, [WP All Stars](https://www.wpallstars.com/).
