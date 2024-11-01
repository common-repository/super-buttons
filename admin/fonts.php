<?php
/**
 * Fonts
 *
 * @package Super Buttons
 */

/**
 * Update fonts every week
 *
 * @param String $file something.
 *
 * @return bool
 */
function super_buttons_update_fonts( $file ) {
	$mdate = date( 'Ymd', filemtime( $file ) );
	$date  = date( 'Ymd' );
	return ( $mdate + 7 >= $date );
}

/**
 * Get the google fonts from the API or in the cache
 *
 * @param int|string $amount number of fonts.
 *
 * @return Array
 */
function super_buttons_get_fonts( $amount = 'all' ) {
	$select_directory       = SUPER_BUTTONS__PLUGIN_DIR . '/admin';
	$final_select_directory = '';

	if ( is_dir( $select_directory ) ) {
		$final_select_directory = $select_directory;
	}

	$font_file = $final_select_directory . '/cache/google-web-fonts.txt';

	// update fonts if not updated for a week.
	if ( file_exists( $font_file ) && super_buttons_update_fonts( $font_file ) ) {
		$content = json_decode( file_get_contents( $font_file ) );
	} else {
		$google_api = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=AIzaSyAfeY3uCiplC3aZi-AloPjwZBfEVnDIwzU';

		$font_content = wp_remote_get( $google_api, array( 'sslverify' => false ) );

		$fp = fopen( $font_file, 'w' );
		fwrite( $fp, $font_content['body'] );
		fclose( $fp );

		$content = json_decode( $font_content['body'] );
	}

	if ( null === $content ) {
		return array();
	}

	$fonts = array();
	foreach ( $content->items as $font ) {
		$fonts[] = array(
			'value' => $font->family . ', ' . $font->category,
			'label' => $font->family,
		);
	}

	return $fonts;
}
