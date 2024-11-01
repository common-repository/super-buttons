<?php
/**
 * Ajax call for creating button
 *
 * @package Super Buttons
 */

/**
 * Create button
 *
 * @return void
 *
 * @author Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @since 1.0.0
 */
function super_buttons_create_button() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// query db.
	global $wpdb;
	$result = $wpdb->insert(
		SUPER_BUTTONS__TABLE_NAME,
		array(
			'title' => 'Button',
			'time'  => current_time( 'mysql' ),
		)
	); // db call ok; no-cache ok.
	if ( $result ) {
		$lastid = $wpdb->insert_id;
		$wpdb->update(
			SUPER_BUTTONS__TABLE_NAME,
			array(
				// Translators: button id.
				'title' => isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : sprintf( __( 'Button #%s', 'super-buttons' ), $lastid ), // string.
			),
			array( 'id' => $lastid ),
			array( '%s' ),
			array( '%d' )
		); // db call ok; no-cache ok.
		wp_send_json( $lastid );
	} else {
		wp_send_json( $result );
	}
	wp_die();
}
add_action( 'wp_ajax_super_buttons_create_button', 'super_buttons_create_button' );

/**
 * Duplicate button
 *
 * @return void
 *
 * @author Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @since 1.3.0
 */
function super_buttons_duplicate_button() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// query db.
	global $wpdb;
	$result = $wpdb->insert(
		SUPER_BUTTONS__TABLE_NAME,
		array(
			'title' => 'Button',
			'time'  => current_time( 'mysql' ),
		)
	); // db call ok; no-cache ok.
	if ( $result ) {
		$id         = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$lastid     = $wpdb->insert_id;
		$table_name = SUPER_BUTTONS__TABLE_NAME . '_meta';
		$wpdb->update(
			SUPER_BUTTONS__TABLE_NAME,
			array(
				// Translators: button id.
				'title' => isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : sprintf( __( 'Button #%s', 'super-buttons' ), $lastid ), // string.
			),
			array( 'id' => $lastid ),
			array( '%s' ),
			array( '%d' )
		); // db call ok; no-cache ok.
		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE button_id = %d",
			$id
		), ARRAY_A ); // db call ok; no-cache ok. WPCS: unprepared SQL OK.
		foreach ( $results as $key => $option ) {
			// duplicate each meta value for new button.
			$wpdb->replace(
				$table_name,
				array(
					'button_id' => $lastid,
					'meta_key'  => $option['meta_key'], // WPCS: slow query ok.
					'value'     => $option['value'],
					'property'  => $option['property'],
					'state'     => $option['state'],
				),
				array( '%d', '%s', '%s', '%s', '%s' )
			); // db call ok; no-cache ok.
		}
		wp_send_json( $lastid );
	} else {
		wp_send_json( $result );
	}
	wp_die();
}
add_action( 'wp_ajax_super_buttons_duplicate_button', 'super_buttons_duplicate_button' );
