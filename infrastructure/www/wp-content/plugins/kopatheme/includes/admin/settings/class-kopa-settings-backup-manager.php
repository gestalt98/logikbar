<?php
/**
 * Kopa Framework Backup Manager Settings
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Settings_Backup_Manager' ) ) {

/**
 * Kopa_Admin_Settings_Backup_Manager
 */
class Kopa_Settings_Backup_Manager extends Kopa_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->id    = 'backup-manager';
		$this->label = __( 'Backup', 'kopa-framework' );

		add_filter( 'kopa_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'kopa_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'kopa_sidebar_menu_settings_' . $this->id, array( $this, 'output_sidebar' ) );
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

		return apply_filters( 'kopa_backup_settings', array(
			// restore default settings
			array(
				'title' => __( 'Restore Default', 'kopa-framework' ),
				'type'  => 'title',
				'id'    => 'restore_default_settings',
				'icon'  => 'refresh',
			),
				array(
					'title'   => __( 'Restore Default Settings', 'kopa-framework' ),
					'desc'    => __( 'Select one of the options below. When you click the "Restore Defaults" button below, the Kopa Framework will restore corresponding default settings for you.', 'kopa-framework' ),
					'type'    => 'restore_default',
					'id'      => 'kopa-restore-default',

					/** 
					 * Tab ids that separate by commas
					 * Core tab ids:
					 * @see id: theme-options | class: Kopa_Settings_Theme_Options | file: class-kopa-settings-theme-options.php 
					 * @see id: sidebar-manager | class: Kopa_Settings_Sidebar_Manager | file: class-kopa-settings-sidebar-manager.php 
					 * @see id: layout-manager | class: Kopa_Settings_Layout_Manager | file: class-kopa-settings-layout-manager.php 
					 */
					'options' => array(
						'theme-options,sidebar-manager,layout-manager' => __( 'All Settings (This will contain all of the options listed below.)', 'kopa-framework' ),
						'theme-options' => __( 'Theme Options', 'kopa-framework' ),
						'sidebar-manager,layout-manager' => __( 'Sidebars &amp; Layouts', 'kopa-framework' ),
					),
					'default' => 'theme-options,sidebar-manager,layout-manager',
				),

			// backup settings
			array(
				'title' => __( 'Import/Export', 'kopa-framework' ),
				'type'  => 'title',
				'id'    => 'backup_settings',
				'icon'  => 'wrench',
			),
				array(
					'title'   => __( 'Import Settings', 'kopa-framework' ),
					'type'    => 'import',
					'id'      => 'import',
					'desc'    => __( "If you have settings in a backup file on your computer, you can import those into this site.", 'kopa-framework' ),
					'default' => '',
				),
				array(
					'title' 	=> __( 'Export Settings', 'kopa-framework' ),
					'type' 		=> 'export',
					'id' 		=> 'export',
					'desc' 		=> sprintf( __( "When you click the button below, the Kopa Framework will create a text file for you to save to your computer.\nThis text file can be used to restore your settings here on \"%s\", or to easily setup another website with the same settings.", 'kopa-framework' ), get_bloginfo( 'name' ) ),
					/** 
					 * Tab ids that separate by commas
					 * Core tab ids:
					 * @see id: theme-options | class: Kopa_Settings_Theme_Options | file: class-kopa-settings-theme-options.php 
					 * @see id: sidebar-manager | class: Kopa_Settings_Sidebar_Manager | file: class-kopa-settings-sidebar-manager.php 
					 * @see id: layout-manager | class: Kopa_Settings_Layout_Manager | file: class-kopa-settings-layout-manager.php 
					 */
					'options' => array(
						'theme-options,sidebar-manager,layout-manager' => __( 'All Settings (This will contain all of the options listed below.)', 'kopa-framework' ),
						'theme-options' => __( 'Theme Options', 'kopa-framework' ),
						'sidebar-manager,layout-manager' => __( 'Sidebars &amp; Layouts', 'kopa-framework' ),
					),
					'default' => 'theme-options,sidebar-manager,layout-manager',
				),
		) );
	}

}

}

return new Kopa_Settings_Backup_Manager();
