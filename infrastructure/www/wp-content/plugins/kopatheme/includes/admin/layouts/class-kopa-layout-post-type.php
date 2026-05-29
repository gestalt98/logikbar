<?php
/**
 * Kopa Framework Custom Layout Feature for Post Type
 *
 * This module allows you to define a custom layout metabox for post type
 *
 * @extends     Kopa_Layout_Box
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Layout_Post_Type' ) ) {

/**
 * Post type custom layout handler
 */
class Kopa_Layout_Post_Type extends Kopa_Layout_Box {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( $layout_settings ) {
		$this->layout_settings = $layout_settings;
		add_action( 'add_meta_boxes', array( $this, 'add_layout_box' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
		add_action( 'delete_post', array( $this, 'delete_fields' ) );
	}

	/**
	 * Register custom layout metabox
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function add_layout_box() {
        $args = $this->layout_settings;
        $enable_custom = apply_filters('kopa_custom_use_page_layout_singular', true);
        if ( isset($args['hide_in']) && $args['hide_in'] ) {
            $enable_custom = false;
        }
        if ( $enable_custom ) {
            add_meta_box(
                'kopa_custom_layout',
                __( 'Custom Layout', 'kopa-framework' ),
                array( $this, 'output_fields' ),
                $args['screen'],
                'normal',
                'default',
                $args
            );
        }
    }

	/**
	 * Callback function to get custom layout meta data and
	 * print custom layout metabox
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
	 *
	 * @param WP_Post $post current post object
	 * @param array $metabox custom arguments that put to add_meta_box before
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function output_fields( $post, $metabox ) {
		$settings = $this->layout_settings;
		$layout = $settings['layout'];
		
		$option_value = array();
		$use_custom_layout = 0;
		$value = Kopa_Admin_Settings::get_option_arguments( $layout );



		$metas = get_theme_mod( '_custom_layout_posts' );
		$meta  = isset( $metas[ $post->ID ] ) ? $metas[ $post->ID ] : array();

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
	 * Save custom layout meta data
	 *
	 * @param int $post_id current post id
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function save_fields( $post_id ) {
		$settings = $this->layout_settings;
		$screen = $settings['screen'];
		$layout = $settings['layout'];

		$metas = get_theme_mod( '_custom_layout_posts' );
		$meta  = isset( $metas[ $post_id ] ) ? $metas[ $post_id ] : array();
		
		$new = array();

		// don't save if $_POST is empty
		if ( empty( $_POST ) ) {
        	return $post_id;
		}

		// don't save during autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// check nonce
		if ( ! isset( $_POST['_kopa_custom_layout'] ) ) {
			return $post_id;
		}

		// verify nonce
		if ( ! wp_verify_nonce( $_POST['_kopa_custom_layout'], $screen . '_custom_layout' ) ) {
			return $post_id;
		}

		/* check permissions */
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		// custom layout data
		if ( isset( $_POST[ '_custom_layout' ] ) && $_POST[ '_custom_layout' ] ) {
			$new['data'] = $_POST['_custom_layout'];
		}

		// use custom layout or not
		if ( isset( $_POST[ '_use_custom_layout' ] ) && $_POST[ '_use_custom_layout' ] ) {
			$new['use'] = $_POST[ '_use_custom_layout' ];
		} else {
			$new['use'] = 0;
		}

		$new = wp_parse_args( $new, $meta );

		if ( has_filter( 'kopa_sanitize_option_layout_manager' ) ) {
			$new = apply_filters( 'kopa_sanitize_option_layout_manager', $new, null );
		}
		
		$metas[ $post_id ] = $new;
		set_theme_mod( '_custom_layout_posts', $metas );
	}

	/**
	 * Delete custom layout data when delete post
	 *
	 * @param int $post_id post id
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function delete_fields( $post_id ) {

		// get_theme_mod( '_custom_layout_taxonomies' )
		$metas = get_theme_mod( '_custom_layout_posts' );
		
		if ( ! is_array( $metas ) ) {
			$metas = (array) $metas;
		}

		unset( $metas[ $post_id ] );

		set_theme_mod( '_custom_layout_posts', $metas );
	}

} // end class Kopa_Layout_Post_Type

}