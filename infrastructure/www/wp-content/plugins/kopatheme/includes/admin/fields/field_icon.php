<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Icon Picker
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.11
 * @return string - html string.
 */
function kopa_form_field_icon( $wrap_start, $wrap_end, $settings, $value ) {
	$output = $wrap_start;
	$output .= '<div class="kopa-icon-picker-wrap clearfix">';
	$output .= '<input type="hidden"
	                            name="' . esc_attr( $settings['id'] ) . '"
	                            id="' . esc_attr( $settings['id'] ) . '"
	                            value="' . esc_attr( $value ) . '"
	                            autocomplete="off"
	                            class="large-text kopa-icon-picker-value"/>';
	$output .= '<span class="kopa-icon-picker-preview"><i class="' . esc_attr( $value ) . '"></i></span>';
	$output .= '<a class="kopa-icon-picker dashicons dashicons-arrow-down" href="#"></a>';
	$output .= '</div>';
	$output .= $wrap_end;

	return $output;
}
