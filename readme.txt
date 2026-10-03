=== Fix 'Plugin file does not exist' Notices ===
Contributors: marcusquinn, wpallstars
Tags: plugin file does not exist, missing plugins, deactivated due to an error, cleanup, plugins
Requires at least: 6.2
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Explains the "Plugin file does not exist" message on the Plugins screen and cleans up what deleted plugins leave behind.

== Description ==

"The plugin folder-name/file-name.php has been deactivated due to an error: Plugin file does not exist."

WordPress shows this once, on the Plugins screen, when a plugin's folder was deleted without using that screen: by FTP, a host's file manager, a migration or a security tool. WordPress has already switched the plugin off; your content, settings and other plugins are untouched.

This small plugin:

* explains the message right below it, in plain words: which plugins WordPress could not find, that they are now off, and what to do next;
* removes the entries deleted plugins leave behind in the "Recently active" list and the list of uninstall routines, which WordPress never clears.

It runs only on the Plugins screen, for people who can manage plugins. It has no settings, stores nothing and contacts no outside services. It works on single sites and multisite networks.

Want more? SEO Pro Stack, from the same maker, does this too, along with the jobs of 40+ other single-purpose plugins: https://github.com/wpallstars/seoprostack

== Frequently Asked Questions ==

= Is my site broken? =

No. WordPress has switched off the plugin it could not find. Nothing else has changed.

= I did not mean to delete the plugin. =

Install it again and activate it. Its settings are usually still in the database.

= The message keeps coming back. =

Something keeps switching the plugin back on: a deployment tool that restores the database, a must-use plugin, or a persistent object cache holding old options (flush it).

= Do I need to keep this plugin active? =

No. Once the message is gone, you can deactivate and delete it. Keep it if you would like the explanation to be there next time.

== Changelog ==

= 3.0.0 =
* Rewritten as one small file that runs only on the Plugins screen.
* Explains the message below WordPress's own and lists the plugins it switched off.
* Removes leftover "Recently active" and uninstall entries for deleted plugins.
* Removed the "Remove Notice" link: WordPress switches missing plugins off on the same page load, so the link always reported a failure.
* Removed the update source chooser and the code that cleared every plugin's update cache and the whole object cache on each visit to the Plugins screen.
* Removed the made-up ratings and install counts from the plugin details popup.
* Requires WordPress 6.2 and PHP 7.4.

Older versions: see CHANGELOG.md on GitHub.
