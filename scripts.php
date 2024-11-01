<?php
/**
 * Scripts & Styles
 *
 * @package Super Buttons
 */

/**
 * Enqueue Scripts and Styles
 *
 * @param String $hook current page slug.
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_public_scripts( $hook ) {
	wp_enqueue_style( 'super-buttons-style', SUPER_BUTTONS__PLUGIN_URL . 'assets/style.min.css', '', '1.0.0' );
	wp_enqueue_script( 'super-buttons-public', SUPER_BUTTONS__PLUGIN_URL . 'assets/public.js', array( 'jquery' ), SUPER_BUTTONS__VERSION, true );
	wp_localize_script(
		'super-buttons-public',
		'sba',
		array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'super-buttons-analytics' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'super_buttons_public_scripts', 99 );
