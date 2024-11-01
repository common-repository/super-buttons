<?php
/**
 * Plugin Name:  Super Buttons
 * Plugin URI:   https://thewebsitedev.com/products/super-buttons/
 * Description:  The best buttons plugin for WordPress.
 * Version:      1.4.0
 * Author:       The Website Dev
 * Author URI:   https://thewebsitedev.com/
 * License:      GPL2+
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  super-buttons
 * Domain Path:  /languages
 *
 * @package SUPER BUTTONS
 */

// Make sure we don't expose any info if called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

// Define constants.
define( 'SUPER_BUTTONS__VERSION', '1.4.0' );
define( 'SUPER_BUTTONS__MIN_WP_VERSION', '4.8' );
define( 'SUPER_BUTTONS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SUPER_BUTTONS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SUPER_BUTTONS__TABLE_NAME', $wpdb->prefix . 'super_buttons' );
define( 'SUPER_BUTTONS_STORE_URL', 'https://thewebsitedev.com/' );
define( 'SUPER_BUTTONS_ITEM_ID', 216 );

// Load files.
require SUPER_BUTTONS__PLUGIN_DIR . 'db.php';
// fire activation hook.
register_activation_hook( __FILE__, 'super_buttons_install' );
// load admin.
require SUPER_BUTTONS__PLUGIN_DIR . 'admin/functions.php';
// load public scripts.
require SUPER_BUTTONS__PLUGIN_DIR . 'scripts.php';
// load public functions.
require SUPER_BUTTONS__PLUGIN_DIR . 'functions.php';

/**
 * Load plugin textdomain.
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.7
 */
function super_buttons_load_textdomain() {
	load_plugin_textdomain( 'super-buttons', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'super_buttons_load_textdomain' );
