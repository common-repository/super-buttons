<?php
/**
 * Button Imports
 *
 * @package Super Buttons
 */

/**
 * AJAX upload button
 *
 * @return boolean if no write access.
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.2
 */
function super_buttons_upload_button() {
	if ( ! isset( $_FILES['button'] ) ) {
		wp_send_json_error( __( 'No file attached.', 'super-buttons' ) );
	}
	// check for valid file format.
	if ( 'application/json' !== $_FILES['button']['type'] ) {
		wp_send_json_error( __( 'Not a valid JSON file.', 'super-buttons' ) );
	}
	// get file access type.
	$access_type = get_filesystem_method();
	// if direct access, proceed.
	if ( 'direct' === $access_type ) {
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );
		// initialize the API.
		if ( ! WP_Filesystem( $creds ) ) {
			// any problems & we exit.
			return false;
		}
		global $wp_filesystem;
		// do our file manipulations below.
		$buttons = json_decode( $wp_filesystem->get_contents(
			sanitize_text_field( wp_unslash( $_FILES['button']['tmp_name'] ) )
		) );
		$count   = 0;
		foreach ( $buttons as $button ) {
			$new  = super_buttons_import_create( $button->title );
			$meta = super_buttons_import_update( $new, $button->meta );
			$count++;
		}
		if ( 1 === $count ) {
			wp_send_json_success( $count . __( ' Button imported successfully.', 'super-buttons' ) );
		} else {
			wp_send_json_success( $count . __( ' Buttons imported successfully.', 'super-buttons' ) );
		}
	} else {
		// don't have direct write access. Prompt user with our notice.
		wp_send_json_error( __( 'Filesystem access not allowed. Import failed.', 'super-buttons' ) );
	}
}
add_action( 'wp_ajax_super_buttons_upload_button', 'super_buttons_upload_button' );

/**
 * Create button on import
 *
 * @param boolean $title Button title.
 *
 * @return Integer $lastid
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.2
 */
function super_buttons_import_create( $title = false ) {
	// button title is must.
	if ( ! isset( $title ) ) {
		wp_send_json_error( __( 'Button title not set.', 'super-buttons' ) );
	}
	// query db.
	global $wpdb;
	// create button.
	$result = $wpdb->insert(
		SUPER_BUTTONS__TABLE_NAME,
		array(
			'title' => sanitize_text_field( $title ),
			'time'  => current_time( 'mysql' ),
		)
	); // db call ok.
	if ( $result ) {
		// get new button id.
		$lastid = $wpdb->insert_id;
		// update button.
		$wpdb->update(
			SUPER_BUTTONS__TABLE_NAME,
			array(
				'title' => $title, // string.
			),
			array( 'id' => $lastid ),
			array(
				'%s', // value.
			),
			array( '%d' )
		); // db call ok; no-cache ok.
		return $lastid;
	}
	// send response.
	wp_send_json_error( __( 'Unable to add button to database.', 'super-buttons' ) );
}

/**
 * Update button meta
 *
 * @param Integer $id button ID.
 * @param Array   $value button meta value.
 *
 * @return Integer $rows_updated
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.2
 */
function super_buttons_import_update( $id, $value ) {
	// id and value is required.
	if ( ! $id || ! $value ) {
		wp_send_json_error( __( 'Button ID or values not available.', 'super-buttons' ) );
	}
	// query db.
	global $wpdb;
	// set variables.
	$table_name     = SUPER_BUTTONS__TABLE_NAME . '_meta';
	$id             = sanitize_key( wp_unslash( $id ) );
	$rows_updated   = 0;
	$button_classes = array();
	// store value for later use.
	set_transient( 'super_button_' . $id . '_meta', $value );
	// store as option for when transient is not available.
	update_option( 'super_button_' . $id . '_meta', $value );
	// loop over values, sanitize before storing.
	foreach ( $value as $tab => $option ) {
		foreach ( $option as $key => $val ) {
			if ( 'text' === $tab ) {
				// store value for later use.
				set_transient( 'super_button_' . sanitize_key( $id ) . '_text', $val );
				// store as option for when transient is not available.
				update_option( 'super_button_' . sanitize_key( $id ) . '_text', $val );
			}
			if ( 'class' === $value->property ) {
				$button_classes[] = $value;
			}
			if ( isset( $val->type ) && 'title' !== $val->type ) {
				// insert or update db.
				$result       = $wpdb->replace(
					$table_name,
					array(
						'button_id' => $id,
						'meta_key'  => sanitize_key( $val->id ), // WPCS: slow query ok.
						'value'     => maybe_serialize( 'text' === $val->id ? super_buttons_sanitize_html( $val->value ) : super_buttons_sanitize_value( $val->value ) ),
						'property'  => sanitize_key( $val->property ),
						'state'     => isset( $val->state ) ? sanitize_key( $val->state ) : 'normal',
					),
					array( '%d', '%s', '%s', '%s', '%s' )
				); // db call ok; no-cache ok.
				$rows_updated = $rows_updated + $result;
				if ( isset( $val->children ) && is_array( $val->children ) ) {
					foreach ( $val->children as $k => $v ) {
						if ( isset( $v->type ) && 'title' !== $v->type ) {
							// insert or update db.
							$result       = $wpdb->replace(
								$table_name,
								array(
									'button_id' => $id,
									'meta_key'  => sanitize_key( $v->id ), // WPCS: slow query ok.
									'value'     => maybe_serialize( super_buttons_sanitize_value( $v->value ) ),
									'property'  => sanitize_key( $v->property ),
									'state'     => isset( $v->state ) ? sanitize_key( $v->state ) : 'normal',
								),
								array( '%d', '%s', '%s', '%s', '%s' )
							); // db call ok; no-cache ok.
							$rows_updated = $rows_updated + $result;
						}
					}
				}
			}
		}
	}
	set_transient( 'super_button_' . $id . '_classes', $button_classes );
	// store as option for when transient is not available.
	update_option( 'super_button_' . $id . '_classes', $button_classes );
	// send number of rows updated ( which should always be 1 ) and die.
	return $rows_updated;
}
