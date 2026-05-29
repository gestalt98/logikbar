<?php
/**
 * Kopa Framework Sidebar Manager Settings
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Settings_Sidebar_Manager' ) ) {

/**
 * Kopa_Admin_Settings_Sidebar_Manager
 */
class Kopa_Settings_Sidebar_Manager extends Kopa_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->id    = 'sidebar-manager';
		$this->label = __( 'Sidebar Manager', 'kopa-framework' );

		add_filter( 'kopa_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'kopa_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'kopa_sidebar_menu_settings_' . $this->id, array( $this, 'output_sidebar' ) );
		add_action( 'kopa_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'kopa_settings_reset_' . $this->id, array( $this, 'reset' ) );
	}

	/**
	 * Get settings array
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array settings arguments
	 */
	public function get_page_settings() {

		return apply_filters( 'kopa_sidebar_manager_settings', array(
			array(
				'title'   => __( 'Sidebar Manager', 'kopa-framework' ),
				'type' 	  => 'title',
				'id' 	  => 'sidebar-title',
			),
			array(
				'title'       => 'Sidebar Manager',
				'description' => __( 'Add as many sidebars (widget areas) as you need. Creating sidebars (widget areas) is unlimited.', 'kopa-framework' ),
				'id'          => 'sidebar-manager',
				'type'        => 'sidebar_manager',
				'default_atts' => apply_filters( 'kopa_sidebar_default_attributes', array(
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => '',
				) ),
				'default' => apply_filters( 'kopa_sidebar_default', array() ),
			),
		) ); // End sidebar manager settings

	}

} // end class Kopa_Settings_Sidebar_Manager

}

return new Kopa_Settings_Sidebar_Manager();
