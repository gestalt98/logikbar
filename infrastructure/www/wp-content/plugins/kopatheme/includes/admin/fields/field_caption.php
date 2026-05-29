<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Caption (Title Text)
 * 
 * @author vutuansw
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.2.0
 * @return string - html string.
 */
function kopa_form_field_caption( $wrap_start, $wrap_end, $settings, $value ) {

	$el_class = isset( $settings['class'] ) ? $settings['class'] : '';

	return sprintf( '<div class="kopa-field kopa-field-caption %s"><h4>%s</h4></div>', $el_class, $settings['title'] );
}
