<?php
/**
 * Kopa Framework Custom Layout Feature for Taxonomy
 *
 * This module allows you to define a custom layout for taxonomy
 *
 * @extends     Kopa_Layout_Box
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Layout_Taxonomy' ) ) {

/**
 * Taxonomy custom layout handler
 */
class Kopa_Layout_Taxonomy extends Kopa_Layout_Box {

	/**
	 * Constructor
	 *
	 * @link http://en.bainternet.info/custom-taxonomies-extra-fields/
	 * @link https://wordpress.org/plugins/taxonomy-meta/
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( $layout_settings ) {
        $this->layout_settings = $layout_settings;
        $enable_custom = apply_filters('kopa_custom_use_page_layout_taxonomy', true);
        if ( isset($args['hide_in']) && $args['hide_in'] ) {
            $enable_custom = false;
        }
        if ( $enable_custom ) {
            add_action( $layout_settings['screen'] . '_edit_form_fields', array( $this, 'output_fields' ) );
            add_action( 'edit_term', array( $this, 'save_fields' ) );
            add_action( 'delete_term', array( $this, 'delete_fields' ) );
        }
	}

	/**
	 * Get custom layout data from the db
	 * and print custom layout metabox
	 *
	 * @param stdClass $tag taxonomy object
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function output_fields( $tag ) {
		$settings = $this->layout_settings;
		$layout = $settings['layout'];

		$option_value = array();
		$use_custom_layout = 0;
		$value = Kopa_Admin_Settings::get_option_arguments( $layout );

		$metas = get_theme_mod( '_custom_layout_taxonomies' );
		$meta  = isset( $metas[ $tag->term_id ] ) ? $metas[ $tag->term_id ] : array();

		if ( ! empty( $meta['data'] ) ) {
			$option_value = apply_filters( 'kopa_get_option_layout_manager', $meta['data'], $value );
		} else {
			$option_value = Kopa_Admin_Settings::get_option( $layout );
		}
		$option_value = array_map( 'stripslashes_deep', (array) $option_value );

		if ( ! empty( $meta['use'] ) ) {
			$use_custom_layout = $meta['use'];
		}

		parent::output( $use_custom_layout, $option_value, $value );
	}

	/**
	 * Save custom layout data to the db
	 * 
	 * @param int $term_id taxonomy id
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function save_fields( $term_id ) {
		$settings = $this->layout_settings;
		$screen = $settings['screen'];

		$new = array();

		$metas = get_theme_mod( '_custom_layout_taxonomies' );
		$meta  = isset( $metas[ $term_id ] ) ? $metas[ $term_id ] : array();

		// don't save if $_POST is empty
		if ( empty( $_POST ) ) {
        	return $post_id;
		}

		// check nonce
		if ( ! isset( $_POST['_kopa_custom_layout'] ) ) {
			return $term_id;
		}

		// verify nonce
		if ( ! wp_verify_nonce( $_POST['_kopa_custom_layout'], $screen . '_custom_layout' ) ) {
			return $term_id;
		}

		// custom layout data
		if ( isset( $_POST[ '_custom_layout' ] ) && $_POST[ '_custom_layout' ] ) {
			$new['data'] = $_POST['_custom_layout'];
		}

		// use custom layout or not
		if ( isset( $_POST[ '_use_custom_layout' ] ) && $_POST[ '_use_custom_layout' ] ) {
			$new['use'] = $_POST['_use_custom_layout'];
		} else {
			$new['use'] = 0;
		}

		$new = wp_parse_args( $new, $meta );

		if ( has_filter( 'kopa_sanitize_option_layout_manager' ) ) {
			$new = apply_filters( 'kopa_sanitize_option_layout_manager', $new, null );
		}
		
		$metas[ $term_id ] = $new;
		set_theme_mod( '_custom_layout_taxonomies', $metas );
	}


	/**
	 * Delete custom layout data when delete term
	 *
	 * @param int $term_id taxonomy id
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function delete_fields( $term_id ) {

		$metas = get_theme_mod( '_custom_layout_taxonomies' );
		
		if ( ! is_array( $metas ) ) {
			$metas = (array) $metas;
		}

		unset( $metas[ $term_id ] );

		set_theme_mod( '_custom_layout_taxonomies', $metas );
	}

} // end class Kopa_Layout_Taxonomy

}