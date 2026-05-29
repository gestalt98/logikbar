<?php
/*
Plugin Name: Inbound Now - Edit Core WordPress Email Templates
Plugin URI: http://www.inboundnow.com/
Description: Lets users edit/modify core WordPress and Inbound Now email templates.
Version: 1.0.7
Author: Inbound Now
Contributors: Hudson Atwell
Author URI: http://www.inboundnow.com/
*
*/



if ( !class_exists( 'Inbound_Legacy_Email_Temaples' )) {

	class Inbound_Legacy_Email_Temaples {

		/**
		*	initiates class
		*/
		public function __construct() {

			global $wpdb;

			/* Define constants */
			self::define_constants();

			/* Define hooks and filters */
			self::load_hooks();

			/* load files */
			self::load_files();
		}

		/**
		*	Loads hooks and filters selectively
		*/
		public static function load_hooks() {
		}


		/**
		*	Defines constants
		*/
		public static function define_constants() {
			define('INBOUND_EDIT_CORE_EMAIL_TEMPLATES_CURRENT_VERSION', '1.0.7' );
			define('INBOUND_EDIT_CORE_EMAIL_TEMPLATES_SLUG' , plugin_basename( dirname(__FILE__) ) );
			define('INBOUND_EDIT_CORE_EMAIL_TEMPLATES_FILE' ,	__FILE__ );
			define('INBOUND_EDIT_CORE_EMAIL_TEMPLATES_REMOTE_ITEM_NAME' , 'inbound-legacy-email-templates' );
			define('INBOUND_EDIT_CORE_EMAIL_TEMPLATES_URLPATH', plugins_url( '/', __FILE__ ) );
			define('INBOUND_EDIT_CORE_EMAIL_TEMPLATES_PATH', realpath(dirname(__FILE__) ).'/');
		}

		/**
		*  Loads PHP files
		*/
		public static function load_files() {

			

			include_once INBOUND_EDIT_CORE_EMAIL_TEMPLATES_PATH . 'classes/post-type.php';
			include_once INBOUND_EDIT_CORE_EMAIL_TEMPLATES_PATH . 'classes/metaboxes.php';
			include_once INBOUND_EDIT_CORE_EMAIL_TEMPLATES_PATH . 'classes/core.php';
			include_once INBOUND_EDIT_CORE_EMAIL_TEMPLATES_PATH . 'classes/settings.php';
			
			if ( is_admin() ) {
                include_once INBOUND_EDIT_CORE_EMAIL_TEMPLATES_PATH . 'classes/activate.php';
			}
		}

	}

	new Inbound_Legacy_Email_Temaples();

}