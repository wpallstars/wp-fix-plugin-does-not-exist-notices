<?php
/**
 * Explains the "Plugin file does not exist" message and cleans up after
 * deleted plugins.
 *
 * When a plugin folder is deleted without using the Plugins screen (by FTP,
 * a host's file manager or a migration), WordPress keeps pointing at it:
 *
 * - `active_plugins` (and `active_sitewide_plugins` on multisite): the next
 *   time the Plugins screen opens, WordPress switches the plugin off and shows
 *   "The plugin … has been deactivated due to an error: Plugin file does not
 *   exist." This plugin leaves that to WordPress and explains the message
 *   below it, because the wording alarms people who did nothing wrong;
 * - `recently_activated` and `uninstall_plugins`: WordPress never clears
 *   these, so this plugin removes entries whose plugin file is gone.
 *
 * Everything runs on the Plugins screen only, for people who can manage
 * plugins. Nothing is stored and nothing is sent anywhere.
 *
 * @package WPALLSTARS\FixPluginDoesNotExistNotices
 */

namespace WPALLSTARS\FixPluginDoesNotExistNotices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The whole plugin.
 */
final class Plugin {

	/**
	 * Active plugins whose file is missing, found before WordPress switches
	 * them off later in the same request.
	 *
	 * @var string[]
	 */
	private static $missing = array();

	/**
	 * How many leftover entries were removed on this page load.
	 *
	 * @var int
	 */
	private static $cleaned = 0;

	/**
	 * Hooks into the Plugins screen.
	 *
	 * @return void
	 */
	public static function init() {
		// Runs before the plugin list is built and before WordPress switches
		// missing plugins off (`validate_active_plugins()` in plugins.php).
		add_action( 'load-plugins.php', array( __CLASS__, 'check' ) );
		// Prints below WordPress's own notices, above the plugin list.
		add_action( 'pre_current_active_plugins', array( __CLASS__, 'notice' ) );
	}

	/**
	 * Finds missing active plugins and removes leftover entries.
	 *
	 * @return void
	 */
	public static function check() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		self::$missing = self::missing_active_plugins();
		self::$cleaned = self::clean_up( 'recently_activated', is_network_admin() )
			+ self::clean_up( 'uninstall_plugins', false );

		// Version 2 stored an update source choice; nothing reads it now.
		if ( false !== get_option( 'fpden_update_source' ) ) {
			delete_option( 'fpden_update_source' );
		}
	}

	/**
	 * Lists active plugins whose main file is missing.
	 *
	 * Mirrors `validate_active_plugins()`, so it lists exactly the plugins
	 * WordPress is about to switch off on this screen.
	 *
	 * @return string[] Plugin files, relative to the plugins folder.
	 */
	private static function missing_active_plugins() {
		$plugins = get_option( 'active_plugins', array() );
		$plugins = is_array( $plugins ) ? $plugins : array();

		if ( is_multisite() && current_user_can( 'manage_network_plugins' ) ) {
			$network = (array) get_site_option( 'active_sitewide_plugins', array() );
			$plugins = array_merge( $plugins, array_keys( $network ) );
		}

		$missing = array_filter( $plugins, array( __CLASS__, 'is_missing' ) );

		return array_values( array_unique( $missing ) );
	}

	/**
	 * Removes entries for plugins whose file is gone from a list keyed by
	 * plugin file.
	 *
	 * @param string $option  `recently_activated` or `uninstall_plugins`.
	 * @param bool   $network Whether the list is a network option.
	 * @return int Number of entries removed.
	 */
	private static function clean_up( $option, $network ) {
		$list = $network ? get_site_option( $option ) : get_option( $option );
		if ( ! is_array( $list ) || empty( $list ) ) {
			return 0;
		}

		$missing = array_filter( array_keys( $list ), array( __CLASS__, 'is_missing' ) );
		if ( empty( $missing ) ) {
			return 0;
		}

		$update = $network ? 'update_site_option' : 'update_option';
		$update( $option, array_diff_key( $list, array_flip( $missing ) ) );

		return count( $missing );
	}

	/**
	 * Whether an entry names a plugin file, inside the plugins folder, that
	 * no longer exists. Public because it is used as an array_filter() callback.
	 *
	 * @param mixed $plugin Plugin file, relative to the plugins folder.
	 * @return bool
	 */
	public static function is_missing( $plugin ) {
		return is_string( $plugin ) && 0 === validate_file( $plugin ) && ! is_file( WP_PLUGIN_DIR . '/' . $plugin );
	}

	/**
	 * Explains what happened, below WordPress's own message.
	 *
	 * @return void
	 */
	public static function notice() {
		if ( empty( self::$missing ) && 0 === self::$cleaned ) {
			return;
		}

		echo '<div class="notice notice-info inline">';

		if ( ! empty( self::$missing ) ) {
			$files = implode(
				', ',
				array_map(
					static function ( $plugin ) {
						return '<code>' . esc_html( $plugin ) . '</code>';
					},
					self::$missing
				)
			);

			echo '<p><strong>' . esc_html__( 'About the “Plugin file does not exist” message', 'wp-fix-plugin-does-not-exist-notices' ) . '</strong></p>';
			echo '<p>' . wp_kses(
				sprintf(
					/* translators: %s: list of plugin files. */
					esc_html( _n( 'WordPress could not find %s, so it has switched it off. Nothing else has changed, and your other plugins keep working.', 'WordPress could not find %s, so it has switched them off. Nothing else has changed, and your other plugins keep working.', count( self::$missing ), 'wp-fix-plugin-does-not-exist-notices' ) ),
					$files
				),
				array( 'code' => array() )
			) . '</p>';
			$count = count( self::$missing );
			echo '<p>' . esc_html( _n( 'If it was deleted on purpose, there is nothing more to do, and the message will not appear again. If not, install it again and activate it.', 'If they were deleted on purpose, there is nothing more to do, and the message will not appear again. If not, install them again and activate them.', $count, 'wp-fix-plugin-does-not-exist-notices' ) ) . '</p>';
			echo '<p>' . esc_html__( 'If the message comes back, something on the server keeps switching the plugin on, such as a deployment tool or a must-use plugin.', 'wp-fix-plugin-does-not-exist-notices' ) . '</p>';
		}

		if ( self::$cleaned > 0 ) {
			echo '<p>' . esc_html(
				sprintf(
					/* translators: %d: number of entries. */
					_n( 'Removed %d leftover entry for a deleted plugin.', 'Removed %d leftover entries for deleted plugins.', self::$cleaned, 'wp-fix-plugin-does-not-exist-notices' ),
					self::$cleaned
				)
			) . '</p>';
		}

		echo '</div>';
	}
}
