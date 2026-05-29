<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Sidebar manager
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
function kopa_form_field_sidebar_manager( $wrap_start, $wrap_end, $settings, $value ) {
	$output = '';
	global $wp_registered_sidebars;

	// hold merge data of registered sidebars may be by the theme
	// and registered sidebars by the sidebar manager
	if ( $value && is_array( $value ) ) {
		$temp_sidebars = wp_parse_args( $wp_registered_sidebars, $value );
	} else {
		$temp_sidebars = $wp_registered_sidebars;
	}

	$sidebar_atts = array(
		'name' => __( 'Name', 'kopa-framework' ),
		'description' => __( 'Description', 'kopa-framework' ),
		'before_widget' => __( 'Before Widget', 'kopa-framework' ),
		'after_widget' => __( 'After Widget', 'kopa-framework' ),
		'before_title' => __( 'Before Title', 'kopa-framework' ),
		'after_title' => __( 'After Title', 'kopa-framework' ),
	);

	$data_register_sidebars = 0;
	$data_register_sidebar_ids = '';

	// if not empty, get the number of register sidebars
	$data_register_sidebars = count( $temp_sidebars );

	// get register sidebar ids
	foreach ( $temp_sidebars as $sidebar_id => $sidebar_args ) {
		$data_register_sidebar_ids .= $sidebar_id . ',';
	}

	$output .= '<div id="kopa_section_' . esc_attr( $settings['id'] ) . '" class="kopa_section kopa_section_' . esc_attr( $settings['type'] ) . '">';

	// add new sidebar section
	$output .= '<div class="kopa_section_add_new_sidebar">';
	$output .= '<h4 class="kopa_heading">' . esc_html( $settings['title'] ) . '</h4>';
	$output .= '<div class="kopa_option">';
	$output .= '<div class="kopa_description">' . esc_html( $settings['description'] ) . '</div>';
	$output .= '<div class="kopa_controls">';
	$output .= '<input type="text" class="kopa_sidebar_add_field" name="sidebar" id="kopa_' . esc_attr( $settings['id'] ) . '_add_field">';
	$output .= '<a class="kopa_sidebar_add_button kopa_button_inactive" href="#" data-registered-sidebars="' . esc_attr( $data_register_sidebars ) . '" data-register-sidebar-ids="' . esc_attr( $data_register_sidebar_ids ) . '" data-name="' . esc_attr( $settings['id'] ) . '" data-container-id="kopa_' . esc_attr( $settings['id'] ) . '">' . esc_html__( 'Add New', 'kopa-framework' ) . '</a>';
	$output .= '</div>'; // kopa_controls
	$output .= '</div>'; // kopa_option
	$output .= '</div>'; // kopa_section_add_new_sidebar
	// sidebar manager
	$output .= '<div class="kopa_section_sidebars">';
	$output .= '<ul id="kopa_' . esc_attr( $settings['id'] ) . '" class="kopa_sidebar_sortable kopa_ui_sortable">';

	if ( $value && is_array( $value ) ) {

		foreach ( $value as $sidebar_id => $sidebar_args ) {

			$sidebar_value = array();

			// get current sidebar arguments
			foreach ( $sidebar_atts as $key => $label ) {
				if ( isset( $value[$sidebar_id][$key] ) ) {
					$sidebar_value[$key] = $value[$sidebar_id][$key];
				} elseif ( isset( $sidebar_args[$key] ) ) {
					$sidebar_value[$key] = $sidebar_args[$key];
					// } elseif( isset( $settings['default_atts'][$key] ) ) {
					// 	$sidebar_value[ $key ] = $settings['default_atts'][ $key ];
				} else {
					$sidebar_value[$key] = '';
				}
			}

			$output .= '<li class="kopa_sidebar">';
			$output .= '<div class="kopa_sidebar_header">';
			$output .= '<div class="kopa_sidebar_title_action"></div>';
			$output .= '<strong>' . esc_html( $sidebar_value['name'] ) . '</strong>';
			$output .= '</div>'; // kopa_sidebar_header

			$output .= '<div class="kopa_sidebar_body">';

			// checkbox folding for advanced settings
			$output .= '<label><input class="kopa_sidebar_advanced_settings" type="checkbox"> ' . esc_html__( 'Advanced Settings', 'kopa-framework' ) . '</label>';

			// print sidebar attribute fields
			foreach ( $sidebar_atts as $key => $label ) {

				$id = 'kopa_' . $sidebar_id . '_' . $key;
				$name = $settings['id'] . '[' . $sidebar_id . ']' . '[' . $key . ']';

				$output .= '<div class="kopa_sidebar_' . esc_attr( $key ) . '">';
				// $output .= '<label for="'.esc_attr( $id ).'">'.$label.'</label>';
				$output .= '<input type="text" class="kopa_sidebar kopa_sidebar_attr" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $sidebar_value[$key] ) . '" placeholder="' . esc_attr( $label ) . '">';
				$output .= '</div>';
			} // end foreach sidebar_atts

			$output .= '<div class="kopa_sidebar_control_actions">';
			$output .= '<a class="kopa_sidebar_delete_button" href="#" data-sidebar-id="' . esc_attr( $sidebar_id ) . '">' . esc_html__( 'Delete', 'kopa-framework' ) . '</a>';
			$output .= ' | ';
			$output .= '<a class="kopa_sidebar_close_button" href="#">' . esc_html__( 'Close', 'kopa-framework' ) . '</a>';
			$output .= '<span class="spinner"></span>';
			$output .= '</div>'; // kopa_sidebar_control_actions

			$output .= '</div>'; // kopa_sidebar_body

			$output .= '</li>';
		} // end foreach sidebar options
	} // end check empty sidebar settings

	$output .= '</ul>';
	$output .= '</div>';

	$output .= '</div>';
	
	return $output;
}
