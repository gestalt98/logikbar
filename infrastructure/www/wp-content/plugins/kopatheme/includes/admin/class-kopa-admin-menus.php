<?php
/**
 * Setup menus in WP admin.
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Admin_Menus' ) ) {

/**
 * Kopa_Admin_Menus Class
 */
class Kopa_Admin_Menus {

	/**
	 * Hook in tabs.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		// Add menus
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 9 );
	}

	/**
	 * Define menu options (still limited to appearance section)
	 *
	 * Examples usage:
	 *
	 * add_filter( 'kopa_menu_settings', function( $menu ) {
	 *     $menu['page_title'] = 'The Options';
	 *	   $menu['menu_title'] = 'The Options';
	 *     return $menu;
	 * });
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function menu_settings() {

		$menu = array(
			'page_title' => __( 'Theme Options', 'kopa-framework' ),
			'menu_title' => __( 'Theme Options', 'kopa-framework' ),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'kopa-framework'
		);

		return apply_filters( 'kopa_menu_settings', $menu );
	}

	/**
     * Add a subpage called "Theme Options" to the appearance menu.
     *
     * @since 1.0.0
	 * @access public
     */
	public function add_settings_page() {

		$menu = $this->menu_settings();

		add_theme_page( $menu['page_title'], $menu['menu_title'], $menu['capability'], $menu['menu_slug'], array( $this, 'settings_page' ) );
	}

	/**
	 * Init the settings page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function settings_page() {
		Kopa_Admin_Settings::output();
	}
}

}

return new Kopa_Admin_Menus();