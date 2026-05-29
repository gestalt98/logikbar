<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Color Picker
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.11
 * @return string - html string.
 */
function kopa_form_field_color( $wrap_start, $wrap_end, $settings, $value ) {
	$default_color = '';
	$field_class = '';
	
	if ( 'color' === $settings['type'] ) {
		$field_class = ' kopa_color';

		if ( $settings['default'] ) {
			$default_color = ' data-default-color="' . esc_attr( $settings['default'] ) . '" ';
		} // end check empty option value
	} // end check color type

	$output = $wrap_start;
	$output .= '<input
										class="' . esc_attr( $field_class ) . '"
										style="' . esc_attr( $settings['css'] ) . '"
										type="' . esc_attr( $settings['type'] ) . '"
										name="' . esc_attr( $settings['id'] ) . '"
										id="' . esc_attr( $settings['id'] ) . '"
										value="' . esc_attr( $value ) . '"' .
			$default_color . '>';
	$output .= $wrap_end;

	return $output;
}