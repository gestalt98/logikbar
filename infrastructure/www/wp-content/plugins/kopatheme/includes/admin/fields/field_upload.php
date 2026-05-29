<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Upload (Image, file, video,...)
 * 
 * Full media wordpress support
 *
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_upload( $wrap_start, $wrap_end, $settings, $value ) {
	// make sure mimes key is set
	$settings = wp_parse_args( $settings, array(
		'mimes' => '',
			) );

	$output = '<div class="kopa_section">';
	$output .= $wrap_start;

	$output .= '<input type="text" 
							class="large-text kopa_upload" 
							name="' . esc_attr( $settings['id'] ) . '" 
							id="' . esc_attr( $settings['id'] ) . '" 
							value="' . esc_attr( $value ) . '" 
							data-type="' . esc_attr( $settings['mimes'] ) . '">';

	$output .= '<br>';

	if ( function_exists( 'wp_enqueue_media' ) ) {
		if ( $value == '' ) {
			$output .= '<a style="margin-top: 3px" class="kopa_upload_button button">' . esc_html__( 'Upload', 'kopa-framework' ) . '</a>';
		} else {
			$output .= '<a style="margin-top: 3px" class="kopa_remove_file button">' . esc_html__( 'Remove', 'kopa-framework' ) . '</a>';
		}
	} else {
		$output .= '<small class="kopa_upload_notice">' . esc_html__( 'Upgrade your version of WordPress for full media support.', 'kopa-framework' ) . '</small>';
	}

	$output .= '<p class="kopa_screenshot">';

	if ( $value ) {
		$remove = '<a class="button kopa_remove_image">' . esc_html__( 'Remove', 'kopa-framework' ) . '</a>';
		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
		if ( $image ) {
			$output .= '<img style="max-width: 300px" src="' . esc_attr( $value ) . '" alt=""><br>' . $remove;
		}
	}

	$output .= '</p>';
	$output .= '</div>';

	$output .= $wrap_end;

	return $output;
}
