<?php
/**
 * Ajax call for fetching buttons
 *
 * @package Super Buttons
 */

/**
 * Get all buttons
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_get_buttons() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// query database.
	global $wpdb;
	$table_name = SUPER_BUTTONS__TABLE_NAME;
	$id         = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : false;
	if ( $id ) {
		$result = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE id < %d ORDER BY id DESC LIMIT 10",
			$id
		), OBJECT ); // db call ok; no-cache ok. WPCS: unprepared SQL OK.
	} else {
		$result = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC LIMIT 10", OBJECT ); // db call ok; no-cache ok; WPCS: unprepared SQL OK.
	}
	// send result.
	wp_send_json( $result );
}
add_action( 'wp_ajax_super_buttons_get_buttons', 'super_buttons_get_buttons' );

/**
 * Get single button
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0
 */
function super_buttons_get_button() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// check button id.
	if ( ! isset( $_POST['id'] ) ) {
		return;
	}
	// set variables.
	$table_name = SUPER_BUTTONS__TABLE_NAME;
	$id         = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : false;
	// query database.
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM $table_name WHERE id = %d",
		$id
	), OBJECT ); // db call ok; no-cache ok. WPCS: unprepared SQL OK.
	// send result.
	wp_send_json( $result[0] );
}
add_action( 'wp_ajax_super_buttons_get_button', 'super_buttons_get_button' );
