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
        $this->register_autoloader();
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
     * Register the PSR-4 autoloader
     *
     * Maps plugin namespaces to their corresponding directories so that
     * new class files are loaded automatically without manual require_once calls.
     *
     * Optimisations applied:
     * - Early exit when the class does not belong to this plugin's namespace,
     *   avoiding the loop entirely for every foreign class lookup.
     * - The namespace map is stored in a static variable so it is allocated
     *   only once across all invocations rather than on every class lookup.
     * - `require` is used instead of `require_once` because the autoloader is
     *   only called for classes that are not yet defined, making the redundancy
     *   check in `require_once` unnecessary.
     *
     * @return void
     */
    private function register_autoloader() {
        $plugin_dir = $this->plugin_dir;

        spl_autoload_register(function ($class) use ($plugin_dir) {
            // Skip immediately if the class is not in this plugin's namespace.
            $base_prefix = 'WPALLSTARS\\FixPluginDoesNotExistNotices\\';
            if (strncmp($base_prefix, $class, strlen($base_prefix)) !== 0) {
                return;
            }

            // Static map allocated once; ordered most-specific first so Admin\
            // resolves before the root namespace.
            static $namespace_map = null;
            if ($namespace_map === null) {
                $namespace_map = array(
                    'WPALLSTARS\\FixPluginDoesNotExistNotices\\Admin\\' => 'admin/lib/',
                    'WPALLSTARS\\FixPluginDoesNotExistNotices\\'        => 'includes/',
                );
            }

            foreach ($namespace_map as $prefix => $relative_path) {
                if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
                    continue;
                }

                $relative_class = substr($class, strlen($prefix));
                $file = $plugin_dir . $relative_path . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        });
    }

    /**
     * Load dependencies
     *
     * Loads the Composer autoloader when available (vendor installs), registering
     * it on the spl_autoload stack before the custom PSR-4 loader so that Composer
     * acts as the primary resolver. Project classes without a vendor install are
     * resolved by the autoloader registered in register_autoloader().
     *
     * @return void
     */
    private function load_dependencies() {
        // Load composer autoloader if it exists (vendor/ directory present).
        $autoloader = $this->plugin_dir . 'vendor/autoload.php';
        if (file_exists($autoloader)) {
            require_once $autoloader;
        }
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
