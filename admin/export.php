<?php
/**
 * Button export.
 *
 * @package Super Buttons
 */

/**
 * Button download
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.1
 */
function super_buttons_download_button() {
	// security check.
	if ( ! isset( $_GET['nonce'] ) ) {
		return;
	}
	$nonce = $_GET['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'super-buttons-admin' ) ) {
		return;
	}
	// return if not buttons screen.
	if ( ! isset( $_GET['page'] ) ) {
		return;
	}
	if ( 'super-buttons' !== $_GET['page'] ) {
		return;
	}
	// get the current button id and title.
	if ( ! isset( $_GET['id'] ) || ! isset( $_GET['title'] ) ) {
		return;
	}
	$id    = sanitize_key( wp_unslash( $_GET['id'] ) );
	$title = sanitize_text_field( wp_unslash( $_GET['title'] ) );
	// get meta values.
	$meta = get_transient( 'super_button_' . $id . '_meta' );
	// fallback.
	if ( ! $meta ) {
		$meta = get_option( 'super_button_' . $id . '_meta' );
	}
	// create button.
	$button = array(
		'id'    => $id,
		'title' => $title,
		'meta'  => $meta,
	);
	// print json data.
	super_buttons_print_json( array( $button ) );
}
add_action( 'admin_init', 'super_buttons_download_button' );

/**
 * JSON output for file download
 *
 * @param String $output button options.
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.1
 */
function super_buttons_print_json( $output ) {
	// Create a file name.
	$filename = 'Super-Button.' . date( 'YmdHis' ) . '.json';
	// Print header.
	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/txt;' );
	header( 'Content-Disposition: attachment; filename=' . $filename );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate' );
	header( 'Pragma: public' );
	// print encoded data.
	print wp_json_encode( $output ); // Use json_encode( $output, JSON_PRETTY_PRINT ) for pretty print.
	exit;
}
