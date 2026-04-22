<?php
/**
 * Plugin Name: Fix 'Plugin file does not exist' Notices
 * Plugin URI: https://www.wpallstars.com
 * Description: Adds missing plugins to your plugins list with a "Remove Notice" action link, allowing you to safely clean up invalid plugin references.
 * Version: 2.4.0
 * Author: Marcus Quinn & The WPALLSTARS Team
 * Author URI: https://www.wpallstars.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-fix-plugin-does-not-exist-notices
 * Domain Path: /languages
 * GitHub Plugin URI: wpallstars/wp-fix-plugin-does-not-exist-notices
 * GitHub Branch: main
 * Primary Branch: main
 * Release Branch: main
 * Release Asset: true
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Update URI: https://github.com/wpallstars/wp-fix-plugin-does-not-exist-notices
 *
 * Gitea Plugin URI: https://gitea.wpallstars.com/wpallstars/wp-fix-plugin-does-not-exist-notices
 * Gitea Branch: main
 * Gitea Languages: languages
 *
 * @package WPALLSTARS\FixPluginDoesNotExistNotices
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Load the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/Plugin.php';

// Initialize the plugin
// This is a test change for our new workflow
new WPALLSTARS\FixPluginDoesNotExistNotices\Plugin(__FILE__, '2.4.0');
