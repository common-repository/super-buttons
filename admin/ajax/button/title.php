<?php
/**
 * Ajax call for Button title
 *
 * @package Super Buttons
 */

/**
 * Change button title
 * Requires button id and button options to send along post request
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_change_button_title() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// check required values.
	if ( ! isset( $_POST['id'] ) || ! isset( $_POST['title'] ) ) {
		return;
	}
	// query db.
	global $wpdb;
	$id         = sanitize_key( wp_unslash( $_POST['id'] ) );
	$title      = sanitize_text_field( wp_unslash( $_POST['title'] ) );
	$table_name = SUPER_BUTTONS__TABLE_NAME;
	$result     = $wpdb->update(
		$table_name,
		array(
			'title' => $title,
		),
		array(
			'id' => $id,
		),
		array( '%s' ),
		array( '%d' )
	); // db call ok; no-cache ok.
	// send number of rows updated ( which should always be 1 ) and die.
	wp_send_json( $result );
}
add_action( 'wp_ajax_super_buttons_change_button_title', 'super_buttons_change_button_title' );
