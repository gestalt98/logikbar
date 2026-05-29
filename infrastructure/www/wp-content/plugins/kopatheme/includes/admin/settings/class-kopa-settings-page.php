<?php
/**
 * Kopa Framework Settings Page/Tab
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Settings_Page' ) ) :

/**
 * Kopa_Settings_Page
 */
class Kopa_Settings_Page {

	/**
	 * @access protected
	 * @var string settings page id
	 */
	protected $id    = '';

	/**
	 * @access protected
	 * @var string settings page label
	 */
	protected $label = '';

	/**
	 * Get setting ID
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Add this page to settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;

		return $pages;
	}

	/**
	 * Get settings array
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_page_settings() {
		return array();
	}

	/**
	 * Output the settings content
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function output() {
		$settings = $this->get_page_settings();

		Kopa_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Output the sidebar menu settings
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function output_sidebar() {
		$menu = '';
		$options = $this->get_page_settings();

		foreach ( $options as $value ) {
			if ( ! isset( $value['type'] ) ) continue;

			if ( 'title' == $value['type'] ) {
				$value = wp_parse_args( $value, array(
    				'id'    => '',
    				'title' => '',
    				'icon'  => '',
    			) );

    			$menu .= '<li id="kopa_'.esc_attr( $value['id'] ).'_tab">';
    			if ( $value['icon'] ) {
					$menu .= '<span class="fa fa-'.esc_attr( $value['icon'] ).'"></span>';
    			}
    			$menu .= '<a class="kopa_nav_title" href="#kopa_'.esc_attr( $value['id'] ).'">'.esc_html( $value['title'] ).'</a></li>';
			} // end if
		} // end foreach

		echo '<ul class="kopa_nav kopa_nav_tabs">' . $menu . '</ul>';
	}

	/**
	 * Save settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function save() {
		$settings = $this->get_page_settings();
		Kopa_Admin_Settings::save_fields( $settings );
	}

	/**
	 * Reset settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function reset() {
		$settings = $this->get_page_settings();
		Kopa_Admin_Settings::reset_fields( $settings );
	}
}

endif;