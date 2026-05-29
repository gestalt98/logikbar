<?php
/**
 * Kopa Framework Custom Layout Feature
 *
 * This module allows you to define a custom layout for post types or taxonomies
 *
 * @author 		Kopatheme
 * @category 	Admin/Custom Layout
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Admin_Custom_Layouts' ) ) {

/**
 * Kopa_Admin_Custom_Layouts Class
 */
class Kopa_Admin_Custom_Layouts {

	/**
	 * @access private
	 * @var array post type custom layout settings
	 */
	private $posttype_settings;

	/**
	 * @access private
	 * @var array post type custom layout objects
	 */
	private $posttype_layouts;

	/**
	 * @access private
	 * @var array taxonomy custom layout settings
	 */
	private $taxonomy_settings;

	/**
	 * @access private
	 * @var array taxonomy custom layout objects
	 */
	private $taxonomy_layouts;

	/**
	 * @access private
	 * @var array screen ids for custom layouts
	 */
	private $screen_ids;

	/**
	 * Constructor
	 *
	 * All custom functionality will be hooked into the "init" action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->posttype_settings = array();
		$this->posttype_layouts  = array();
		$this->taxonomy_settings = array();
		$this->taxonomy_layouts  = array();
		$this->screen_ids = array();

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'custom_layout_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'custom_layout_scripts' ) );
	}

	/**
	 * Includes classes for printing and saving 
	 * for custom layout of post type and taxonomy
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function custom_layout_styles() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->screen_ids ) ) {
			wp_enqueue_style( 'kopa_custom_layout' );
		}
	}

	/**
	 * Includes classes for printing and saving 
	 * for custom layout of post type and taxonomy
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function custom_layout_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->screen_ids ) ) {
			// script for disable/enable selection fields 
			// of custom layout box
			wp_enqueue_script( 'kopa_custom_layout' );
			
			// script for change sidebar selection fields 
			// and preview image for each selected layout
			wp_enqueue_script( 'kopa_dynamic_layout' );
		} 
	}

	/**
	 * Includes classes for printing and saving 
	 * for custom layout of post type and taxonomy
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function includes() {
		include_once( 'layouts/class-kopa-layout-box.php' );
		include_once( 'layouts/class-kopa-layout-post-type.php' );
		include_once( 'layouts/class-kopa-layout-taxonomy.php' );
	}

	/**
	 * Conditionally hook into WordPress.
	 *
	 * Theme must declare that they support this module by adding
	 * add_theme_support( 'kopa_custom_layout' ); during after_setup_theme.
	 *
	 * If no theme support is found there is no need to hook into WordPress.
	 * We'll just return early instead.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		$theme_support = get_theme_support( 'kopa_custom_layout' );

		// Return early if theme does not support Featured Content.
		if ( ! $theme_support ) {
			return;
		}

		/*
		 * An array of named arguments must be passed as the second parameter
		 * of add_theme_support().
		 */
		if ( ! isset( $theme_support[0] ) ) {
			return;
		}

		// includes Kopa_Layout_Post_Type 
		// and Kopa_Layout_Taxonomy classes
		$this->includes();

		$posttype_settings = array();
		$posttype_layouts  = array();
		$taxonomy_settings = array();
		$taxonomy_layouts  = array();
		$screen_ids        = array();

		foreach ( $theme_support[0] as $args ) {
			// if taxonomy, may be custom
			if ( $args['taxonomy'] ) {
				$taxonomy_settings[] = $args;
				$screen_ids[] = 'edit-' . $args['screen'];
			} 
			// if post type, may be custom
			else {
				$posttype_settings[] = $args;
				$screen_ids[] = $args['screen'];
			}
		}

		if ( $posttype_settings ) {
			foreach ( $posttype_settings as $args ) {				
				$posttype_layouts[] = new Kopa_Layout_Post_Type( $args );
			}
		}

		if ( $taxonomy_settings ) {
			foreach ( $taxonomy_settings as $args ) {
				$taxonomy_layouts[] = new Kopa_Layout_Taxonomy( $args );
			}
		}

		// assign new settings and layouts to private properties
		$this->posttype_settings = $posttype_settings;
		$this->posttype_layouts  = $posttype_layouts;
		$this->taxonomy_settings = $taxonomy_settings;
		$this->taxonomy_layouts  = $taxonomy_layouts;
		$this->screen_ids        = $screen_ids;
	}

} // end class Kopa_Admin_Custom_Layout

}

new Kopa_Admin_Custom_Layouts();