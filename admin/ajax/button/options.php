<?php
/**
 * Ajax call for button options
 *
 * @package Super Buttons
 */

/**
 * Get button options
 * Requires button id to send along post request
 * Optional button type to send along post request
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.0
 */
function super_buttons_get_options() {
	// security check.
	check_ajax_referer( 'super-buttons-admin', 'security' );
	// check button id.
	if ( ! isset( $_POST['id'] ) ) {
		return;
	}
	// set variables.
	$table_name = SUPER_BUTTONS__TABLE_NAME . '_meta';
	$id         = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : false;
	if ( $id ) {
		// check if we already have option values stored in db.
		global $wpdb;

		$saved_options = array();
		$results       = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE button_id = %d",
			$id
		), ARRAY_A ); // db call ok; no-cache ok. WPCS: unprepared SQL OK.

		foreach ( $results as $key => $option ) {
			$saved_options[ $option['meta_key'] ] = $option['value']; // WPCS: slow query ok.
		}
		// if button type defined, else default to normal.
		$type = 'normal';
		// default options.
		$options = array(
			'dimensions' => array(
				array(
					'type'  => 'label',
					'label' => __( 'Dimensions', 'super-buttons' ),
				),
				array(
					'id'          => 'width',
					'name'        => 'width',
					'label'       => __( 'Width', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => __( '100', 'super-buttons' ),
					'value'       => isset( $saved_options['width'] ) ? maybe_unserialize( $saved_options['width'] ) : array(
						array(
							'desktop' => array(
								'value' => 100,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 100,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 100,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'width',
					'state'       => 'normal',
				),
				array(
					'id'          => 'height',
					'name'        => 'height',
					'label'       => __( 'Height', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => __( '40', 'super-buttons' ),
					'value'       => isset( $saved_options['height'] ) ? maybe_unserialize( $saved_options['height'] ) : array(
						array(
							'desktop' => array(
								'value' => 40,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 40,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 40,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array(
						'#super-button-' . $id,
					),
					'property'    => 'height',
					'state'       => 'normal',
				),
				array(
					'id'    => 'margin_heading',
					'label' => __( 'Margin', 'super-buttons' ),
					'type'  => 'title',
				),
				array(
					'id'          => 'margin-top',
					'name'        => 'marginTop',
					'label'       => __( 'Top', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['margin-top'] ) ? maybe_unserialize( $saved_options['margin-top'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'margin-top',
				),
				array(
					'id'          => 'margin-bottom',
					'name'        => 'marginBottom',
					'label'       => __( 'Bottom', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['margin-bottom'] ) ? maybe_unserialize( $saved_options['margin-bottom'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'margin-bottom',
				),
				array(
					'id'          => 'margin-left',
					'name'        => 'marginLeft',
					'label'       => __( 'Left', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['margin-left'] ) ? maybe_unserialize( $saved_options['margin-left'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'margin-left',
				),
				array(
					'id'          => 'margin-right',
					'name'        => 'marginRight',
					'label'       => __( 'Right', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['margin-right'] ) ? maybe_unserialize( $saved_options['margin-right'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'margin-right',
				),
				array(
					'id'    => 'padding_heading',
					'label' => __( 'Padding', 'super-buttons' ),
					'type'  => 'title',
				),
				array(
					'id'          => 'padding-top',
					'name'        => 'paddingTop',
					'label'       => __( 'Top', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['padding-top'] ) ? maybe_unserialize( $saved_options['padding-top'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'padding-top',
				),
				array(
					'id'          => 'padding-bottom',
					'name'        => 'paddingBottom',
					'label'       => __( 'Bottom', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['padding-bottom'] ) ? maybe_unserialize( $saved_options['padding-bottom'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'padding-bottom',
				),
				array(
					'id'          => 'padding-left',
					'name'        => 'paddingLeft',
					'label'       => __( 'Left', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['padding-left'] ) ? maybe_unserialize( $saved_options['padding-left'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'padding-left',
				),
				array(
					'id'          => 'padding-right',
					'name'        => 'paddingRight',
					'label'       => __( 'Right', 'super-buttons' ),
					'type'        => 'number',
					'class'       => '',
					'placeholder' => 0,
					'value'       => isset( $saved_options['padding-right'] ) ? maybe_unserialize( $saved_options['padding-right'] ) : array(
						array(
							'desktop' => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => 0,
								'unit'  => 'px',
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'min'         => 0,
					'max'         => 1000,
					'step'        => 1,
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'padding-right',
				),
			),
			'background' => array(
				array(
					'type'  => 'label',
					'label' => __( 'Background', 'super-buttons' ),
				),
				array(
					'id'    => 'background_color_heading',
					'label' => __( 'Color', 'super-buttons' ),
					'type'  => 'title',
				),
				array(
					'id'          => 'background_color_normal',
					'name'        => 'background_color_normal',
					'label'       => __( 'Normal', 'super-buttons' ),
					'type'        => 'color',
					'class'       => '',
					'placeholder' => __( 'Select background color.', 'super-buttons' ),
					'value'       => isset( $saved_options['background_color_normal'] ) ? maybe_unserialize( $saved_options['background_color_normal'] ) : array(
						array(
							'desktop' => array(
								'value' => '#eaeaea',
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => '#eaeaea',
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => '#eaeaea',
								'unit'  => null,
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'background-color',
				),
				array(
					'id'          => 'background_color_hover',
					'name'        => 'background_color_hover',
					'label'       => __( 'Hover', 'super-buttons' ),
					'type'        => 'color',
					'class'       => '',
					'placeholder' => __( 'Select background color for when button is hovered.', 'super-buttons' ),
					'value'       => isset( $saved_options['background_color_hover'] ) ? maybe_unserialize( $saved_options['background_color_hover'] ) : array(
						array(
							'desktop' => array(
								'value' => '#eaeaea',
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => '#eaeaea',
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => '#eaeaea',
								'unit'  => null,
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => array( '#super-button-' . $id . ':hover' ),
					'property'    => 'background-color',
				),
				array(
					'id'    => 'background_image_heading',
					'label' => __( 'Image', 'super-buttons' ),
					'type'  => 'title',
				),
				array(
					'id'          => 'background_image_normal',
					'name'        => 'background_image_normal',
					'label'       => __( 'Normal', 'super-buttons' ),
					'type'        => 'image',
					'class'       => '',
					'placeholder' => __( 'Select background image.', 'super-buttons' ),
					'value'       => isset( $saved_options['background_image_normal'] ) ? maybe_unserialize( $saved_options['background_image_normal'] ) : array(
						array(
							'desktop' => array(
								'value' => '',
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => '',
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => '',
								'unit'  => null,
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'background-image',
				),
				array(
					'id'          => 'background_image_hover',
					'name'        => 'background_image_hover',
					'label'       => __( 'Hover', 'super-buttons' ),
					'type'        => 'image',
					'class'       => '',
					'placeholder' => __( 'Select background image for hover view.', 'super-buttons' ),
					'value'       => isset( $saved_options['background_image_hover'] ) ? maybe_unserialize( $saved_options['background_image_hover'] ) : array(
						array(
							'desktop' => array(
								'value' => '',
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => '',
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => '',
								'unit'  => null,
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => array( '#super-button-' . $id . ':hover' ),
					'property'    => 'background-image',
				),
				array(
					'id'          => 'background_image_repeat',
					'name'        => 'background_image_repeat',
					'label'       => __( 'Repeat', 'super-buttons' ),
					'type'        => 'select',
					'class'       => '',
					'placeholder' => __( 'Select whether the background image should repeat.', 'super-buttons' ),
					'options'     => array(
						array(
							'label' => __( 'No Repeat', 'super-buttons' ),
							'value' => 'no-repeat',
						),
						array(
							'label' => __( 'Repeat Horizontally', 'super-buttons' ),
							'value' => 'repeat-x',
						),
						array(
							'label' => __( 'Repeat Vertically', 'super-buttons' ),
							'value' => 'repeat-y',
						),
						array(
							'label' => __( 'Repeat', 'super-buttons' ),
							'value' => 'repeat',
						),
						array(
							'label' => __( 'Space', 'super-buttons' ),
							'value' => 'space',
						),
						array(
							'label' => __( 'Round', 'super-buttons' ),
							'value' => 'round',
						),
					),
					'value'       => isset( $saved_options['background_image_repeat'] ) ? maybe_unserialize( $saved_options['background_image_repeat'] ) : array(
						array(
							'desktop' => array(
								'value' => array(
									'label' => __( 'No Repeat', 'super-buttons' ),
									'value' => 'no-repeat',
								),
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => array(
									'label' => __( 'No Repeat', 'super-buttons' ),
									'value' => 'no-repeat',
								),
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => array(
									'label' => __( 'No Repeat', 'super-buttons' ),
									'value' => 'no-repeat',
								),
								'unit'  => null,
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'background-repeat',
				),
				array(
					'id'          => 'background_image_position',
					'name'        => 'background_image_position',
					'label'       => __( 'Position', 'super-buttons' ),
					'type'        => 'select',
					'class'       => '',
					'placeholder' => __( 'Select background image position.', 'super-buttons' ),
					'options'     => array(
						array(
							'label' => __( 'Initial', 'super-buttons' ),
							'value' => 'initial',
						),
						array(
							'label' => __( 'Left Top', 'super-buttons' ),
							'value' => 'left top',
						),
						array(
							'label' => __( 'Left Center', 'super-buttons' ),
							'value' => 'left center',
						),
						array(
							'label' => __( 'Left Bottom', 'super-buttons' ),
							'value' => 'left bottom',
						),
						array(
							'label' => __( 'Right Top', 'super-buttons' ),
							'value' => 'right top',
						),
						array(
							'label' => __( 'Right Center', 'super-buttons' ),
							'value' => 'right center',
						),
						array(
							'label' => __( 'Right Bottom', 'super-buttons' ),
							'value' => 'right bottom',
						),
						array(
							'label' => __( 'Center Top', 'super-buttons' ),
							'value' => 'center top',
						),
						array(
							'label' => __( 'Center Center', 'super-buttons' ),
							'value' => 'center center',
						),
						array(
							'label' => __( 'Center Bottom', 'super-buttons' ),
							'value' => 'center bottom',
						),
					),
					'value'       => isset( $saved_options['background_image_position'] ) ? maybe_unserialize( $saved_options['background_image_position'] ) : array(
						array(
							'desktop' => array(
								'value' => array(
									'label' => __( 'Initial', 'super-buttons' ),
									'value' => 'initial',
								),
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => array(
									'label' => __( 'Initial', 'super-buttons' ),
									'value' => 'initial',
								),
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => array(
									'label' => __( 'Initial', 'super-buttons' ),
									'value' => 'initial',
								),
								'unit'  => null,
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => array( '#super-button-' . $id ),
					'property'    => 'background-position',
				),
			),
			'text'       => array(
				array(
					'type'  => 'label',
					'label' => __( 'Text', 'super-buttons' ),
				),
				array(
					'id'          => 'text',
					'name'        => 'text',
					'label'       => __( 'Text', 'super-buttons' ),
					'type'        => 'text',
					'class'       => '',
					'placeholder' => __( 'Change button text', 'super-buttons' ),
					'value'       => isset( $saved_options['text'] ) ? maybe_unserialize( $saved_options['text'] ) : array(
						array(
							'desktop' => array(
								'value' => __( 'Default', 'super-buttons' ),
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => __( 'Default', 'super-buttons' ),
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => __( 'Default', 'super-buttons' ),
								'unit'  => null,
							),
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => null,
					'property'    => null,
					'multiple'    => true,
					'children'    => array(
						array(
							'id'          => 'text-font-family',
							'name'        => 'text-font-family',
							'label'       => __( 'Font Family', 'super-buttons' ),
							'type'        => 'select',
							'class'       => '',
							'placeholder' => __( 'Inherit', 'super-buttons' ),
							'options'     => super_buttons_get_fonts(),
							'value'       => isset( $saved_options['text-font-family'] ) ? maybe_unserialize( $saved_options['text-font-family'] ) : array(
								array(
									'desktop' => array(
										'value' => 'inherit',
										'unit'  => false,
									),
									'tablet'  => array(
										'value' => 'inherit',
										'unit'  => false,
									),
									'mobile'  => array(
										'value' => 'inherit',
										'unit'  => false,
									),
								),
							),
							'width'       => '1-1',
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'font-family',
							'multiple'    => true,
						),
						array(
							'id'          => 'font-weight',
							'name'        => 'font-weight',
							'label'       => __( 'Font Weight', 'super-buttons' ),
							'type'        => 'select',
							'class'       => '',
							'placeholder' => __( 'Select font weight', 'super-buttons' ),
							'options'     => array(
								array(
									'label' => '100',
									'value' => '100',
								),
								array(
									'label' => '200',
									'value' => '200',
								),
								array(
									'label' => '300',
									'value' => '300',
								),
								array(
									'label' => '400',
									'value' => '400',
								),
								array(
									'label' => '500',
									'value' => '500',
								),
								array(
									'label' => '600',
									'value' => '600',
								),
								array(
									'label' => '700',
									'value' => '700',
								),
								array(
									'label' => '800',
									'value' => '800',
								),
								array(
									'label' => '900',
									'value' => '900',
								),
							),
							'value'       => isset( $saved_options['font-weight'] ) ? maybe_unserialize( $saved_options['font-weight'] ) : array(
								array(
									'desktop' => array(
										'value' => array(
											'label' => '400',
											'value' => '400',
										),
										'unit'  => null,
									),
									'tablet'  => array(
										'value' => array(
											'label' => '400',
											'value' => '400',
										),
										'unit'  => null,
									),
									'mobile'  => array(
										'value' => array(
											'label' => '400',
											'value' => '400',
										),
										'unit'  => null,
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'font-weight',
							'multiple'    => true,
						),
						array(
							'id'          => 'text-font-size',
							'name'        => 'text-font-size',
							'label'       => __( 'Size', 'super-buttons' ),
							'type'        => 'number',
							'class'       => '',
							'placeholder' => 15,
							'value'       => isset( $saved_options['text-font-size'] ) ? maybe_unserialize( $saved_options['text-font-size'] ) : array(
								array(
									'desktop' => array(
										'value' => 15,
										'unit'  => 'px',
									),
									'tablet'  => array(
										'value' => 15,
										'unit'  => 'px',
									),
									'mobile'  => array(
										'value' => 15,
										'unit'  => 'px',
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'min'         => 0,
							'max'         => 1000,
							'step'        => 1,
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'font-size',
						),
						array(
							'id'          => 'text-line-height',
							'name'        => 'text-line-height',
							'label'       => __( 'Vertical Space', 'super-buttons' ),
							'type'        => 'number',
							'class'       => '',
							'placeholder' => 40,
							'value'       => isset( $saved_options['text-line-height'] ) ? maybe_unserialize( $saved_options['text-line-height'] ) : array(
								array(
									'desktop' => array(
										'value' => 40,
										'unit'  => 'px',
									),
									'tablet'  => array(
										'value' => 40,
										'unit'  => 'px',
									),
									'mobile'  => array(
										'value' => 40,
										'unit'  => 'px',
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'min'         => 0,
							'max'         => 1000,
							'step'        => 1,
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'line-height',
							'multiple'    => true,
						),
						array(
							'id'          => 'text-transform',
							'name'        => 'text-transform',
							'label'       => __( 'Transform', 'super-buttons' ),
							'type'        => 'select',
							'class'       => '',
							'placeholder' => __( 'Transform button text', 'super-buttons' ),
							'options'     => array(
								array(
									'label' => __( 'None', 'super-buttons' ),
									'value' => 'none',
								),
								array(
									'label' => __( 'Capitalize', 'super-buttons' ),
									'value' => 'capitalize',
								),
								array(
									'label' => __( 'Uppercase', 'super-buttons' ),
									'value' => 'uppercase',
								),
								array(
									'label' => __( 'Lowercase', 'super-buttons' ),
									'value' => 'lowercase',
								),
							),
							'value'       => isset( $saved_options['text-transform'] ) ? maybe_unserialize( $saved_options['text-transform'] ) : array(
								array(
									'desktop' => array(
										'value' => array(
											'label' => __( 'None', 'super-buttons' ),
											'value' => 'none',
										),
										'unit'  => null,
									),
									'tablet'  => array(
										'value' => array(
											'label' => __( 'None', 'super-buttons' ),
											'value' => 'none',
										),
										'unit'  => null,
									),
									'mobile'  => array(
										'value' => array(
											'label' => __( 'None', 'super-buttons' ),
											'value' => 'none',
										),
										'unit'  => null,
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'text-transform',
							'multiple'    => true,
						),
						array(
							'id'          => 'text-alignment',
							'name'        => 'text-alignment',
							'label'       => __( 'Alignment', 'super-buttons' ),
							'type'        => 'select',
							'class'       => '',
							'placeholder' => __( 'Align button text', 'super-buttons' ),
							'options'     => array(
								array(
									'label' => __( 'Center', 'super-buttons' ),
									'value' => 'center',
								),
								array(
									'label' => __( 'Left', 'super-buttons' ),
									'value' => 'left',
								),
								array(
									'label' => __( 'Right', 'super-buttons' ),
									'value' => 'right',
								),
							),
							'value'       => isset( $saved_options['text-alignment'] ) ? maybe_unserialize( $saved_options['text-alignment'] ) : array(
								array(
									'desktop' => array(
										'value' => array(
											'label' => __( 'Center', 'super-buttons' ),
											'value' => 'center',
										),
										'unit'  => null,
									),
									'tablet'  => array(
										'value' => array(
											'label' => __( 'Center', 'super-buttons' ),
											'value' => 'center',
										),
										'unit'  => null,
									),
									'mobile'  => array(
										'value' => array(
											'label' => __( 'Center', 'super-buttons' ),
											'value' => 'center',
										),
										'unit'  => null,
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'text-align',
							'multiple'    => true,
						),
						array(
							'id'          => 'text-position',
							'name'        => 'text-position',
							'label'       => __( 'Position', 'super-buttons' ),
							'type'        => 'select',
							'class'       => '',
							'placeholder' => __( 'Select text position', 'super-buttons' ),
							'options'     => array(
								array(
									'label' => __( 'Same line', 'super-buttons' ),
									'value' => 'inline',
								),
								array(
									'label' => __( 'New line', 'super-buttons' ),
									'value' => 'block',
								),
							),
							'value'       => isset( $saved_options['text-position'] ) ? maybe_unserialize( $saved_options['text-position'] ) : array(
								array(
									'desktop' => array(
										'value' => array(
											'label' => 'New line',
											'value' => 'block',
										),
										'unit'  => null,
									),
									'tablet'  => array(
										'value' => array(
											'label' => 'New line',
											'value' => 'block',
										),
										'unit'  => null,
									),
									'mobile'  => array(
										'value' => array(
											'label' => 'New line',
											'value' => 'block',
										),
										'unit'  => null,
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'display',
							'multiple'    => true,
						),
						array(
							'id'    => 'text_color_heading',
							'label' => __( 'Color', 'super-buttons' ),
							'type'  => 'title',
						),
						array(
							'id'          => 'text_color_normal',
							'name'        => 'text_color_normal',
							'label'       => __( 'Normal', 'super-buttons' ),
							'type'        => 'color',
							'class'       => '',
							'placeholder' => __( 'Select text color.', 'super-buttons' ),
							'value'       => isset( $saved_options['text_color_normal'] ) ? maybe_unserialize( $saved_options['text_color_normal'] ) : array(
								array(
									'desktop' => array(
										'value' => '#2d2d2d',
										'unit'  => null,
									),
									'tablet'  => array(
										'value' => '#2d2d2d',
										'unit'  => null,
									),
									'mobile'  => array(
										'value' => '#2d2d2d',
										'unit'  => null,
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'target'      => array( '#super-button-' . $id, '.super-button__text' ),
							'property'    => 'color',
							'multiple'    => true,
						),
						array(
							'id'          => 'text_color_hover',
							'name'        => 'text_color_hover',
							'label'       => __( 'Hover', 'super-buttons' ),
							'type'        => 'color',
							'class'       => '',
							'placeholder' => __( 'Select text color.', 'super-buttons' ),
							'value'       => isset( $saved_options['text_color_hover'] ) ? maybe_unserialize( $saved_options['text_color_hover'] ) : array(
								array(
									'desktop' => array(
										'value' => '#2d2d2d',
										'unit'  => null,
									),
									'tablet'  => array(
										'value' => '#2d2d2d',
										'unit'  => null,
									),
									'mobile'  => array(
										'value' => '#2d2d2d',
										'unit'  => null,
									),
								),
							),
							'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
							'target'      => array( '#super-button-' . $id . ':hover', '.super-button__text' ),
							'property'    => 'color',
							'multiple'    => true,
						),
					),
				),
			),
			'link'       => array(
				array(
					'type'  => 'label',
					'label' => __( 'Link', 'super-buttons' ),
				),
				array(
					'id'          => 'link_url',
					'name'        => 'link_url',
					'label'       => __( 'URL', 'super-buttons' ),
					'type'        => 'text',
					'class'       => '',
					'placeholder' => __( 'Insert URL.', 'super-buttons' ),
					'value'       => isset( $saved_options['link_url'] ) ? maybe_unserialize( $saved_options['link_url'] ) : array(
						array(
							'desktop' => array(
								'value' => '#',
								'unit'  => null,
							),
							'tablet'  => false,
							'mobile'  => false,
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => null,
					'property'    => null,
					'multiple'    => false,
				),
				array(
					'id'          => 'link_target',
					'name'        => 'link_target',
					'label'       => __( 'Open link in', 'super-buttons' ),
					'type'        => 'select',
					'class'       => '',
					'placeholder' => __( 'Insert URL.', 'super-buttons' ),
					'value'       => isset( $saved_options['link_target'] ) ? maybe_unserialize( $saved_options['link_target'] ) : array(
						array(
							'desktop' => array(
								'value' => 'url',
								'unit'  => null,
							),
							'tablet'  => false,
							'mobile'  => false,
						),
					),
					'options'     => array(
						array(
							'label' => __( 'Same Window', 'super-buttons' ),
							'value' => '_self',
						),
						array(
							'label' => __( 'New Window', 'super-buttons' ),
							'value' => '_blank',
						),
					),
					'width'       => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'      => null,
					'property'    => null,
					'multiple'    => false,
				),
			),
			'border'     => array(
				array(
					'type'  => 'label',
					'label' => __( 'Border', 'super-buttons' ),
				),
				array(
					'id'       => 'border',
					'name'     => 'border',
					'label'    => __( 'Border', 'super-buttons' ),
					'type'     => 'border',
					'class'    => '',
					'value'    => isset( $saved_options['border'] ) ? maybe_unserialize( $saved_options['border'] ) : array(
						array(
							'desktop' => array(
								'value' => array(
									'size'  => 0,
									'style' => array(
										'label' => __( 'Solid', 'super-buttons' ),
										'value' => 'solid',
									),
									'color' => '#eaeaea',
									'sides' => array(
										array(
											'label' => __( 'Top', 'super-buttons' ),
											'value' => 'top',
										),
										array(
											'label' => __( 'Bottom', 'super-buttons' ),
											'value' => 'bottom',
										),
										array(
											'label' => __( 'Left', 'super-buttons' ),
											'value' => 'left',
										),
										array(
											'label' => __( 'Right', 'super-buttons' ),
											'value' => 'right',
										),
									),
								),
								'unit'  => 'px',
							),
							'tablet'  => array(
								'value' => array(
									'size'  => 0,
									'style' => array(
										'label' => __( 'Solid', 'super-buttons' ),
										'value' => 'solid',
									),
									'color' => '#eaeaea',
									'sides' => array(
										array(
											'label' => __( 'Top', 'super-buttons' ),
											'value' => 'top',
										),
										array(
											'label' => __( 'Bottom', 'super-buttons' ),
											'value' => 'bottom',
										),
										array(
											'label' => __( 'Left', 'super-buttons' ),
											'value' => 'left',
										),
										array(
											'label' => __( 'Right', 'super-buttons' ),
											'value' => 'right',
										),
									),
								),
								'unit'  => 'px',
							),
							'mobile'  => array(
								'value' => array(
									'size'  => 0,
									'style' => array(
										'label' => __( 'Solid', 'super-buttons' ),
										'value' => 'solid',
									),
									'color' => '#eaeaea',
									'sides' => array(
										array(
											'label' => __( 'Top', 'super-buttons' ),
											'value' => 'top',
										),
										array(
											'label' => __( 'Bottom', 'super-buttons' ),
											'value' => 'bottom',
										),
										array(
											'label' => __( 'Left', 'super-buttons' ),
											'value' => 'left',
										),
										array(
											'label' => __( 'Right', 'super-buttons' ),
											'value' => 'right',
										),
									),
								),
								'unit'  => 'px',
							),
						),
					),
					'target'   => array( '#super-button-' . $id ),
					'property' => 'border',
					'multiple' => true,
					'options'  => array(
						'style' => array(
							array(
								'label' => __( 'None', 'super-buttons' ),
								'value' => 'none',
							),
							array(
								'label' => __( 'Dotted', 'super-buttons' ),
								'value' => 'dotted',
							),
							array(
								'label' => __( 'Dashed', 'super-buttons' ),
								'value' => 'dashed',
							),
							array(
								'label' => __( 'Solid', 'super-buttons' ),
								'value' => 'solid',
							),
							array(
								'label' => __( 'Double', 'super-buttons' ),
								'value' => 'double',
							),
							array(
								'label' => __( 'Groove', 'super-buttons' ),
								'value' => 'groove',
							),
							array(
								'label' => __( 'Ridge', 'super-buttons' ),
								'value' => 'ridge',
							),
						),
						'sides' => array(
							array(
								'label' => __( 'Top', 'super-buttons' ),
								'value' => 'top',
							),
							array(
								'label' => __( 'Bottom', 'super-buttons' ),
								'value' => 'bottom',
							),
							array(
								'label' => __( 'Left', 'super-buttons' ),
								'value' => 'left',
							),
							array(
								'label' => __( 'Right', 'super-buttons' ),
								'value' => 'right',
							),
						),
					),
					'unit'     => 'px',
				),
			),
			'corners'    => array(
				array(
					'type'  => 'label',
					'label' => __( 'Corners', 'super-buttons' ),
				),
				array(
					'id'       => 'border-radius',
					'name'     => 'border-radius',
					'label'    => __( 'Rounded Corners', 'super-buttons' ),
					'type'     => 'borderRadius',
					'class'    => '',
					'value'    => isset( $saved_options['border-radius'] ) ? maybe_unserialize( $saved_options['border-radius'] ) : array(
						array(
							'desktop' => array(
								'value' => array(
									'size'   => 0,
									'sides'  => array(
										array(
											'label' => __( 'Top Left', 'super-buttons' ),
											'value' => 'top-left',
										),
										array(
											'label' => __( 'Top Right', 'super-buttons' ),
											'value' => 'top-right',
										),
										array(
											'label' => __( 'Bottom Left', 'super-buttons' ),
											'value' => 'bottom-left',
										),
										array(
											'label' => __( 'Bottom Right', 'super-buttons' ),
											'value' => 'bottom-right',
										),
									),
									'states' => array(
										array(
											'label' => __( 'Normal', 'super-buttons' ),
											'value' => 'normal',
										),
										array(
											'label' => __( 'Hover', 'super-buttons' ),
											'value' => 'hover',
										),
									),
								),
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => array(
									'size'   => 0,
									'sides'  => array(
										array(
											'label' => __( 'Top Left', 'super-buttons' ),
											'value' => 'top-left',
										),
										array(
											'label' => __( 'Top Right', 'super-buttons' ),
											'value' => 'top-right',
										),
										array(
											'label' => __( 'Bottom Left', 'super-buttons' ),
											'value' => 'bottom-left',
										),
										array(
											'label' => __( 'Bottom Right', 'super-buttons' ),
											'value' => 'bottom-right',
										),
									),
									'states' => array(
										array(
											'label' => __( 'Normal', 'super-buttons' ),
											'value' => 'normal',
										),
										array(
											'label' => __( 'Hover', 'super-buttons' ),
											'value' => 'hover',
										),
									),
								),
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => array(
									'size'   => 0,
									'sides'  => array(
										array(
											'label' => __( 'Top Left', 'super-buttons' ),
											'value' => 'top-left',
										),
										array(
											'label' => __( 'Top Right', 'super-buttons' ),
											'value' => 'top-right',
										),
										array(
											'label' => __( 'Bottom Left', 'super-buttons' ),
											'value' => 'bottom-left',
										),
										array(
											'label' => __( 'Bottom Right', 'super-buttons' ),
											'value' => 'bottom-right',
										),
									),
									'states' => array(
										array(
											'label' => __( 'Normal', 'super-buttons' ),
											'value' => 'normal',
										),
										array(
											'label' => __( 'Hover', 'super-buttons' ),
											'value' => 'hover',
										),
									),
								),
								'unit'  => null,
							),
						),
					),
					'target'   => array( '#super-button-' . $id ),
					'property' => 'border-radius',
					'multiple' => true,
					'options'  => array(
						'sides'  => array(
							array(
								'label' => __( 'Top Left', 'super-buttons' ),
								'value' => 'top-left',
							),
							array(
								'label' => __( 'Top Right', 'super-buttons' ),
								'value' => 'top-right',
							),
							array(
								'label' => __( 'Bottom Left', 'super-buttons' ),
								'value' => 'bottom-left',
							),
							array(
								'label' => __( 'Bottom Right', 'super-buttons' ),
								'value' => 'bottom-right',
							),
						),
						'states' => array(
							array(
								'label' => __( 'Normal', 'super-buttons' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Hover', 'super-buttons' ),
								'value' => 'hover',
							),
						),
					),
					'state'    => 'options',
				),
			),
			'tooltip'    => array(
				array(
					'type'  => 'label',
					'label' => __( 'Tooltip', 'super-buttons' ),
				),
				array(
					'id'        => 'tooltip_content',
					'name'      => 'tooltip_content',
					'label'     => __( 'Content', 'super-buttons-pro' ),
					'type'      => 'text',
					'class'     => '',
					'value'     => isset( $saved_options['tooltip_content'] ) ? maybe_unserialize( $saved_options['tooltip_content'] ) : array(
						array(
							'desktop' => array(
								'value' => '',
								'unit'  => null,
							),
							'tablet'  => array(
								'value' => '',
								'unit'  => null,
							),
							'mobile'  => array(
								'value' => '',
								'unit'  => null,
							),
						),
					),
					'width'     => '1-1', // 1-1, 1-2, 1-3, 1-4, 1-5, 1-6.
					'target'    => array( '#super-button-' . $id ),
					'property'  => 'attribute',
					'attribute' => 'tippyContent',
				),
			),
		);
		// allow to modify or add extra options.
		$result = apply_filters( 'super_buttons__options', $options, $saved_options, $id );
		// send options as json.
		wp_send_json( $result );
	}
}
add_action( 'wp_ajax_super_buttons_get_options', 'super_buttons_get_options' );
