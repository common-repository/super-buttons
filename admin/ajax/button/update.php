<?php
/**
 * Ajax call for button updates
 *
 * @package Super Buttons
 */

/**
 * Sanitize html value before saving in db
 *
 * @param Array $value option value.
 *
 * @return Array
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.7
 */
function super_buttons_sanitize_html( $value ) {
	// value should be an array.
	if ( 'array' !== gettype( $value ) ) {
		return false;
	}
	// error_log( wp_json_encode( $value ) );
	// sanitize each and every array value.
	array_walk( $value, function( $val, $key ) {
		switch ( gettype( $val ) ) {
			case 'object':
				foreach ( $val as $k => $v ) {
					if ( $v->value && 'string' === gettype( $v->value ) ) {
						$v->value = wp_unslash( wp_filter_post_kses( $v->value ) );
					}
					if ( $v->unit && 'string' === gettype( $v->unit ) ) {
						$v->unit = sanitize_text_field( $v->unit );
					}
				}
				break;
			default:
				$val = sanitize_text_field( $val );
		};
	});
	// return sanitized value.
	return $value;
}

/**
 * Sanitize value before saving in db
 *
 * @param Array $value option value.
 *
 * @return Array
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.7
 */
function super_buttons_sanitize_value( $value ) {
	// value should be an array.
	if ( 'array' !== gettype( $value ) ) {
		return false;
	}
	// sanitize each and every array value.
	array_walk( $value, function( $val, $key ) {
		switch ( gettype( $val ) ) {
			case 'object':
				foreach ( $val as $k => $v ) {
					if ( is_object( $v ) ) {
						if ( $v->value && 'string' === gettype( $v->value ) ) {
							$v->value = sanitize_text_field( $v->value );
						}
						if ( $v->value && 'object' === gettype( $v->value ) ) {
							foreach ( $v->value as $a => $b ) {
								if ( 'string' === gettype( $b ) ) {
									$v->value->$a = sanitize_text_field( $b );
								}
								if ( 'object' === gettype( $b ) ) {
									foreach ( $b as $c => $d ) {
										$v->value->$a->$c = sanitize_text_field( $d );
									}
								}
								if ( 'array' === gettype( $b ) ) {
									array_walk( $v->value->$a, function( $d, $c ) {
										if ( 'object' === gettype( $d ) ) {
											foreach ( $d as $e => $f ) {
												$d->$e = sanitize_text_field( $f );
											}
										}
										if ( 'string' === gettype( $d ) ) {
											$d = sanitize_text_field( $d );
										}
									});
								}
							}
						}
						if ( $v->unit && 'string' === gettype( $v->unit ) ) {
							$v->unit = sanitize_text_field( $v->unit );
						}
					}
				}
				break;
			default:
				$val = sanitize_text_field( $val );
		};
	});
	// return sanitized value.
	return $value;
}

/**
 * Update button options
 * Requires button id and button options to send along post request
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_update_button() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// check button id and value.
	if ( ! isset( $_POST['id'] ) || ! isset( $_POST['value'] ) ) {
		return;
	}
	// query db.
	global $wpdb;
	$id             = sanitize_key( wp_unslash( $_POST['id'] ) );
	$table_name     = SUPER_BUTTONS__TABLE_NAME . '_meta';
	$rows_updated   = 0;
	$button_classes = array();
	$decoded_value  = json_decode( wp_unslash( $_POST['value'] ) ); // sanitized user input below using super_buttons_sanitize_* functions. WPCS: sanitization ok.
	// store value for later use.
	set_transient( 'super_button_' . $id . '_meta', $decoded_value );
	// store as option for when transient is not available.
	update_option( 'super_button_' . $id . '_meta', $decoded_value );
	foreach ( $decoded_value as $tab => $option ) {
		foreach ( $option as $key => $value ) {
			if ( 'text' === $tab ) {
				// store value for later use.
				set_transient( 'super_button_' . $id . '_text', $value );
				// store as option for when transient is not available.
				update_option( 'super_button_' . $id . '_text', $value );
			}
			if ( isset( $value->property ) && 'class' === $value->property ) {
				$button_classes[] = $value;
			}
			if ( isset( $value->type ) && isset( $value->type ) && 'title' !== $value->type && 'label' !== $value->type ) {
				// insert or update db.
				$result       = $wpdb->replace(
					$table_name,
					array(
						'button_id' => $id,
						'meta_key'  => sanitize_key( $value->id ), // WPCS: slow query ok.
						'value'     => maybe_serialize( 'text' === $value->id ? super_buttons_sanitize_html( $value->value ) : super_buttons_sanitize_value( $value->value ) ),
						'property'  => sanitize_key( $value->property ),
						'state'     => isset( $value->state ) ? sanitize_key( $value->state ) : 'normal',
					),
					array( '%d', '%s', '%s', '%s', '%s' )
				); // db call ok; no-cache ok.
				$rows_updated = $rows_updated + $result;
				if ( isset( $value->children ) && is_array( $value->children ) ) {
					foreach ( $value->children as $k => $v ) {
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
	wp_send_json( $rows_updated );
}
add_action( 'wp_ajax_super_buttons_update_button', 'super_buttons_update_button' );
