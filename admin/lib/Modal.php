<?php
/**
 * Modal Functionality
 *
 * @package WPALLSTARS\FixPluginDoesNotExistNotices
 */

namespace WPALLSTARS\FixPluginDoesNotExistNotices\Admin;

/**
 * Modal Class
 *
 * Handles the update source selector modal.
 */
class Modal {

    /**
     * Constructor
     */
    public function __construct() {
        // Add filter for plugin action links to add our update source selector
        add_filter('plugin_action_links_' . plugin_basename(FPDEN_PLUGIN_DIR . 'wp-fix-plugin-does-not-exist-notices.php'), array($this, 'add_update_source_link'));

        // Add AJAX handler for saving update source
        add_action('wp_ajax_fpden_save_update_source', array($this, 'save_update_source'));

        // Add the update source modal to admin footer
        add_action('admin_footer', array($this, 'add_update_source_modal'));
    }

    /**
     * Add the "Update Source" link to plugin action links
     *
     * @param array $links Array of plugin action links
     * @return array Modified array of plugin action links
     */
    public function add_update_source_link($links) {
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
        $update_source_link = '<a href="#" class="fpden-update-source-toggle">Update Source <span class="' . $badge_class . '">' . $badge_text . '</span></a>';
        $links[] = $update_source_link;

        return $links;
    }

    /**
     * Add the update source modal to the admin footer
     */
    public function add_update_source_modal() {
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
            FPDEN_PLUGIN_URL . 'admin/css/update-source-selector.css',
            array(),
            FPDEN_VERSION
        );

        wp_enqueue_script(
            'fpden-update-source-selector',
            FPDEN_PLUGIN_URL . 'admin/js/update-source-selector.js',
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
                    <a href="https://wordpress.org/plugins/wp-fix-plugin-does-not-exist-notices/" target="_blank" rel="noopener noreferrer">WordPress.org</a>
                    <span class="fpden-source-description">Updates from the official WordPress.org plugin repository. Has a version update delay, to allow for the WP.org policy review and approval process. Best for unmonitored auto-updates.</span>
                </label>

                <label>
                    <input type="radio" name="update_source" value="github" <?php checked($current_source, 'github'); ?>>
                    <a href="https://github.com/wpallstars/wp-fix-plugin-does-not-exist-notices" target="_blank" rel="noopener noreferrer">GitHub</a>
                    <span class="fpden-source-description">Update directly from the GitHub repo main branch for the latest stable release. Git Updater plugin must be installed & active. Best for monitored updates, where the latest features and fixes are required as soon as they are merged into the main branch.</span>
                </label>

                <label>
                    <input type="radio" name="update_source" value="gitea" <?php checked($current_source, 'gitea'); ?>>
                    <a href="https://gitea.wpallstars.com/wpallstars/wp-fix-plugin-does-not-exist-notices" target="_blank" rel="noopener noreferrer">Gitea</a>
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
    public function save_update_source() {
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
}
