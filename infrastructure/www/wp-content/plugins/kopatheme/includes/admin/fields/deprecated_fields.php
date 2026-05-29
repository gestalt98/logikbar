<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * KopaFramework deprecated fields.
 *
 * @author 		vutuansw
 * @category 	Fields
 * @package 	KopaFramework/Admin
 * @since       1.1.9
 */

/**
 * Gallery
 * This field is deprecated, use 'gallery_sortable' instead.
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.11
 * @deprecated since 1.1.9
 * @return string - html string.
 */
function kopa_form_field_gallery( $wrap_start, $wrap_end, $settings, $value ) {
	$output = $wrap_start;
	$output .= '<div class="kopa-framework-gallery-box">';
	$output .= '<input
										class="medium-text kopa-framework-gallery"
										type="text"
										name="' . esc_attr( $settings['id'] ) . '"
										id="' . esc_attr( $settings['id'] ) . '"
										value="' . esc_attr( $value ) . '"
										autocomplete="off">';

	$output .= '<a href="#" class="kopa-framework-gallery-config button button-secondary">' . esc_html__( 'Select images', 'kopa-framework' ) . '</a>';
	$output .= '</div>';
	$output .= $wrap_end;
	
	return $output;
}



