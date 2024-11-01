<?php
/**
 * Ajax call for deleting button
 *
 * @package Super Buttons
 */

/**
 * Delete button
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_delete_button() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// check button id.
	if ( ! isset( $_POST['id'] ) ) {
		return;
	}
	// set variables.
	$table_name = SUPER_BUTTONS__TABLE_NAME;
	$id         = sanitize_text_field( wp_unslash( $_POST['id'] ) );
	// update db.
	global $wpdb;
	// delete button.
	$result = $wpdb->delete(
		SUPER_BUTTONS__TABLE_NAME,
		array(
			'id' => $id,
		)
	); // db call ok; no-cache ok.
	// delete button meta.
	$meta = $wpdb->delete(
		$table_name . '_meta',
		array(
			'button_id' => $id,
		)
	); // db call ok; no-cache ok.
	// delete cached values.
	delete_transient( 'super_button_' . $id . '_text' );
	delete_transient( 'super_button_' . $id . '_meta' );
	delete_transient( 'super_button_' . $id . '_classes' );
	// delete option values.
	delete_option( 'super_button_' . $id . '_text' );
	delete_option( 'super_button_' . $id . '_meta' );
	delete_option( 'super_button_' . $id . '_classes' );
	// send result.
	wp_send_json( $result );
}
add_action( 'wp_ajax_super_buttons_delete_button', 'super_buttons_delete_button' );
