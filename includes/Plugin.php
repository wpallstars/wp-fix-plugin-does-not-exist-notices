<?php
/**
 * Main Plugin Class
 *
 * @package WPALLSTARS\FixPluginDoesNotExistNotices
 */

namespace WPALLSTARS\FixPluginDoesNotExistNotices;

/**
 * Main Plugin Class
 *
 * Initializes all components of the plugin.
 */
class Plugin {

    /**
     * Plugin version
     *
     * @var string
     */
    private $version;

    /**
     * Plugin file path
     *
     * @var string
     */
    private $plugin_file;

    /**
     * Plugin directory path
     *
     * @var string
     */
    private $plugin_dir;

    /**
     * Plugin directory URL
     *
     * @var string
     */
    private $plugin_url;

    /**
     * Core functionality instance
     *
     * @var Core
     */
    private $core;

    /**
     * Admin functionality instance
     *
     * @var Admin\Admin
     */
    private $admin;

    /**
     * Updater instance
     *
     * @var Updater
     */
    private $updater;

    /**
     * Constructor
     *
     * @param string $plugin_file Main plugin file path.
     * @param string $version Plugin version.
     */
    public function __construct($plugin_file, $version) {
        $this->plugin_file = $plugin_file;
        $this->version = $version;
        $this->plugin_dir = plugin_dir_path($plugin_file);
        $this->plugin_url = plugin_dir_url($plugin_file);

        $this->define_constants();
        $this->load_dependencies();
        $this->init_components();
    }

    /**
     * Define plugin constants
     *
     * @return void
     */
    private function define_constants() {
        if (!defined('FPDEN_VERSION')) {
            define('FPDEN_VERSION', $this->version);
        }
        if (!defined('FPDEN_PLUGIN_DIR')) {
            define('FPDEN_PLUGIN_DIR', $this->plugin_dir);
        }
        if (!defined('FPDEN_PLUGIN_URL')) {
            define('FPDEN_PLUGIN_URL', $this->plugin_url);
        }
    }

    /**
     * Load dependencies
     *
     * @return void
     */
    private function load_dependencies() {
        // Load composer autoloader if it exists
        $autoloader = $this->plugin_dir . 'vendor/autoload.php';
        if (file_exists($autoloader)) {
            require_once $autoloader;
        }

        // Load required files
        require_once $this->plugin_dir . 'includes/Core.php';
        require_once $this->plugin_dir . 'admin/lib/admin.php';
        require_once $this->plugin_dir . 'admin/lib/modal.php';
    }

    /**
     * Initialize plugin components
     *
     * @return void
     */
    private function init_components() {
        // Initialize core functionality
        $this->core = new Core();

        // Initialize admin functionality
        $this->admin = new Admin\Admin($this->core);

        // Initialize Git Updater fixes
        $this->init_git_updater_fixes();

        // Initialize the updater if the class exists
        if (class_exists('\WPALLSTARS\FixPluginDoesNotExistNotices\Updater')) {
            $this->updater = new Updater($this->plugin_file);
        }

        // Initialize the modal for update source selection
        new Admin\Modal();
    }

    /**
     * Initialize Git Updater fixes
     *
     * This function previously added filters to fix Git Updater's handling of 'main' vs 'master' branches.
     * These fixes are no longer needed with proper plugin headers.
     * See: https://git-updater.com/knowledge-base/required-headers/
     *
     * @return void
     */
    private function init_git_updater_fixes() {
        // No fixes needed - we're using the proper plugin headers
        // Git Updater reads version information from the readme.txt file in the main branch
    }

    // Git Updater override methods have been removed as they're no longer needed
    // We now use the proper plugin headers for Git Updater integration
}
