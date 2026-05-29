<?php
/**
 * Kopa Framework Theme Options Settings
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Settings_Theme_Options' ) ) {

/**
 * Kopa_Admin_Settings_Theme_Options
 */
class Kopa_Settings_Theme_Options extends Kopa_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->id    = 'theme-options';
		$this->label = __( 'General Settings', 'kopa-framework' );

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

		return apply_filters( 'kopa_theme_options_settings', array() ); // End general settings
	}

}

}

return new Kopa_Settings_Theme_Options();
