<?php
/**
 * Kopa Framework Backup
 * Backup your settings to a downloadable text file.
 * Folked from WooThemes Theme Options Backup
 *
 * @author 		Kopatheme
 * @category 	Backup
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kopa_Admin_Backup {
	/**
	 * @access private
	 * @var string concantinated string of export file name
	 */
	private $token;

	/**
	 * Contructor function
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->token = 'kopathemes-backup';
	} // end __construct()

	/**
	 * init()
	 *
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init () {
		$admin_screen_ids = kopa_get_screen_ids();
		foreach ( $admin_screen_ids as $admin_page ) {
			add_action( 'load-' . $admin_page, array( $this, 'backup_logic' ) );
		}
	} // end init()


	/**
	 * backup_logic()
	 * 
	 * Determines backup or restore
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function backup_logic() {
		if ( ! isset( $_POST['kopa_backup_export'] ) && isset( $_POST['kopa_backup_import'] ) && ( $_POST['kopa_backup_import'] == true ) ) {
			$this->import();
		}
		
		if ( ! isset( $_POST['kopa_backup_import'] ) && isset( $_POST['kopa_backup_export'] ) && ( $_POST['kopa_backup_export'] == true ) ) {
			$this->export();
		}
	}

	/**
	 * import()
	 *
	 * Import settings from a backup file.
	 * @return boolean | true  Import successfully
	 * @return boolean | false Import failure
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function import() {
		check_admin_referer( 'kopa-settings' );

		if ( ! isset( $_FILES['kopa_import_file'] ) ) { return; } // We can't import the settings without a settings file.

		$menu = Kopa_Admin_Menus::menu_settings();
		$url = admin_url( 'themes.php?page='.$menu['menu_slug'].'&tab=backup-manager' );

		/**
		 * Getting Credentials
		 * @see http://codex.wordpress.org/Filesystem_API#Getting_Credentials
		 */
		if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
			return false; // stop processing here
		}

		/**
		 * Initializing WP_Filesystem_Base
		 * @see http://codex.wordpress.org/Filesystem_API#Initializing_WP_Filesystem_Base
		 */
		if ( ! WP_Filesystem($creds) ) {
			request_filesystem_credentials($url, '', true, false, null);
			return false;
		}

		/**
		 * Using the WP_Filesystem_Base Class to read file
		 * @see http://codex.wordpress.org/Filesystem_API#Using_the_WP_Filesystem_Base_Class
		 */
		global $wp_filesystem;

		$file = $_FILES['kopa_import_file']['tmp_name'];
		$upload = $wp_filesystem->get_contents( $file );

		// Decode the JSON from the uploaded file
		$options = json_decode( $upload, true );

		// Check for errors
		if ( ! $options || $_FILES['kopa_import_file']['error'] ) {
			Kopa_Admin_Settings::add_error( __( 'There was a problem importing your settings.', 'kopa-framework' ) );
			return false;
		}

		// Make sure this is a valid backup file.
		if ( ! isset( $options['kopathemes-backup-validator'] ) ) {
			Kopa_Admin_Settings::add_error( __( "The import file you've provided is invalid.", 'kopa-framework' ) );
			return false;
		} else {
			unset( $options['kopathemes-backup-validator'] ); // Now that we've checked it, we don't need the field anymore.
		}

		$has_updated = false; // If this is set to true at any stage, we add a successful message.

		// Loop through data, import settings
		foreach ( (array) $options as $key => $settings ) {
			$settings = maybe_unserialize( $settings ); // Unserialize serialized data before inserting it back into the database.
			
			// We can run checks using get_option(), as the options are all cached. See wp-includes/functions.php for more information.
			if ( get_theme_mod( $key ) != $settings ) {
				set_theme_mod( $key, $settings );
				$has_updated = true;
			}
		}

		if ( $has_updated ) {
			Kopa_Admin_Settings::add_message( __( 'Settings successfully imported.', 'kopa-framework' ) );
		}

		return $has_updated;
	}

	/**
	 * export()
	 *
	 * Export settings to a backup file.
	 *
	 * @uses $this->get_export_type_label()
	 * @uses $this->construct_export_data()
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function export() {
		check_admin_referer( 'kopa-settings' );

		$export_type = esc_attr( strip_tags( $_POST['kopa_export_type'] ) );

		$export_type_label = $this->get_export_type_label( $export_type );
		if ( $export_type_label ) {
			$export_type_label = '-' . $export_type_label;
		}

		$export_data = $this->construct_export_data( $export_type );

		// Add our custom marker, to ensure only valid files are imported successfully.
		$export_data['kopathemes-backup-validator'] = date_i18n( 'Y-m-d H:i:s' );

		$output = json_encode( $export_data );

		header( 'Content-Description: File Transfer' );
	    header( 'Cache-Control: public, must-revalidate' );
	    header( 'Pragma: hack' );
		header( 'Content-Type: text/plain' );
	    header( 'Content-Disposition: attachment; filename="' . $this->token . '-' . date_i18n( 'Ymd-His' ) . $export_type_label . '.json"' );
	    header( 'Content-Length: ' . strlen( $output ) );
		echo $output;
		die();
	}

	/**
	 * construct_export_data()
	 *
	 * Constructs the export data based on the export type.
	 *
	 * @param string $export_type
	 * @return array exported data
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function construct_export_data( $export_type ) {
		/**
		 * Get array of setting ids
		 *
		 * @see class-kopa-settings-backup-manager.php
		 */
		$export_type = explode( ',', $export_type );
		$settings = Kopa_Admin_Settings::get_settings_pages();
		$options = array();      // will store setting arguments of all setting objects
		$export_data = array();  // will store only option names and their values 

		// get setting arguments based on export type
		foreach ( $settings as $setting_obj ) {
			$setting_id = $setting_obj->get_id();

			if ( in_array( $setting_id, $export_type ) ) {
				$options = wp_parse_args( $options, $setting_obj->get_page_settings() );
			}
		}

		// create an array to store pairs of option name and value
		foreach ( $options as $value ) {
			if ( isset( $value['id'] ) ) {
				$option_id = $value['id'];

				$option_id = sanitize_title( $option_id );
				$option_value = Kopa_Admin_Settings::get_option( $option_id );
				
				if ( ! is_null( $option_value ) ) {
					$export_data[ $option_id ] = $option_value;
				}
			}
		}

		return $export_data;
	}

	/**
	 * get_export_type_label()
	 *
	 * Get export type label for export file name
	 *
	 * @param string $export_type
	 * @return string export type label
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_export_type_label( $export_type ) {
		$labels = apply_filters( 'kopa_backup_export_type_labels', array(
			'theme-options,sidebar-manager,layout-manager' => 'all-settings',
			'theme-options'                                => 'theme-options',
			'sidebar-manager,layout-manager'               => 'sidebars-layouts',
		) );

		if ( ! empty( $labels[ $export_type ] ) ) {
			return sanitize_title( $labels[ $export_type ] );
		}

		return '';
	}
}

/**
 * Create $kopa_backup Object.
 *
 * @uses Kopa_Admin_Backup
 *
 * @since 1.0.0
 */
$kopa_backup = new Kopa_Admin_Backup();
$kopa_backup->init();