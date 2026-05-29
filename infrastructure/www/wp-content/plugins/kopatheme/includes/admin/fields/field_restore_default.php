<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Restore Default
 * 
 * @author kopatheme <http://kopatheme.com>
 * @see Kopa_Admin_Settings:reset_fields()
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function kopa_form_field_restore_default( $wrap_start, $wrap_end, $settings, $value ) {
	$output = $wrap_start;

	foreach ( $settings['options'] as $key => $val ) {
		$output .= '<label><input value="' . esc_attr( $key ) . '" type="radio" name="kopa_' . esc_attr( $settings['type'] ) . '" ' . checked( $settings['default'], $key, false ) . '> ' . esc_html( $val ) . '</label><br>';
	}

	$output .= '<input type="submit" class="button-secondary kopa_reset" name="kopa_reset" value="' . esc_attr__( 'Restore Defaults', 'kopa-framework' ) . '">';

	$output .= $wrap_end;
	return $output;
}
