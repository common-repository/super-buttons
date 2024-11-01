<?php
/**
 * Shortcodes
 *
 * @package Super Buttons
 */

/**
 * Button Shortcode
 *
 * @param   Array $atts passed values.
 *
 * @return  String
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_short_code_callback( $atts ) {

	$atts = shortcode_atts( array(
		'id'        => '',
		'class'     => '',
		'alignment' => 'center',
		'url'       => '',
		'target'    => '',
	), $atts, 'super_button' );

	$css   = super_buttons_generate_all_css( $atts['id'] );
	$texts = get_transient( 'super_button_' . $atts['id'] . '_text' );
	// fallback.
	if ( ! $texts ) {
		$texts = get_option( 'super_button_' . $atts['id'] . '_text' );
	}
	$meta = get_transient( 'super_button_' . $atts['id'] . '_meta' );
	// fallback.
	if ( ! $meta ) {
		$meta = get_option( 'super_button_' . $atts['id'] . '_meta' );
	}
	$classes = get_transient( 'super_button_' . $atts['id'] . '_classes' );
	// fallback.
	if ( ! $classes ) {
		$classes = get_option( 'super_button_' . $atts['id'] . '_classes' );
	}
	$sb_classes = ' ';

	if ( $meta ) {
		$meta_link   = 'label' === $meta->link[0]->type ? $meta->link[1] : $meta->link[0];
		$link        = $atts['url'] && ! empty( $atts['url'] ) ? $atts['url'] : $meta_link->value[0]->desktop->value;
		$meta_target = 'label' === $meta->link[0]->type ? $meta->link[2] : $meta->link[1];
		$link_target = $atts['target'] && ! empty( $atts['target'] ) ? $atts['target'] : isset( $meta_target->value[0]->desktop->value->value ) ? $meta_target->value[0]->desktop->value->value : '_self';
		$tooltip     = isset( $meta->tooltip[1] ) ? $meta->tooltip[1] : false;
		$tooltip_val = isset( $meta->tooltip[1]->value[0]->desktop->value ) ? $meta->tooltip[1]->value[0]->desktop->value : false;

		if ( ! $texts ) {
			return __( 'You have not added button text yet.', 'super-button' );
		}
		if ( ! $css ) {
			return __( 'You have not yet saved button values or the button does not exist.', 'super-button' );
		}
		$text_output = '';
		if ( isset( $texts->value ) && is_array( $texts->value ) ) {
			foreach ( $texts->value as $key => $text ) {
				foreach ( $text as $state => $text_state ) {
					if ( ! empty( $text_state->value ) ) {
						$text_output .= "<span id='super-button__text{$key}__{$state}' class='super-button__text super-button__text{$key}__{$state} super-button__text__{$state}'>{$text_state->value}</span>";
					}
				}
			}
		}

		if ( isset( $classes ) ) {
			foreach ( $classes as $key => $option ) {
				if ( is_object( $option->value[0]->desktop->value ) ) {
					$sb_classes .= $option->value[0]->desktop->value->value;
				} else {
					$sb_classes .= $option->value[0]->desktop->value;
				}
			}
		}

		$attributes = '';

		foreach ($meta as $key => $option) {
			$property = isset( $option[1]->property ) ? $option[1]->property : false;
			if( $property === 'attribute' ) {
				$attribute = isset( $option[1]->attribute ) ? $option[1]->attribute : false;
				$value = isset( $option[1]->value[0]->desktop->value ) ? $option[1]->value[0]->desktop->value : false;
				if ($attribute) {
					$attributes .= 'data-' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $attribute)) . '="' . esc_attr($value) . '"';
				}
			}
		}

		return "<style>{$css}</style><a id='super-button-{$atts['id']}' class='super-button" . esc_attr( $sb_classes ) . "' target='" . esc_attr( $link_target ) . "' href='" . esc_url( $link ) . "' {$attributes}>{$text_output}</a>";
	}

	return __( 'Button does not exist. Please remove button short code.', 'super-button' );
}
add_shortcode( 'super_button', 'super_buttons_short_code_callback' );
