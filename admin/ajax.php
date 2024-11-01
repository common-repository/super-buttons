<?php
/**
 * Admin AJAX Functionality
 *
 * @package Super Buttons
 */

/**
 * Get user roles
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0
 */
function super_buttons_get_user_roles() {
	global $wp_roles;
	// set required roles.
	$roles = $wp_roles;
	// set wp roles.
	if ( ! isset( $wp_roles ) ) {
		$roles = new WP_Roles();
	}
	// send result.
	wp_send_json( $roles->get_names() );
}
add_action( 'wp_ajax_super_buttons_get_user_roles', 'super_buttons_get_user_roles' );

// create button.
require 'ajax/button/create.php';
// fetch button(s).
require 'ajax/button/fetch.php';
// delete button.
require 'ajax/button/delete.php';
// button options.
require 'ajax/button/options.php';
// update button.
require 'ajax/button/update.php';
// button title.
require 'ajax/button/title.php';
// button clicks.
require 'ajax/button/analytics.php';
// settings.
require 'ajax/settings.php';
