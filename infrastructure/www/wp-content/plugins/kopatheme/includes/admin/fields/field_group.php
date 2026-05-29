<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Group Start
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
function kopa_form_field_groupstart( $wrap_start, $wrap_end, $settings, $value ) {

	$output = '<div id="kopa_section_' . esc_attr( $settings['id'] ) . '" class="kopa_section_group">';

	if ( $settings['title'] ) {
		$output .= '<h4 class="kopa_heading_group">' . esc_html( $settings['title'] ) . '</h4>';
	}

	if ( isset( $settings['desc'] ) && !empty( $settings['desc'] ) ) {
		$output .= sprintf( '<div class="kopa_group_description"><p>%s</p></div>', $settings['desc'] );
	}

	$output .= '<div class="kopa_group_content">';

	return $output;
}

/**
 * Group End
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
function kopa_form_field_groupend( $wrap_start, $wrap_end, $settings, $value ) {
	return '</div></div>';
}
