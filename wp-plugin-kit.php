<?php
/**
 * WpPluginKit - A starter plugin for WordPress
 *
 * @package    WpPluginKit - WpPluginKit
 * @author     WpPluginKit <therakib7@gmail.com>
 * @link       https://therakib7.com
 * @copyright  2023 WpPluginKit
 *
 * @wordpress-plugin
 * Plugin Name:       WP Plugin Kit
 * Plugin URI:        https://wordpress.org/plugins/wp-plugin-kit
 * Description:       A starter plugin for WordPress that provides a foundational structure to build upon
 * Version:           0.1.0
 * Author:            WpPluginKit
 * Author URI:        https://therakib7.com
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Tested up to:      6.4
 * Author:            WpPluginKit
 * Author URI:        https://therakib7.com
 * Text Domain:       wp-plugin-kit
 * Domain Path:       /languages
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Don't allow call the file directly
 *
 * @since 0.1.0
 */
defined( 'ABSPATH' ) || exit;

/**
 * WpPluginKit Final Class
 *
 * @since 0.1.0
 *
 * @class WpPluginKit The class that holds the entire WpPluginKit plugin
 */

final class WpPluginKit {

    /**
     * Plugin version
     *
     * @since 0.1.0
	 *
     * @var string
     */
    const VERSION = '0.1.0';

	/**
     * Instance of self
	 *
     * @since 0.1.0
	 *
     * @var WpPluginKit
     */
    private static $instance = null;

    /**
     * Minimum PHP version required
	 *
     * @since 0.1.0
	 *
     * @var string
     */
    const MIN_PHP = '7.4';

    /**
     * Constructor for the WpPluginKit class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        require_once __DIR__ . '/vendor/autoload.php';

        $this->define_constants();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'wp_loaded', array( $this, 'flush_rewrite_rules' ) );

		$this->init_plugin();
    }

	/**
     * Initializes the WpPluginKit() class
     *
     * Checks for an existing WpPluginKit() instance
     * and if it doesn't find one, create it.
     */
    public static function init() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Define all constants
	 *
     * @since 0.1.0
	 *
     * @return void
     */
    public function define_constants() {
        define( 'RAKIB_VERSION', self::VERSION );
        define( 'RAKIB_FILE', __FILE__ );
        define( 'RAKIB_DIR', __DIR__ );
        define( 'RAKIB_PATH', plugin_dir_path( RAKIB_FILE ) );
        define( 'RAKIB_URL', plugins_url( '', RAKIB_FILE ) );
        define( 'RAKIB_SLUG', basename( RAKIB_DIR ) );
        define( 'RAKIB_TEMPLATE_PATH', RAKIB_PATH . '/templates' );
        define( 'RAKIB_BUILD', RAKIB_URL . '/build' );
        define( 'RAKIB_ASSETS', RAKIB_URL . '/assets' );
    }

	/**
     * Load the plugin after all plugins are loaded.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function init_plugin() {

		do_action( 'wp_plugin_kit_before_init' );

        $this->includes();
        $this->init_hooks();

		// Fires after the plugin is loaded.
        do_action( 'wp_plugin_kit_init' );
    }

	/**
     * Include the required files.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function includes() {
        // Common classes
    }

	/**
     * Initialize the hooks.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function init_hooks() {

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

	/**
     * Initialize plugin for localization
     *
     * @since 0.1.0
     *
     * @uses load_plugin_textdomain()
	 *
	 * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wp-plugin-kit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

	/**
     * Activating the plugin.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function activate() {
        // Run the installer to create necessary migrations and seeders.
    }

    /**
     * Placeholder for deactivation function.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function deactivate() {
        // Remove unnecessary data when deactivate
    }

	/**
     * Flush rewrite rules after plugin is activated.
     *
     * Nothing being added here yet.
     *
     * @since 0.1.0
	 *
	 * @return void
     */
    public function flush_rewrite_rules() {
        // fix rewrite rules
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or public.
     *
     * @since 0.1.0
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined( 'DOING_AJAX' );

            case 'rest':
                return defined( 'REST_REQUEST' );

            case 'cron':
                return defined( 'DOING_CRON' );

            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }
}

/**
 * Initialize the main plugin.
 *
 * @since 0.1.0
 *
 * @return WpPluginKit
 */
function wp_plugin_kit() {
    return WpPluginKit::init();
}
wp_plugin_kit(); // Run WpPluginKit Plugin
