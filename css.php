<?php
/**
 * CSS
 *
 * @package Super Buttons
 */

/**
 * Search in multi dimensional array
 *
 * @param string  $elem string to search.
 * @param array   $array array to search in.
 * @param boolean $field strict comparison.
 *
 * @return Boolean
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.1.0
 */
function super_buttons_in_multiarray( $elem, $array, $field ) {
	$top    = count( $array ) - 1;
	$bottom = 0;
	while ( $bottom <= $top ) {
		if ( $array[ $bottom ]->$field === $elem ) {
			return true;
		} else {
			if ( is_array( $array[ $bottom ]->$field ) ) {
				if ( super_buttons_in_multiarray( $elem, ( $array[ $bottom ]->$field ) ) ) {
					return true;
				}
			}
		}
		$bottom++;
	}
	return false;
}

/**
 * Function to generate css from meta values
 *
 * @param String $id Button ID.
 *
 * @return String
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_generate_all_css( $id ) {
	$meta = get_transient( 'super_button_' . $id . '_meta' );

	// fallback.
	if ( ! $meta ) {
		$meta = get_option( 'super_button_' . $id . '_meta' );
	}
	// get button meta values from transient.
	if ( false === $meta ) {
		return false;
	}

	$default = array(
		'rolesAllowed'     => array( 'administrator' ),
		'breakpointTablet' => 768,
		'breakpointMobile' => 576,
	);

	$options = get_option( 'super_buttons', $default );
	$css     = '';
	// import statements should be at the top.
	$import = array();

	foreach ( $meta as $tab => $option ) {
		foreach ( $option as $key => $value ) {
			if ( isset( $value->type ) && 'title' !== $value->type ) {
				if ( isset( $value->target ) && isset( $value->property ) ) {
					if ( isset( $value->value ) ) {
						foreach ( $value->value as $val ) {
							if ( 'class' !== $value->property && 'attribute' !== $value->property ) {
								$target = implode( ' ', $value->target );
								if ( 'background-image' === $value->property ) {
									foreach ( $val as $idx => $v ) {
										if ( ! empty( $v->value ) ) {
											if ( 'tablet' === $idx ) {
												$css .= '@media (max-width: ' . $options['breakpointTablet'] . 'px) {' . "\r\n";
											}
											if ( 'mobile' === $idx ) {
												$css .= '@media (max-width: ' . $options['breakpointMobile'] . 'px) {' . "\r\n";
											}
											$unit = isset( $v->unit ) ? $v->unit : 'px';

											$css .= "{$target} { {$value->property}: url({$v->value}); }\r\n";

											if ( 'tablet' === $idx || 'mobile' === $idx ) {
												$css .= "}\r\n";
											}
										}
									}
								} elseif ( 'border' === $value->property ) {
									foreach ( $val as $idx => $v ) {
										if ( ! empty( $v->value ) && isset( $v->value->size ) ) {
											$sides_default = array(
												'top',
												'bottom',
												'left',
												'right',
											);
											$sides_saved   = $v->value->sides;
											foreach ( $sides_default as $side_value ) {
												if ( 'tablet' === $idx ) {
													$css .= '@media (max-width: ' . $options['breakpointTablet'] . 'px) {' . "\r\n";
												}
												if ( 'mobile' === $idx ) {
													$css .= '@media (max-width: ' . $options['breakpointMobile'] . 'px) {' . "\r\n";
												}
												$unit   = isset( $v->unit ) ? $v->unit : 'px';
												$b_size = super_buttons_in_multiarray( $side_value, $sides_saved, 'value' ) ? $v->value->size . $unit : 0;
												$css   .= "{$target} { {$value->property}-{$side_value}: {$b_size} {$v->value->style->value} {$v->value->color};}\r\n";
												if ( 'tablet' === $idx || 'mobile' === $idx ) {
													$css .= "}\r\n";
												}
											}
										}
									}
								} elseif ( 'border-radius' === $value->property ) {
									foreach ( $val as $idx => $v ) {
										if ( ! empty( $v->value ) && isset( $v->value->size ) ) {
											if ( isset( $v->value->sides ) && isset( $v->value->states ) ) {
												foreach ( $v->value->states as $state_value ) {
													$br_sides_default = array(
														'top-left',
														'top-right',
														'bottom-left',
														'bottom-right',
													);
													$br_sides_saved   = $v->value->sides;
													foreach ( $br_sides_default as $side_value ) {
														if ( 'tablet' === $idx ) {
															$css .= '@media (max-width: ' . $options['breakpointTablet'] . 'px) {' . "\r\n";
														}
														if ( 'mobile' === $idx ) {
															$css .= '@media (max-width: ' . $options['breakpointMobile'] . 'px) {' . "\r\n";
														}
														$unit    = isset( $v->unit ) ? $v->unit : 'px';
														$state   = isset( $state_value->value ) && 'normal' !== $state_value->value ? ':' . $state_value->value : '';
														$br_size = super_buttons_in_multiarray( $side_value, $br_sides_saved, 'value' ) ? $v->value->size . $unit : 0;
														$css    .= "{$target}{$state} { border-{$side_value}-radius: {$br_size};}\r\n";
														if ( 'tablet' === $idx || 'mobile' === $idx ) {
															$css .= "}\r\n";
														}
													}
												}
											}
										}
									}
								} else {
									foreach ( $val as $idx => $v ) {
										if ( isset( $v->value )  ) {
											if ( 'tablet' === $idx ) {
												$css .= '@media (max-width: ' . $options['breakpointTablet'] . 'px) {' . "\r\n";
											}
											if ( 'mobile' === $idx ) {
												$css .= '@media (max-width: ' . $options['breakpointMobile'] . 'px) {' . "\r\n";
											}
											$unit = isset( $v->unit ) || null === $v->unit ? $v->unit : 'px';
											$valu = isset( $v->value ) && is_object( $v->value ) ? $v->value->value : $v->value;
											$css .= "{$target} { {$value->property}: {$valu}{$unit}; }\r\n";
											if ( 'tablet' === $idx || 'mobile' === $idx ) {
												$css .= "}\r\n";
											}
										}
									}
								}
							}
						}
					}
				}
			}
			if ( isset( $value->children ) ) {
				foreach ( $value->children as $ckey => $cvalue ) {
					if ( isset( $cvalue->type ) && 'title' !== $cvalue->type ) {
						if ( isset( $cvalue->target ) && isset( $cvalue->property ) ) {
							if ( isset( $cvalue->value ) ) {
								foreach ( $cvalue->value as $ckey => $cval ) {
									foreach ( $cval as $cidx => $cv ) {
										if ( ! empty( $cv->value ) ) {
											if ( 'font-family' === $cvalue->property ) {
												$font = isset( $cv->value ) && is_object( $cv->value ) ? $cv->value->value : $cv->value;
												if ( 'inherit' !== $font ) {
													$font     = explode( ',', $font );
													$font[0]  = preg_replace( '/\s+/', '+', $font[0] );
													$import[] = "@import url('https://fonts.googleapis.com/css?family={$font[0]}');\r\n";
												}
											}
											if ( 'tablet' === $cidx ) {
												$css .= '@media (max-width: ' . $options['breakpointTablet'] . 'px) {' . "\r\n";
											}
											if ( 'mobile' === $cidx ) {
												$css .= '@media (max-width: ' . $options['breakpointMobile'] . 'px) {' . "\r\n";
											}

											$unit     = isset( $cv->unit ) || null === $cv->unit ? $cv->unit : 'px';
											$valu     = isset( $cv->value ) && is_object( $cv->value ) ? $cv->value->value : $cv->value;
											$text_idx = 'text' === $tab ? $ckey : '';
											$target   = implode( ' ', $cvalue->target );

											if ( 'font-family' === $cvalue->property ) {
												if ( 'inherit' !== $valu ) {
													$valu     = explode( ',', $valu );
													$fallback = $valu[1];

													if ( ' handwriting' === $fallback ) {
														$fallback = ' cursive';
													}
													$css .= "{$target}{$text_idx}__{$cidx} { {$cvalue->property}: '{$valu[0]}',{$fallback}; }\r\n";
												}
											} else {
												$css .= "{$target}{$text_idx}__{$cidx} { {$cvalue->property}: {$valu}{$unit}; }\r\n";
											}
											if ( 'tablet' === $cidx || 'mobile' === $cidx ) {
												$css .= "}\r\n";
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	$output = '';
	$import = array_unique( $import );

	foreach ( $import as $key => $value ) {
		$output .= $value;
	}

	// Responsive tablet css.
	$css .= "@media( max-width: {$options['breakpointTablet']}px ){ .super-button__text__desktop{ display: none!important; } .super-button__text__tablet { display: inline-block; white-space: normal; } }\r\n";

	// Responsive mobile css.
	$css .= "@media( max-width: {$options['breakpointMobile']}px ){ .super-button__text__tablet{ display: none!important; } .super-button__text__mobile { display: inline-block; white-space: normal; } }\r\n";

	$output .= $css;
	return $output;
}
