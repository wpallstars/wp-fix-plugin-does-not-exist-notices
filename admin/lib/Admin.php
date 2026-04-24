<?php
/**
 * Admin Functionality
 *
 * @package WPALLSTARS\FixPluginDoesNotExistNotices
 */

namespace WPALLSTARS\FixPluginDoesNotExistNotices\Admin;

/**
 * Admin Class
 *
 * Handles admin-specific functionality.
 */
class Admin {

    /**
     * Core instance
     *
     * @var \WPALLSTARS\FixPluginDoesNotExistNotices\Core
     */
    private $core;

    /**
     * Constructor
     *
     * @param \WPALLSTARS\FixPluginDoesNotExistNotices\Core $core Core instance.
     */
    public function __construct($core) {
        $this->core = $core;

        // Enqueue admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Enqueue scripts and styles needed for the admin area.
     *
     * @param string $hook_suffix The current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets($hook_suffix) {
        // Only load on the plugins page
        if ('plugins.php' !== $hook_suffix) {
            return;
        }

        // Version fix script is no longer needed after refactoring
        // Commented out for testing
        /*
        wp_enqueue_script(
            'fpden-version-fix',
            FPDEN_PLUGIN_URL . 'admin/js/version-fix.js',
            array('jquery', 'thickbox'),
            FPDEN_VERSION,
            true // Load in footer
        );
        */

        // Get invalid plugins to decide if other assets are needed
        $invalid_plugins = $this->core->get_invalid_plugins();
        if (empty($invalid_plugins)) {
            return; // No missing plugins, no need for the special notice JS/CSS
        }

        wp_enqueue_style(
            'fpden-admin-styles',
            FPDEN_PLUGIN_URL . 'admin/css/admin-styles.css',
            array(),
            FPDEN_VERSION
        );

        wp_enqueue_script(
            'fpden-admin-scripts',
            FPDEN_PLUGIN_URL . 'admin/js/admin-scripts.js',
            array('jquery'), // Add dependencies if needed, e.g., jQuery
            FPDEN_VERSION,
            true // Load in footer
        );

        // Add translation strings for JavaScript
        wp_localize_script(
            'fpden-admin-scripts',
            'fpdenData',
            array(
                'i18n' => array(
                    'clickToScroll' => esc_html__('Click here to scroll to missing plugins', 'wp-fix-plugin-does-not-exist-notices'),
                    'pluginMissing' => esc_html__('File Missing', 'wp-fix-plugin-does-not-exist-notices'),
                    'removeNotice' => esc_html__('Remove Notice', 'wp-fix-plugin-does-not-exist-notices'),
                ),
                'version' => FPDEN_VERSION, // Add version for the plugin details fix script
            )
        );
    }
}
