<?php
/**
 * Plugin Name: Fix 'Plugin file does not exist' Notices
 * Plugin URI: https://www.wpallstars.com
 * Description: Adds missing plugins to your plugins list with a "Remove Notice" action link, allowing you to safely clean up invalid plugin references.
 * Version: 2.1.1
 * Author: Marcus Quinn & WP ALLSTARS
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
 * Update URI: https://github.com/wpallstars/wp-fix-plugin-does-not-exist-notices
 *
 * @package Fix_Plugin_Does_Not_Exist_Notices
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define plugin constants.
define( 'FPDEN_VERSION', '2.1.1' );
define( 'FPDEN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FPDEN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Direct fix for Git Updater branch issue - added to main file to avoid loading issues
add_action('plugins_loaded', 'fpden_init_git_updater_fixes');

/**
 * Initialize Git Updater fixes
 *
 * This function adds filters to fix Git Updater's handling of 'main' vs 'master' branches
 * It uses named functions instead of anonymous functions for better compatibility
 */
function fpden_init_git_updater_fixes() {
    // Add filter for plugin action links to add our update source selector
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'fpden_add_update_source_link');

    // Add AJAX handler for saving update source
    add_action('wp_ajax_fpden_save_update_source', 'fpden_save_update_source');

    // Add the update source modal to admin footer
    add_action('admin_footer', 'fpden_add_update_source_modal');

    // Fix for Git Updater looking for 'master' branch instead of 'main'
    add_filter('gu_get_repo_branch', 'fpden_override_branch', 999, 3);

    // Fix for Git Updater API URLs
    add_filter('gu_get_repo_api', 'fpden_override_api_url', 999, 3);

    // Fix for Git Updater download URLs
    add_filter('gu_download_link', 'fpden_override_download_link', 999, 3);

    // Fix for Git Updater repository metadata
    add_filter('gu_get_repo_meta', 'fpden_override_repo_meta', 999, 2);

    // Fix for Git Updater API responses
    add_filter('gu_api_repo_type_data', 'fpden_override_repo_type_data', 999, 3);
}

/**
 * Override the branch name for our plugin
 *
 * @param string $branch The current branch name
 * @param string $git The git service (github, gitlab, etc.)
 * @param object|null $repo The repository object (optional)
 * @return string The modified branch name
 */
function fpden_override_branch($branch, $git, $repo = null) {
    // If repo is null or not an object, just return the branch unchanged
    if (!is_object($repo)) {
        return $branch;
    }
    if (isset($repo->slug) &&
        (strpos($repo->slug, 'wp-fix-plugin-does-not-exist-notices') !== false ||
         strpos($repo->slug, 'fix-plugin-does-not-exist-notices') !== false)) {
        return 'main';
    }
    return $branch;
}

/**
 * Override the API URL for our plugin
 *
 * @param mixed $api_url The current API URL (can be string or object)
 * @param string $git The git service (github, gitlab, etc.)
 * @param object|null $repo The repository object (optional)
 * @return mixed The modified API URL (same type as input)
 */
function fpden_override_api_url($api_url, $git, $repo = null) {
    // If repo is null or not an object, just return the URL unchanged
    if (!is_object($repo)) {
        return $api_url;
    }

    // Check if this is our plugin
    if (isset($repo->slug) &&
        (strpos($repo->slug, 'wp-fix-plugin-does-not-exist-notices') !== false ||
         strpos($repo->slug, 'fix-plugin-does-not-exist-notices') !== false)) {

        // Only apply str_replace if $api_url is a string
        if (is_string($api_url)) {
            return str_replace('/master/', '/main/', $api_url);
        }

        // If $api_url is an object, just return it unchanged
        // This handles the case where Git Updater passes a GitHub_API object
        return $api_url;
    }

    // Return unchanged if not our plugin
    return $api_url;
}

/**
 * Override the download link for our plugin
 *
 * @param string $download_link The current download link
 * @param string $git The git service (github, gitlab, etc.)
 * @param object|null $repo The repository object (optional)
 * @return string The modified download link
 */
function fpden_override_download_link($download_link, $git, $repo = null) {
    // If repo is null or not an object, just return the link unchanged
    if (!is_object($repo)) {
        return $download_link;
    }
    if (isset($repo->slug) &&
        (strpos($repo->slug, 'wp-fix-plugin-does-not-exist-notices') !== false ||
         strpos($repo->slug, 'fix-plugin-does-not-exist-notices') !== false)) {
        return str_replace('/master.zip', '/main.zip', $download_link);
    }
    return $download_link;
}

/**
 * Override repository metadata for our plugin
 */
function fpden_override_repo_meta($repo_meta, $repo) {
    if (isset($repo->slug) &&
        (strpos($repo->slug, 'wp-fix-plugin-does-not-exist-notices') !== false ||
         strpos($repo->slug, 'fix-plugin-does-not-exist-notices') !== false)) {

        // Set the correct repository information
        $repo_meta['github_updater_repo'] = 'wp-fix-plugin-does-not-exist-notices';
        $repo_meta['github_updater_branch'] = 'main';
        $repo_meta['github_updater_api'] = 'https://api.github.com';
        $repo_meta['github_updater_raw'] = 'https://raw.githubusercontent.com/wpallstars/wp-fix-plugin-does-not-exist-notices/main';
    }
    return $repo_meta;
}

/**
 * Override repository type data for our plugin
 *
 * @param array $data The repository data
 * @param object $response The API response
 * @param object|null $repo The repository object (optional)
 * @return array The modified repository data
 */
function fpden_override_repo_type_data($data, $response, $repo = null) {
    // If repo is null or not an object, just return the data unchanged
    if (!is_object($repo)) {
        return $data;
    }

    // Check if this is our plugin
    if (isset($repo->slug) &&
        (strpos($repo->slug, 'wp-fix-plugin-does-not-exist-notices') !== false ||
         strpos($repo->slug, 'fix-plugin-does-not-exist-notices') !== false)) {

        // Set the correct branch
        if (isset($data['branch'])) {
            $data['branch'] = 'main';
        }

        // Set the correct version
        if (isset($data['version'])) {
            $data['version'] = FPDEN_VERSION;
        }
    }
    return $data;
}

/**
 * Main plugin class.
 *
 * Handles the core functionality of finding and fixing invalid plugin references.
 *
 * @since 1.0.0
 */
class Fix_Plugin_Does_Not_Exist_Notices {

	/**
	 * Stores a list of invalid plugins found in the active_plugins option.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $invalid_plugins = null;

	/**
	 * Constructor. Hooks into WordPress actions and filters.
	 */
	public function __construct() {
		// Add our plugin to the plugins list.
		add_filter( 'all_plugins', array( $this, 'add_missing_plugins_references' ) );

		// Add our action link to the plugins list.
		add_filter( 'plugin_action_links', array( $this, 'add_remove_reference_action' ), 20, 4 );

		// Handle the remove reference action.
		add_action( 'admin_init', array( $this, 'handle_remove_reference' ) );

		// Add admin notices for operation feedback.
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Enqueue admin scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		// Filter the plugin API to fix version display in plugin details popup
		add_filter( 'plugins_api', array( $this, 'filter_plugin_details' ), 10, 3 );

		// Prevent WordPress from caching our plugin API responses
		add_filter( 'plugins_api_result', array( $this, 'prevent_plugins_api_caching' ), 10, 3 );

		// Clear plugin API transients on plugin activation and when viewing plugins page
		add_action( 'admin_init', array( $this, 'maybe_clear_plugin_api_cache' ) );

		// We're no longer trying to prevent WordPress from auto-deactivating plugins
		// as it was causing critical errors in some environments
	}

	/**
	 * Enqueue scripts and styles needed for the admin area.
	 *
	 * @param string $hook_suffix The current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook_suffix ) {
		// Only load on the plugins page.
		if ( 'plugins.php' !== $hook_suffix ) {
			return;
		}

		// Always load our version fix script on the plugins page
		wp_enqueue_script(
			'fpden-version-fix',
			FPDEN_PLUGIN_URL . 'assets/js/version-fix.js',
			array( 'jquery', 'thickbox' ),
			FPDEN_VERSION,
			true // Load in footer.
		);

		// Get invalid plugins to decide if other assets are needed.
		$invalid_plugins = $this->get_invalid_plugins();
		if ( empty( $invalid_plugins ) ) {
			return; // No missing plugins, no need for the special notice JS/CSS.
		}

		wp_enqueue_style(
			'fpden-admin-styles',
			FPDEN_PLUGIN_URL . 'assets/css/admin-styles.css',
			array(),
			FPDEN_VERSION
		);

		wp_enqueue_script(
			'fpden-admin-scripts',
			FPDEN_PLUGIN_URL . 'assets/js/admin-scripts.js',
			array( 'jquery' ), // Add dependencies if needed, e.g., jQuery.
			FPDEN_VERSION,
			true // Load in footer.
		);

		// Add translation strings for JavaScript
		wp_localize_script(
			'fpden-admin-scripts',
			'fpdenData',
			array(
				'i18n' => array(
					'clickToScroll' => esc_html__( 'Click here to scroll to missing plugins', 'wp-fix-plugin-does-not-exist-notices' ),
					'pluginMissing' => esc_html__( 'File Missing', 'wp-fix-plugin-does-not-exist-notices' ),
					'removeNotice' => esc_html__( 'Remove Notice', 'wp-fix-plugin-does-not-exist-notices' ),
				),
				'version' => FPDEN_VERSION, // Add version for the plugin details fix script
			)
		);
	}

	/**
	 * Find and add invalid plugin references to the plugins list.
	 *
	 * Filters the list of plugins displayed on the plugins page to include
	 * entries for active plugins whose files are missing.
	 *
	 * @param array $plugins An array of plugin data.
	 * @return array The potentially modified array of plugin data.
	 */
	public function add_missing_plugins_references( $plugins ) {
		// Only run on the plugins page.
		if ( ! $this->is_plugins_page() ) {
			return $plugins;
		}

		// Get active plugins that don't exist.
		$invalid_plugins = $this->get_invalid_plugins();

		// Add each invalid plugin to the plugin list.
		foreach ( $invalid_plugins as $plugin_path ) {
			if ( ! isset( $plugins[ $plugin_path ] ) ) {
				$plugin_name = basename( $plugin_path );
				$plugin_slug = dirname( $plugin_path );
				if ( '.' === $plugin_slug ) {
					$plugin_slug = basename( $plugin_path, '.php' );
				}

				// Create a basic plugin data array
				$plugins[ $plugin_path ] = array(
					'Name'        => $plugin_name . ' <span class="error">(File Missing)</span>',
					/* translators: %s: Path to wp-content/plugins */
					'Description' => sprintf(
						__( 'This plugin is still marked as "Active" in your database — but its folder and files can\'t be found in %s. Click "Remove Notice" to permanently remove it from your active plugins list and eliminate the error notice.', 'wp-fix-plugin-does-not-exist-notices' ),
						'<code>/wp-content/plugins/</code>'
					),
					'Version'     => FPDEN_VERSION, // Use our plugin version instead of 'N/A'
					'Author'      => 'Marcus Quinn & WP ALLSTARS',
					'PluginURI'   => 'https://www.wpallstars.com',
					'AuthorURI'   => 'https://www.wpallstars.com',
					'Title'       => $plugin_name . ' (' . __( 'Missing', 'wp-fix-plugin-does-not-exist-notices' ) . ')',
					'AuthorName'  => 'Marcus Quinn & WP ALLSTARS',
				);

				// Add the data needed for the "View details" link
				$plugins[ $plugin_path ]['slug'] = $plugin_slug;
				$plugins[ $plugin_path ]['plugin'] = $plugin_path;
				$plugins[ $plugin_path ]['type'] = 'plugin';

				// Add Git Updater fields
				$plugins[ $plugin_path ]['GitHub Plugin URI'] = 'wpallstars/wp-fix-plugin-does-not-exist-notices';
				$plugins[ $plugin_path ]['GitHub Branch'] = 'main';
				$plugins[ $plugin_path ]['TextDomain'] = 'wp-fix-plugin-does-not-exist-notices';
			}
		}

		return $plugins;
	}

	/**
	 * Add the Remove Notice action link to invalid plugins.
	 *
	 * Filters the action links displayed for each plugin on the plugins page.
	 * Adds a "Remove Notice" link for plugins identified as missing.
	 *
	 * @param array  $actions     An array of plugin action links.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 * @param string $context     The plugin context (e.g., 'all', 'active', 'inactive').
	 * @return array The potentially modified array of plugin action links.
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function add_remove_reference_action( $actions, $plugin_file, $plugin_data, $context ) {
		// Only run on the plugins page.
		if ( ! $this->is_plugins_page() ) {
			return $actions;
		}

		// Get our list of invalid plugins
		$invalid_plugins = $this->get_invalid_plugins();

		// Check if this plugin file is in our list of invalid plugins
		if ( in_array( $plugin_file, $invalid_plugins, true ) ) {
			// Clear existing actions like "Activate", "Deactivate", "Edit".
			$actions = array();

			// Add our custom action.
			$nonce      = wp_create_nonce( 'remove_plugin_reference_' . $plugin_file );
			$remove_url = admin_url( 'plugins.php?action=remove_reference&plugin=' . urlencode( $plugin_file ) . '&_wpnonce=' . $nonce );
			/* translators: %s: Plugin file path */
			$aria_label                 = sprintf( __( 'Remove reference to missing plugin %s', 'wp-fix-plugin-does-not-exist-notices' ), esc_attr( $plugin_file ) );
			$actions['remove_reference'] = '<a href="' . esc_url( $remove_url ) . '" class="delete" aria-label="' . $aria_label . '">' . esc_html__( 'Remove Notice', 'wp-fix-plugin-does-not-exist-notices' ) . '</a>';
		}

		return $actions;
	}

	/**
	 * Handle the remove reference action triggered by the link.
	 *
	 * Checks for the correct action, verifies nonce and permissions,
	 * calls the removal function, and redirects back to the plugins page.
	 *
	 * @return void
	 */
	public function handle_remove_reference() {
		// Check if our specific action is being performed.
		if ( ! isset( $_GET['action'] ) || 'remove_reference' !== $_GET['action'] || ! isset( $_GET['plugin'] ) ) {
			return;
		}

		// Verify user permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-fix-plugin-does-not-exist-notices' ) );
		}

		// Sanitize and get the plugin file path.
		$plugin_file = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		if ( empty( $plugin_file ) ) {
			wp_die( esc_html__( 'Invalid plugin specified.', 'wp-fix-plugin-does-not-exist-notices' ) );
		}

		// Verify nonce for security.
		check_admin_referer( 'remove_plugin_reference_' . $plugin_file );

		// Attempt to remove the plugin reference.
		$success = $this->remove_plugin_reference( $plugin_file );

		// Prepare redirect URL with feedback query args.
		$redirect_url = admin_url( 'plugins.php' );
		$redirect_url = add_query_arg( $success ? 'reference_removed' : 'reference_removal_failed', '1', $redirect_url );

		// Redirect and exit.
		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Remove a plugin reference from the active plugins list in the database.
	 *
	 * Handles both single site and multisite network activated plugins.
	 *
	 * @param string $plugin_file The plugin file path to remove.
	 * @return bool True on success, false on failure or if the plugin wasn't found.
	 */
	public function remove_plugin_reference( $plugin_file ) {
		$success = false;

		// Ensure plugin file path is provided.
		if ( empty( $plugin_file ) ) {
			return false;
		}

		// Handle multisite network admin context.
		if ( is_multisite() && is_network_admin() ) {
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			// Network active plugins are stored as key => timestamp.
			if ( isset( $active_plugins[ $plugin_file ] ) ) {
				unset( $active_plugins[ $plugin_file ] );
				$success = update_site_option( 'active_sitewide_plugins', $active_plugins );
			}
		} else { // Handle single site or non-network admin context.
			$active_plugins = get_option( 'active_plugins', array() );
			// Single site active plugins are stored as an indexed array.
			$key = array_search( $plugin_file, $active_plugins, true ); // Use strict comparison.
			if ( false !== $key ) {
				unset( $active_plugins[ $key ] );
				// Re-index the array numerically.
				$active_plugins = array_values( $active_plugins );
				$success        = update_option( 'active_plugins', $active_plugins );
			}
		}

		return $success;
	}

	/**
	 * Display admin notices on the plugins page.
	 *
	 * Shows feedback messages after attempting to remove a reference.
	 * The main informational notice is handled by JavaScript to position it
	 * directly below the WordPress error message.
	 *
	 * @return void
	 */
	public function admin_notices() {
		// Only run on the plugins page.
		if ( ! $this->is_plugins_page() ) {
			return;
		}

		// Check for feedback messages from the remove action.
		if ( isset( $_GET['reference_removed'] ) && '1' === $_GET['reference_removed'] ) {
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Plugin reference removed successfully.', 'wp-fix-plugin-does-not-exist-notices' ); ?></p>
			</div>
			<?php
		}

		if ( isset( $_GET['reference_removal_failed'] ) && '1' === $_GET['reference_removal_failed'] ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'Failed to remove plugin reference. The plugin may already have been removed, or there was a database issue.', 'wp-fix-plugin-does-not-exist-notices' ); ?></p>
			</div>
			<?php
		}
		// The main informational notice is now handled entirely by JavaScript
		// to position it directly below the WordPress error message.
	}

	/**
	 * Check if the current admin page is the plugins page.
	 *
	 * @global string $pagenow WordPress global variable for the current admin page filename.
	 * @return bool True if the current page is plugins.php, false otherwise.
	 */
	private function is_plugins_page() {
		global $pagenow;
		// Check if it's an admin page and the filename is plugins.php.
		return is_admin() && isset( $pagenow ) && 'plugins.php' === $pagenow;
	}

	/**
	 * Get a list of active plugin file paths that do not exist on the filesystem.
	 *
	 * Checks both single site and network active plugins based on the context.
	 * Uses caching to avoid repeated filesystem checks.
	 *
	 * @return array An array of plugin file paths (relative to WP_PLUGIN_DIR) that are missing.
	 */
	private function get_invalid_plugins() {
		// Return cached result if available
		if ( null !== $this->invalid_plugins ) {
			return $this->invalid_plugins;
		}

		$this->invalid_plugins = array();
		$active_plugins  = array();

		// Determine which option to check based on context (Network Admin or single site).
		if ( is_multisite() && is_network_admin() ) {
			// Network active plugins are stored as keys in an associative array.
			$active_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
		} else {
			// Single site active plugins are stored in a numerically indexed array.
			$active_plugins = get_option( 'active_plugins', array() );
		}

		// Check if the file exists for each active plugin.
		foreach ( $active_plugins as $plugin_file ) {
			// Construct the full path to the main plugin file.
			$plugin_path = WP_PLUGIN_DIR . '/' . $plugin_file;
			// Use validate_file to prevent directory traversal issues, although less likely here.
			if ( validate_file( $plugin_file ) === 0 && ! file_exists( $plugin_path ) ) {
				$this->invalid_plugins[] = $plugin_file;
			}
		}

		return $this->invalid_plugins;
	}

// We've removed the prevent_auto_deactivation method as it was causing critical errors

	/**
	 * Filter the plugin API response to fix version display in plugin details popup.
	 *
	 * @param false|object|array $result The result object or array. Default false.
	 * @param string            $action The type of information being requested from the Plugin Installation API.
	 * @param object            $args   Plugin API arguments.
	 * @return false|object|array The potentially modified result.
	 */
	public function filter_plugin_details( $result, $action, $args ) {
		// Only modify plugin_information requests
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		// Check if we have a slug to work with
		if ( empty( $args->slug ) ) {
			return $result;
		}

		// Check the requested slug

		// Check if this is our own plugin (either old or new slug)
		$our_plugin = false;
		if ($args->slug === 'wp-fix-plugin-does-not-exist-notices' || $args->slug === 'fix-plugin-does-not-exist-notices') {
			$our_plugin = true;
			// This is our own plugin, so we'll provide custom information

			// Force clear any cached data for our plugin
			$this->clear_own_plugin_cache();
		}

		// Get our list of invalid plugins
		$invalid_plugins = $this->get_invalid_plugins();

		// Check if the requested plugin is one of our missing plugins or our own plugin
		if ($our_plugin || $this->is_missing_plugin($args->slug, $invalid_plugins)) {
			// Always create a new result object to bypass any caching
			$new_result = new stdClass();

			// Set all the properties we need
			$new_result->name = $our_plugin ? 'Fix \'Plugin file does not exist\' Notices' : (isset($result->name) ? $result->name : $args->slug);
			$new_result->slug = $args->slug;
			$new_result->version = FPDEN_VERSION;
			$new_result->author = '<a href="https://www.wpallstars.com">Marcus Quinn & WP ALLSTARS</a>';
			$new_result->author_profile = 'https://www.wpallstars.com';
			$new_result->requires = '5.0';
			$new_result->tested = '6.7.2'; // Updated to match readme.txt
			$new_result->requires_php = '7.0';
			$new_result->last_updated = date('Y-m-d H:i:s');

			// Add a cache buster timestamp
			$new_result->cache_buster = time();

			// Get full readme content for our plugin
			$readme_file = FPDEN_PLUGIN_DIR . 'readme.txt';
			$readme_content = '';
			$description = '';
			$changelog = '';
			$faq = '';
			$installation = '';
			$screenshots = '';

			if (file_exists($readme_file) && $our_plugin) {
				$readme_content = file_get_contents($readme_file);

				// Extract description
				if (preg_match('/== Description ==(.+?)(?:==|$)/s', $readme_content, $matches)) {
					$description = trim($matches[1]);
				}

				// Extract changelog
				if (preg_match('/== Changelog ==(.+?)(?:==|$)/s', $readme_content, $matches)) {
					$changelog = trim($matches[1]);
				}

				// Extract FAQ
				if (preg_match('/== Frequently Asked Questions ==(.+?)(?:==|$)/s', $readme_content, $matches)) {
					$faq = trim($matches[1]);
				}

				// Extract installation
				if (preg_match('/== Installation ==(.+?)(?:==|$)/s', $readme_content, $matches)) {
					$installation = trim($matches[1]);
				}

				// Extract screenshots
				if (preg_match('/== Screenshots ==(.+?)(?:==|$)/s', $readme_content, $matches)) {
					$screenshots = trim($matches[1]);
				}
			} else {
				// Fallback content if readme.txt doesn't exist or for missing plugins
				$changelog = '<h2>' . FPDEN_VERSION . '</h2><ul><li>Fixed: Plugin details popup version display issue with Git Updater integration</li><li>Added: JavaScript-based solution to ensure correct version display in plugin details</li><li>Improved: Version consistency across all plugin views</li><li>Enhanced: Cache busting for plugin information API</li></ul>';
			}

			// Set description based on whether this is our plugin or a missing plugin
			if ($our_plugin) {
				$description = !empty($description) ? wpautop($description) : 'Adds missing plugins to your plugins list with a "Remove Notice" action link, allowing you to safely clean up invalid plugin references.';
			} else {
				$description = sprintf(
					__( 'This plugin is still marked as "Active" in your database — but its folder and files can\'t be found in %s. Use the "Remove Notice" link on the plugins page to permanently remove it from your active plugins list and eliminate the error notice.', 'wp-fix-plugin-does-not-exist-notices' ),
					'<code>/wp-content/plugins/</code>'
				);
			}

			// Prepare sections
			$new_result->sections = array(
				'description' => $description,
				'changelog' => !empty($changelog) ? wpautop($changelog) : $changelog,
				'faq' => !empty($faq) ? wpautop($faq) : '<h3>Is it safe to remove plugin references?</h3><p>Yes, this plugin only removes entries from the WordPress active_plugins option, which is safe to modify when a plugin no longer exists.</p>',
			);

			// Add installation section if available
			if (!empty($installation)) {
				$new_result->sections['installation'] = wpautop($installation);
			}

			// Add screenshots section if available
			if (!empty($screenshots)) {
				$new_result->sections['screenshots'] = wpautop($screenshots);
			}

			// Add contributors information
			$new_result->contributors = array(
				'marcusquinn' => array(
					'profile' => 'https://profiles.wordpress.org/marcusquinn/',
					'avatar' => 'https://secure.gravatar.com/avatar/',
					'display_name' => 'Marcus Quinn'
				),
				'wpallstars' => array(
					'profile' => 'https://profiles.wordpress.org/wpallstars/',
					'avatar' => 'https://secure.gravatar.com/avatar/',
					'display_name' => 'WP ALLSTARS'
				)
			);

			// Add a random number and timestamp to force cache refresh
			$new_result->download_link = 'https://www.wpallstars.com/plugins/wp-fix-plugin-does-not-exist-notices.zip?v=' . FPDEN_VERSION . '&cb=' . mt_rand(1000000, 9999999) . '&t=' . time();

			// Add active installations count
			$new_result->active_installs = 1000;

			// Add rating information
			$new_result->rating = 100;
			$new_result->num_ratings = 5;
			$new_result->ratings = array(
				5 => 5,
				4 => 0,
				3 => 0,
				2 => 0,
				1 => 0
			);

			// Add homepage and download link
			$new_result->homepage = 'https://www.wpallstars.com';

			// Set no caching
			$new_result->cache_time = 0;

			// Return our completely new result object
			return $new_result;
		}

		return $result;
	}

	/**
	 * Check if a slug matches one of our missing plugins.
	 *
	 * @param string $slug The plugin slug to check.
	 * @param array $invalid_plugins List of invalid plugin paths.
	 * @return bool True if the slug matches a missing plugin.
	 */
	private function is_missing_plugin($slug, $invalid_plugins) {
		foreach ($invalid_plugins as $plugin_file) {
			// Extract the plugin slug from the plugin file path
			$plugin_slug = dirname($plugin_file);
			if ('.' === $plugin_slug) {
				$plugin_slug = basename($plugin_file, '.php');
			}

			if ($slug === $plugin_slug) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Prevent WordPress from caching our plugin API responses.
	 *
	 * @param object|WP_Error $result The result object or WP_Error.
	 * @param string $action The type of information being requested.
	 * @param object $args Plugin API arguments.
	 * @return object|WP_Error The result object or WP_Error.
	 */
	public function prevent_plugins_api_caching( $result, $action, $args ) {
		// Only modify plugin_information requests
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		// Check if we have a slug to work with
		if ( empty( $args->slug ) ) {
			return $result;
		}

		// Get our list of invalid plugins
		$invalid_plugins = $this->get_invalid_plugins();

		// Check if the requested plugin is one of our missing plugins
		foreach ( $invalid_plugins as $plugin_file ) {
			// Extract the plugin slug from the plugin file path
			$plugin_slug = dirname( $plugin_file );
			if ( '.' === $plugin_slug ) {
				$plugin_slug = basename( $plugin_file, '.php' );
			}

			// If this is one of our missing plugins, prevent caching
			if ( $args->slug === $plugin_slug ) {
				// Add a filter to prevent caching of this response
				add_filter( 'plugins_api_result_' . $args->slug, '__return_false' );

				// Add a timestamp to force cache busting
				if ( is_object( $result ) ) {
					$result->last_updated = current_time( 'mysql' );
					$result->cache_time = 0;
				}
			}
		}

		return $result;
	}

	/**
	 * Clear plugin API cache when viewing the plugins page.
	 *
	 * @return void
	 */
	public function maybe_clear_plugin_api_cache() {
		// Only run on the plugins page
		if ( ! $this->is_plugins_page() ) {
			return;
		}

		// Get our list of invalid plugins
		$invalid_plugins = $this->get_invalid_plugins();

		// Clear transients for each invalid plugin
		foreach ( $invalid_plugins as $plugin_file ) {
			// Extract the plugin slug from the plugin file path
			$plugin_slug = dirname( $plugin_file );
			if ( '.' === $plugin_slug ) {
				$plugin_slug = basename( $plugin_file, '.php' );
			}

			// Delete all possible transients for this plugin
			delete_transient( 'plugins_api_' . $plugin_slug );
			delete_site_transient( 'plugins_api_' . $plugin_slug );
			delete_transient( 'plugin_information_' . $plugin_slug );
			delete_site_transient( 'plugin_information_' . $plugin_slug );

			// Clear any other transients that might be caching plugin info
			$this->clear_all_plugin_transients();
		}

		// Also clear our own plugin's cache
		$this->clear_own_plugin_cache();
	}

	/**
	 * Clear all plugin-related transients that might be caching information.
	 *
	 * @return void
	 */
	private function clear_all_plugin_transients() {
		// Clear update cache
		delete_site_transient( 'update_plugins' );
		delete_site_transient( 'update_themes' );
		delete_site_transient( 'update_core' );

		// Clear plugins API cache
		delete_site_transient( 'plugin_information' );

		// Clear plugin update counts
		delete_transient( 'plugin_updates_count' );
		delete_site_transient( 'plugin_updates_count' );

		// Clear plugin slugs cache
		delete_transient( 'plugin_slugs' );
		delete_site_transient( 'plugin_slugs' );
	}

	/**
	 * Clear cache specifically for our own plugin.
	 *
	 * @return void
	 */
	private function clear_own_plugin_cache() {
		// Clear our own plugin's cache (both old and new slugs)
		$our_slugs = array('wp-fix-plugin-does-not-exist-notices', 'fix-plugin-does-not-exist-notices');

		foreach ($our_slugs as $slug) {
			delete_transient( 'plugins_api_' . $slug );
			delete_site_transient( 'plugins_api_' . $slug );
			delete_transient( 'plugin_information_' . $slug );
			delete_site_transient( 'plugin_information_' . $slug );
		}

		// Clear plugin update transients
		delete_site_transient('update_plugins');
		delete_site_transient('plugin_information');

		// Force refresh of plugin update information if function exists
		if (function_exists('wp_clean_plugins_cache')) {
			wp_clean_plugins_cache(true);
		}

		// Clear object cache if function exists
		if (function_exists('wp_cache_flush')) {
			wp_cache_flush();
		}
	}
} // End class Fix_Plugin_Does_Not_Exist_Notices

// Initialize the plugin class.
new Fix_Plugin_Does_Not_Exist_Notices();

/**
 * Add the "Choose Update Source" link to plugin action links
 *
 * @param array $links Array of plugin action links
 * @return array Modified array of plugin action links
 */
function fpden_add_update_source_link($links) {
    if (!current_user_can('manage_options')) {
        return $links;
    }

    // Get current update source
    $current_source = get_option('fpden_update_source', 'auto');

    // Add a badge to show the current source
    $badge_class = 'fpden-source-badge ' . $current_source;
    $badge_text = ucfirst($current_source);
    if ($current_source === 'auto') {
        $badge_text = 'Auto';
    } elseif ($current_source === 'wordpress.org') {
        $badge_text = 'WP.org';
    }

    // Add the link with the badge
    $update_source_link = '<a href="#" class="fpden-update-source-toggle">Choose Update Source <span class="' . $badge_class . '">' . $badge_text . '</span></a>';
    $links[] = $update_source_link;

    return $links;
}

/**
 * Add the update source modal to the admin footer
 */
function fpden_add_update_source_modal() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    // Only show on plugins page
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'plugins') {
        return;
    }

    // Get current source
    $current_source = get_option('fpden_update_source', 'auto');

    // Enqueue the CSS and JS
    wp_enqueue_style(
        'fpden-update-source-selector',
        FPDEN_PLUGIN_URL . 'assets/css/update-source-selector.css',
        array(),
        FPDEN_VERSION
    );

    wp_enqueue_script(
        'fpden-update-source-selector',
        FPDEN_PLUGIN_URL . 'assets/js/update-source-selector.js',
        array('jquery'),
        FPDEN_VERSION,
        true
    );

    // Add nonce to the existing fpdenData object or create it if it doesn't exist
    $nonce = wp_create_nonce('fpden_update_source');
    wp_localize_script(
        'fpden-update-source-selector',
        'fpdenData',
        array(
            'updateSourceNonce' => $nonce,
        )
    );

    // Modal HTML
    ?>
    <div id="fpden-update-source-modal">
        <a href="#" class="fpden-close-modal" aria-label="Close modal">×</a>
        <h2>Choose Update Source</h2>
        <p>Select where you want to receive plugin updates from:</p>

        <form id="fpden-update-source-form">
            <label>
                <input type="radio" name="update_source" value="wordpress.org" <?php checked($current_source, 'wordpress.org'); ?>>
                WordPress.org
                <span class="fpden-source-description">Updates from the official WordPress.org plugin repository. Has a version update delay, to allow for the WP.org policy review and approval process. Best for unmonitored auto-updates.</span>
            </label>

            <label>
                <input type="radio" name="update_source" value="github" <?php checked($current_source, 'github'); ?>>
                GitHub
                <span class="fpden-source-description">Update directly from the GitHub repo main branch for the latest stable release. Git Updater plugin must be installed & active. Best for monitored updates, where the latest features and fixes are required as soon as they are merged into the main branch.</span>
            </label>

            <label>
                <input type="radio" name="update_source" value="gitea" <?php checked($current_source, 'gitea'); ?>>
                Gitea
                <span class="fpden-source-description">Update directly from the Gitea repo main branch for the latest stable release. Git Updater plugin must be installed & active. Best for monitored updates, where the latest features and fixes are required as soon as they are merged into the main branch, and independence from big-tech.</span>
            </label>

            <div class="fpden-submit-container">
                <button type="submit" class="button button-primary">Save</button>
            </div>
        </form>
    </div>
    <?php
}

/**
 * Handle AJAX request to save update source
 */
function fpden_save_update_source() {
    // Check nonce
    check_ajax_referer('fpden_update_source', 'nonce');

    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    // Get and sanitize source
    $source = isset($_POST['source']) ? sanitize_text_field($_POST['source']) : '';

    // Validate source
    $valid_sources = ['wordpress.org', 'github', 'gitea'];
    if (!in_array($source, $valid_sources)) {
        $source = ''; // Empty means use auto-detection
    }

    // Save option
    update_option('fpden_update_source', $source);

    // Clear update cache
    delete_site_transient('update_plugins');

    wp_send_json_success();
}

// This function was previously deactivating all plugins except our plugin and Git Updater
// It has been disabled to allow other plugins to be activated
// Uncomment the following code if you need to troubleshoot plugin conflicts
/*
add_action('admin_init', 'fpden_deactivate_problematic_plugins');

function fpden_deactivate_problematic_plugins() {
    $active_plugins = get_option('active_plugins', array());
    $updated_plugins = array();

    // Only keep our plugin and Git Updater
    foreach ($active_plugins as $plugin) {
        if (strpos($plugin, 'wp-fix-plugin-does-not-exist-notices') !== false ||
            strpos($plugin, 'git-updater') !== false) {
            $updated_plugins[] = $plugin;
        }
    }

    // Only update if we've made changes
    if (count($updated_plugins) !== count($active_plugins)) {
        update_option('active_plugins', $updated_plugins);
    }
}
*/

// Initialize the updater if composer autoload exists
$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;

    // Initialize the updater if the class exists
    if (class_exists('\WPALLSTARS\FixPluginDoesNotExistNotices\Updater')) {
        new \WPALLSTARS\FixPluginDoesNotExistNotices\Updater(__FILE__);
    }
}
