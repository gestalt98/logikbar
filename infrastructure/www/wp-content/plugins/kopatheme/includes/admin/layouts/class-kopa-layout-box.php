<?php
/**
 * Kopa Framework Custom Layout Abstract Class
 *
 * This module allows you to define a custom layout for post types or taxonomies
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Layout_Box' ) ) {

/**
 * Kopa_Layout_Box
 */
class Kopa_Layout_Box {

	/**
	 * @access protected
	 * @var array Layout settings property
	 *
	 * array(
	 *     'screen'   => post_type|taxonomy
	 *     // which layout settings it inherited from
	 *     'layout'   => blog-layout|post-layout|etc...
	 *     'taxonomy' => false|true
	 * )
	 */
	protected $layout_settings;

	/**
	 * Print markup for custom layout section
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $use_custom_layout use custom layout feature or not
	 * @param array $option_value custom layout data
	 * @param array $value option arguments
	 */
	public function output( $use_custom_layout, $option_value, $value ) {				

		$settings = $this->layout_settings;

		$value        = apply_filters( 'kopa_get_custom_layouts_for_private_object', $value );
		$option_value = apply_filters( 'kopa_get_selected_layout_for_private_object', $option_value );		

		global $wp_registered_sidebars;

		$output = '';
		$output .= '<div id="kopa_section_group_'.esc_attr( $value['id'] ).'" class="kopa_section_group kopa_section_group_layout">';		
		$output .= '<label><input class="kopa_use_custom_layout" type="checkbox" name="_use_custom_layout" value="'.esc_attr( $settings['layout'] ).'" '.checked( $settings['layout'], $use_custom_layout, false ).'> '.__( 'Check if you would like to use custom setting', 'kopa-framework' ).'</label>';
		$output .= '<div class="kopa_group_content">';

		// layout images
		foreach ( $value['layouts'] as $layout_id => $layout_args ) {
			$output .= '<div id="'.esc_attr( $value['id'] . '_' . $layout_id . '_' . 'image' ).'" class="kopa_section_layout_image">';
			$output .= '<img src="'.esc_attr( $layout_args['preview'] ).'" alt="'.esc_attr( $layout_args['title'] ).'">';
			$output .= '</div>';
		}

		// select layout section
		$output .= '<div id="kopa_section_select_layout_'.esc_attr( $value['id'] ).'" class="kopa_section kopa_section_select_layout">';
		$output .= '<h4 class="kopa_heading">'.__( 'Select layout', 'kopa-framework' ).'</h4>';
		$output .= '<div class="kopa_option">';
		$output .= '<div class="kopa_controls">';
		$output .= '<select name="_custom_layout[layout_id]" id="select-layout-'.esc_attr( $value['id'] ).'" data-layout-section-id="'.esc_attr( $value['id'] ).'">';
		
		
			foreach ( $value['layouts'] as $layout_id => $layout_args ) {
				$output .= '<option value="'.esc_attr( $layout_id ).'" '.selected( $option_value['layout_id'], $layout_id, false ).'>'.$layout_args['title'].'</option>';
			}
		

		$output .= '</select>';
		$output .= '</div>'; // kopa_controls
		$output .= '</div>'; // kopa_option
		$output .= '</div>'; // kopa_section_select_layout

		// widget areas
		foreach ( $value['layouts'] as $layout_id => $layout_args ) {

			$output .= '<div id="'.esc_attr( $value['id'] . '_' . $layout_id ).'" class="kopa_section_select_area_container">';

			foreach ( $layout_args['positions'] as $position_index => $position ) {

				$output .= '<div id="kopa_section_select_area_'.esc_attr( $position_index . '_' . $layout_id ).'" class="kopa_section kopa_section_select_area">';
				$output .= '<h4 class="kopa_heading">'.$value['positions'][ $position ].'</h4>';
				$output .= '<div class="kopa_option">';
				$output .= '<div class="kopa_controls">';
				$output .= '<select name="_custom_layout[sidebars]['.esc_attr( $layout_id ).']['.$position.']">';
				$output .= '<option value="">'.__( '&mdash;Select sidebar&mdash;', 'kopa-framework' ).'</option>';
				
				// print all registered sidebars
				foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar_args ) {

					$selected_value = null;
					if ( isset( $option_value['sidebars'][ $layout_id ][ $position ] ) ) {
						$selected_value = $option_value['sidebars'][ $layout_id ][ $position ];
					}

					$output .= '<option value="'.esc_attr( $sidebar_id ).'" '.selected( $selected_value, $sidebar_id, false ).'>'.$sidebar_args['name'].'</option>';
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

		if ( $settings['taxonomy'] ) {
			echo '<tr>';
				echo '<th scope="row" valign="top">';
					echo __( 'Custom Layout', 'kopa-framework' );
				echo '</th>';
				echo '<td>';
		}

		echo $output; // print custom layout fields

		if ( $settings['taxonomy'] ) {
				echo '</td>';
			echo '</tr>';
		}

		wp_nonce_field( $settings['screen'] . '_custom_layout', '_kopa_custom_layout' );
		
	}

} // end class Kopa_Layout_Box

}