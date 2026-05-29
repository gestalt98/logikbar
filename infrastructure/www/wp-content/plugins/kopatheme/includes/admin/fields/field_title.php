<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Title
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
function kopa_form_field_title( $wrap_start, $wrap_end, $settings, $value ) {
	$settings = wp_parse_args( $settings, array(
		'icon' => ''
			) );
	
	global $kopa_title_counter;
	$kopa_title_counter++;
	$output = '';
	if ( $kopa_title_counter >= 2 ) {
		$output .= '</div>';
	}

	$output .= '<div class="kopa_tab_pane" id="kopa_' . esc_attr( $settings['id'] ) . '">';

	return $output;
}
