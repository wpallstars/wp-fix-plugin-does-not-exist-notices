<?php
/**
 * Plugin Name:       Fix 'Plugin file does not exist' Notices
 * Plugin URI:        https://github.com/wpallstars/wp-fix-plugin-does-not-exist-notices
 * Description:       Explains the "Plugin file does not exist" message on the Plugins screen and cleans up what deleted plugins leave behind.
 * Version:           3.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Marcus Quinn
 * Author URI:        https://www.wpallstars.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-fix-plugin-does-not-exist-notices
 * Domain Path:       /languages
 * GitHub Plugin URI: wpallstars/wp-fix-plugin-does-not-exist-notices
 * Primary Branch:    main
 * Release Asset:     true
 *
 * @package WPALLSTARS\FixPluginDoesNotExistNotices
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// It only works on the Plugins screen, so it loads nothing anywhere else.
if ( is_admin() ) {
	require_once __DIR__ . '/includes/Plugin.php';
	WPALLSTARS\FixPluginDoesNotExistNotices\Plugin::init();
}
