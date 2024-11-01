<?php
/**
 * Admin Scripts
 *
 * @package Super Buttons
 */

// define global settings.
$GLOBALS['SUPER_BUTTONS_DEFAULT_SETTINGS'] = array(
	'rolesAllowed'     => array( 'administrator' ),
	'breakpointTablet' => 768,
	'breakpointMobile' => 576,
	'license'          => '',
	'license_key'      => '',
);

/**
 * Plugin Settings
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_get_settings() {
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// get settings.
	$settings = maybe_unserialize( get_option( 'super_buttons', $GLOBALS['SUPER_BUTTONS_DEFAULT_SETTINGS'] ) );
	// send json result.
	wp_send_json( $settings );
}
add_action( 'wp_ajax_super_buttons_get_settings', 'super_buttons_get_settings' );

/**
 * Sanitize setting value
 *
 * @param Array $settings settings.
 *
 * @return Boolean|Array
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.7
 */
function super_buttons_sanitize_settings( $settings ) {
	// settings should be array.
	if ( 'array' !== gettype( $settings ) ) {
		return false;
	}
	// sanitize each and every array value.
	array_walk( $settings, function( $value, $key ) {
		switch ( gettype( $value ) ) {
			case 'array':
				$value = array_map( 'sanitize_text_field', $value );
				break;
			default:
				$value = sanitize_text_field( $value );
		};
	});
	// return sanitized settings.
	return $settings;
}

/**
 * Save plugin settings
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_save_setting() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// proceed if settings is not empty.
	if ( ! empty( $_POST['value'] ) ) {
		$settings = super_buttons_sanitize_settings( wp_unslash( $_POST['value'] ) );
		$result   = update_option( 'super_buttons', $settings );
		// send json result, true if changed.
		$return = array(
			$result,
			$settings,
		);
		wp_send_json( $return );
	}
}
add_action( 'wp_ajax_super_buttons_save_setting', 'super_buttons_save_setting' );

/**
 * Get plugin settings
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_create_settings() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// saved settings.
	$saved_settings = maybe_unserialize( get_option( 'super_buttons', $GLOBALS['SUPER_BUTTONS_DEFAULT_SETTINGS'] ) );
	// User roles.
	$wp_roles = wp_roles();
	// covert wp roles to desired format.
	$roles = array();
	foreach ( $wp_roles->roles as $key => $role ) {
		$roles[] = array(
			'label' => $role['name'],
			'value' => $key,
		);
	}
	// create settings.
	$settings = array(
		array(
			'id'          => 'rolesAllowed',
			'name'        => 'rolesAllowed',
			'label'       => __( 'Who can edit buttons?', 'super-buttons' ),
			'type'        => 'checkbox',
			'class'       => '',
			'placeholder' => '',
			'value'       => isset( $saved_settings['rolesAllowed'] ) ? maybe_unserialize( $saved_settings['rolesAllowed'] ) : $GLOBALS['SUPER_BUTTONS_DEFAULT_SETTINGS']['rolesAllowed'],
			'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6
			'options'     => $roles,
		),
		array(
			'type'  => 'title',
			'label' => __( 'Responsive BreakPoints', 'super-buttons' ),
		),
		array(
			'id'          => 'breakpointTablet',
			'name'        => 'breakpointTablet',
			'label'       => __( 'Tablet (in pixels)', 'super-buttons' ),
			'type'        => 'text',
			'class'       => '',
			'placeholder' => '',
			'value'       => isset( $saved_settings['breakpointTablet'] ) ? maybe_unserialize( $saved_settings['breakpointTablet'] ) : $GLOBALS['SUPER_BUTTONS_DEFAULT_SETTINGS']['breakpointTablet'],
			'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6
		),
		array(
			'id'          => 'breakpointMobile',
			'name'        => 'breakpointMobile',
			'label'       => __( 'Mobile (in pixels)', 'super-buttons' ),
			'type'        => 'text',
			'class'       => '',
			'placeholder' => '',
			'value'       => isset( $saved_settings['breakpointMobile'] ) ? maybe_unserialize( $saved_settings['breakpointMobile'] ) : $GLOBALS['SUPER_BUTTONS_DEFAULT_SETTINGS']['breakpointMobile'],
			'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6
		),
	);
	// allow to modify or add extra settings.
	$result = apply_filters( 'super_buttons__settings', $settings, $saved_settings );
	// send settings as json.
	wp_send_json( $result );
}
add_action( 'wp_ajax_super_buttons_create_settings', 'super_buttons_create_settings' );
