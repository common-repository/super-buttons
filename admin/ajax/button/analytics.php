<?php
/**
 * Ajax call for button clicks
 *
 * @package Super Buttons
 */

/**
 * Ajax callback for button click
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_analytics_ajax_callback() {
	// security check.
	check_ajax_referer( 'super-buttons-analytics', 'security' );
	// make sure id is set. sanitized below.
	if ( isset( $_POST['id'] ) ) {
		// query db.
		global $wpdb;
		// set variables.
		$table_name = SUPER_BUTTONS__TABLE_NAME;
		$id         = sanitize_key( wp_unslash( $_POST['id'] ) );
		$wpdb->query(
			$wpdb->prepare(
				"
				UPDATE $table_name 
				SET clicks = clicks + 1
				WHERE id = %d
				",
				$id
			)
		); // db call ok; no-cache ok. WPCS: unprepared SQL OK.
	}
	// close.
	wp_die();
}
add_action( 'wp_ajax_super_buttons_analytics', 'super_buttons_analytics_ajax_callback' );
