<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Select Font
 * 
 * @author kopatheme <http://kopatheme.com>
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function kopa_form_field_select_font( $wrap_start, $wrap_end, $settings, $value ) {
	$value = wp_parse_args( $value, array(
		'family' => '',
		'style' => '',
		'size' => '',
		'color' => '',
			) );

	$preview = isset( $settings['preview'] ) ? $settings['preview'] : __( 'preview text', 'kopa-framework' );

	$output = $wrap_start;

	// select section
	// font family
	$output .= '<select 
						class="kopa_select_font" 
						id="' . esc_attr( $settings['id'] ) . '" 
						name="' . esc_attr( $settings['id'] ) . '[family]" 
						data-main-id="' . esc_attr( $settings['id'] ) . '">';
	foreach ( $settings['options'] as $key => $val ) {
		// check font groups
		if ( is_array( $val ) ) {
			if ( isset( $settings['groups'][$key] ) ) {
				$output .= '<optgroup label="' . esc_attr( $settings['groups'][$key] ) . '">';
			} // end check group exists
			foreach ( $val as $font_val => $font_label ) {
				$output .= '<option value="' . esc_attr( $font_val ) . '" ' . selected( $font_val, $value['family'], false ) . '>' . $font_label . '</option>';
			}
			if ( isset( $settings['groups'][$key] ) ) {
				$output .= '</optgroup>';
			} // end check group exists
		} else {
			$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value['family'], false ) . '>' . $val . '</option>';
		} // end check font groups
	}
	$output .= '</select>';

	// font weight / style
	$output .= '<select class="kopa_select_font_style" id="' . esc_attr( $settings['id'] ) . '_style" name="' . esc_attr( $settings['id'] ) . '[style]" data-main-id="' . esc_attr( $settings['id'] ) . '">';
	$output .= '<option value="' . esc_attr( $value['style'] ) . '" selected="selected">' . $value['style'] . '</option>';
	$output .= '</select>';

	// font size
	$output .= '<input type="text" class="kopa_select_font_size" name="' . esc_attr( $settings['id'] ) . '[size]" data-main-id="' . esc_attr( $settings['id'] ) . '" value="' . esc_attr( $value['size'] ) . '" placeholder="' . esc_attr__( 'Font size', 'kopa-framework' ) . '">';

	// font color
	$output .= '<input class="kopa_select_font_color" name="' . esc_attr( $settings['id'] ) . '[color]" value="' . esc_attr( $value['color'] ) . '" data-main-id="' . esc_attr( $settings['id'] ) . '" data-default-color="' . esc_attr( $value['color'] ) . '">';

	// preview section
	$output .= '<p 
						class="kopa_google_font_preview" 
						id="' . esc_attr( $settings['id'] ) . '_preview">' . esc_html( $preview ) . '</p>';
	$output .= $wrap_end;

	return $output;
}

/**
 * Custom Font Manager
 * 
 * @author kopatheme <http://kopatheme.com>
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function kopa_form_field_custom_font_manager( $wrap_start, $wrap_end, $settings, $value ) {
	$orders = array();
	$custom_font_attributes = array(
		'name' => array(
			'type' => 'text',
			'placeholder' => __( 'Enter font name', 'kopa-framework' ),
			'required' => false,
		),
		'woff' => array(
			'type' => 'upload',
			'placeholder' => __( 'Upload .woff font file', 'kopa-framework' ),
			'mimes' => 'font/woff',
		),
		'ttf' => array(
			'type' => 'upload',
			'placeholder' => __( 'Upload .ttf font file', 'kopa-framework' ),
			'mimes' => 'font/truetype',
		),
		'eot' => array(
			'type' => 'upload',
			'placeholder' => __( 'Upload .eot font file', 'kopa-framework' ),
			'mimes' => 'font/eot',
		),
		'svg' => array(
			'type' => 'upload',
			'placeholder' => __( 'Upload .svg font file', 'kopa-framework' ),
			'mimes' => 'font/svg',
		),
	);

	$output = $wrap_start;

	$output .= '<div class="kopa_custom_font_list">';

	if ( $value && is_array( $value ) ) {
		foreach ( $value as $font_index => $font_item ) {
			$orders[] = $font_index;

			$output .= '<div class="kopa_custom_font_item">';

			// top
			$output .= '<div class="kopa_custom_font_top">';

			$output .= '<div class="kopa_custom_font_title_action">';
			$output .= '</div>'; // kopa_custom_font_title_action

			$output .= '<div class="kopa_custom_font_title">';
			$output .= '<strong>' . $font_item['name'] . '</strong>';
			$output .= '</div>'; // kopa_custom_font_title

			$output .= '</div>'; // kopa_custom_font_top
			// inside
			$output .= '<div class="kopa_custom_font_inside kopa_hide">';

			foreach ( $custom_font_attributes as $attribute => $attribute_data ) {

				$attribute_classes = 'kopa_custom_font_item_' . $attribute;
				if ( 'upload' === $attribute_data['type'] ) {
					$attribute_classes .= ' kopa_upload';
				}

				$attribute_required = false;
				if ( isset( $attribute_data['required'] ) && $attribute_data['required'] ) {
					$attribute_required = true;
				}

				$attribute_mimes = '';
				if ( isset( $attribute_data['mimes'] ) && $attribute_data['mimes'] ) {
					$attribute_mimes = $attribute_data['mimes'];
				}

				$output .= '<div class="kopa_section"><div class="kopa_controls">';

				$output .= '<input class="' . esc_attr( $attribute_classes ) . '" 
										type="text" 
										name="' . esc_attr( $settings['id'] . '[' . $font_index . '][' . $attribute . ']' ) . '"  
										placeholder="' . esc_attr( $attribute_data['placeholder'] ) . '" ' .
						($attribute_required ? 'required ' : '') .
						'value="' . esc_attr( $font_item[$attribute] ) . '" 
										data-type="' . esc_attr( $attribute_mimes ) . '">';

				// upload button
				if ( 'upload' === $attribute_data['type'] && function_exists( 'wp_enqueue_media' ) ) {
					if ( $font_item[$attribute] == '' ) {
						$output .= '<input class="kopa_upload_button kopa_button button" type="button" value="' . esc_attr__( 'Upload', 'kopa-framework' ) . '">';
					} else {
						$output .= '<input class="kopa_remove_file kopa_button button" type="button" value="' . esc_attr__( 'Remove', 'kopa-framework' ) . '">';
					}
				}

				$output .= '</div></div>'; // kopa_section
			}

			$output .= '<div class="kopa_custom_font_control_actions">';
			$output .= '<a class="kopa_custom_font_remove" href="#">' . esc_html__( 'Delete', 'kopa-framework' ) . '</a>';
			$output .= ' | ';
			$output .= '<a class="kopa_custom_font_close" href="#">' . esc_html__( 'Close', 'kopa-framework' ) . '</a>';
			$output .= '</div>'; // kopa_custom_font_control_actions
			$output .= '</div>'; // kopa_custom_font_inside

			$output .= '</div>'; // kopa_custom_font_item
		}
	}

	$output .= '</div>'; // kopa_custom_font_list
	// get list of font orders
	$data_orders = '';
	if ( $orders ) {
		$data_orders = implode( ',', $orders );
	}

	$output .= '<input class="button kopa_add_font_button" type="button" value="' . esc_attr__( 'Add New Font', 'kopa-framework' ) . '" data-name="' . esc_attr( $settings['id'] ) . '" data-orders="' . esc_attr( $data_orders ) . '">';
	$output .= $wrap_end;

	return $output;
}
