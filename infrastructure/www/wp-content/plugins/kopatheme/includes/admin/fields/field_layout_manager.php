<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Layout Manager
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
function kopa_form_field_layout_manager( $wrap_start, $wrap_end, $settings, $value ) {
	$settings = wp_parse_args( $settings, array(
		'layouts' => array(),
			) );

	global $wp_registered_sidebars;
	$output ='';
	$output .= '<div id="kopa_section_group_' . esc_attr( $settings['id'] ) . '" class="kopa_section_group kopa_section_group_layout">';
	$output .= '<h2 class="kopa_heading_group">' . esc_html( $settings['title'] ) . '</h2>';
	$output .= '<div class="kopa_group_content">';

	// layout images
	foreach ( $settings['layouts'] as $layout_id => $layout_args ) {
		$output .= '<div id="' . esc_attr( $settings['id'] . '_' . $layout_id . '_' . 'image' ) . '" class="kopa_section_layout_image">';
		$output .= '<img src="' . esc_attr( $layout_args['preview'] ) . '" alt="' . esc_attr( $layout_args['title'] ) . '">';
		$output .= '</div>';
	}

	// select layout section
	$output .= '<div id="kopa_section_select_layout_' . esc_attr( $settings['id'] ) . '" class="kopa_section kopa_section_select_layout">';
	$output .= '<h4 class="kopa_heading">' . esc_html__( 'Select layout', 'kopa-framework' ) . '</h4>';
	$output .= '<div class="kopa_option">';
	$output .= '<div class="kopa_controls">';
	$output .= '<select name="' . esc_attr( $settings['id'] ) . '[layout_id]" id="select-layout-' . esc_attr( $settings['id'] ) . '" data-layout-section-id="' . esc_attr( $settings['id'] ) . '">';

	foreach ( $settings['layouts'] as $layout_id => $layout_args ) {
		$selected_layout_id = null;
		if ( isset( $value['layout_id'] ) ) {
			$selected_layout_id = $value['layout_id'];
		}
		$output .= '<option value="' . esc_attr( $layout_id ) . '" ' . selected( $selected_layout_id, $layout_id, false ) . '>' . esc_html( $layout_args['title'] ) . '</option>';
	}

	$output .= '</select>';
	$output .= '</div>'; // kopa_controls
	$output .= '</div>'; // kopa_option
	$output .= '</div>'; // kopa_section_select_layout
	// widget areas
	foreach ( $settings['layouts'] as $layout_id => $layout_args ) {

		$output .= '<div id="' . esc_attr( $settings['id'] . '_' . $layout_id ) . '" class="kopa_section_select_area_container">';

		foreach ( $layout_args['positions'] as $position_index => $position ) {

			$output .= '<div id="kopa_section_select_area_' . esc_attr( $position_index . '_' . $layout_id ) . '" class="kopa_section kopa_section_select_area">';
			$output .= '<h4 class="kopa_heading">' . esc_html( $settings['positions'][$position] ) . '</h4>';
			$output .= '<div class="kopa_option">';
			$output .= '<div class="kopa_controls">';
			$output .= '<select name="' . esc_attr( $settings['id'] ) . '[sidebars][' . esc_attr( $layout_id ) . '][' . $position . ']">';
			$output .= '<option value="">' . esc_html__( '&mdash;Select sidebar&mdash;', 'kopa-framework' ) . '</option>';

			// print all registered sidebars
			foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar_args ) {

				$selected_value = null;
				if ( isset( $value['sidebars'][$layout_id][$position] ) ) {
					$selected_value = $value['sidebars'][$layout_id][$position];
				}

				$output .= '<option value="' . esc_attr( $sidebar_id ) . '" ' . selected( $selected_value, $sidebar_id, false ) . '>' . esc_html( $sidebar_args['name'] ) . '</option>';
			}

			$output .= '</select>';
			$output .= '</div>'; // kopa_controls
			$output .= '</div>'; // kopa_option
			$output .= '</div>'; // kopa_section_select_area
		}

		$output .= '</div>'; // kopa_section_select_area_container
	}

	$output .= '</div>'; // kopa_group_content
	$output .= '</div>'; // kopa_section_group_layout
	
	return $output;
}
