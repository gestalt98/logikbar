<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Kopa Framework Admin.
 *
 * @class 		Kopa_Admin 
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */
class Kopa_Admin {
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function includes() {
		// Functions
		include_once( 'kopa-admin-functions.php' );

		/**
		 * Load form fields
		 * @since 1.1.9
		 */
		include_once( 'fields/load.php' );
		
		// Classes	
		include_once( 'class-kopa-admin-menus.php' );
		include_once( 'class-kopa-admin-assets.php' );
		include_once( 'class-kopa-admin-backup.php' );
		include_once( 'class-kopa-admin-settings-sanitization.php' );
		include_once( 'class-kopa-admin-custom-layouts.php' );
		include_once( 'class-kopa-admin-meta-box.php' );
		include_once( 'class-kopa-admin-term-meta.php' );

		// Classes we only need if the ajax is not-ajax
		if ( ! defined( 'DOING_AJAX' ) ) {
			// Help
			if ( apply_filters( 'kopa_enable_admin_help_tab', true ) ) {
				include( 'class-kopa-admin-help.php' );
			}
		}
	}
}

return new Kopa_Admin();