<?php

/**
 * Kopa Admin Settings Class.
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( !class_exists( 'Kopa_Admin_Settings' ) ) {

	/**
	 * Kopa_Admin_Settings
	 */
	class Kopa_Admin_Settings {

		/**
		 * @access private
		 * @static
		 * @var array settings objects
		 */
		private static $settings = array();

		/**
		 * @access private
		 * @static
		 * @var array all settings arguments
		 */
		private static $settings_arguments = array();

		/**
		 * @access private
		 * @static
		 * @var array error messages
		 */
		private static $errors = array();

		/**
		 * @access private
		 * @static
		 * @var array info messages
		 */
		private static $messages = array();

		/**
		 * Include the settings page classes
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function get_settings_pages() {
			if ( empty( self::$settings ) ) {
				$settings = array();

				include_once( 'settings/class-kopa-settings-page.php' );

				$enables = array(
					'theme-options' => apply_filters( 'kopa_settings_theme_options_enable', 1 ),
					'sidebar-manager' => apply_filters( 'kopa_settings_sidebar_manager_enable', 1 ),
					'layout-manager' => apply_filters( 'kopa_settings_layout_manager_enable', 1 ),
					'backup-manager' => apply_filters( 'kopa_settings_backup_manager_enable', 1 ),
				);

				if ( $enables['theme-options'] ) {
					$settings[] = include( 'settings/class-kopa-settings-theme-options.php' );
				}

				if ( $enables['sidebar-manager'] ) {
					$settings[] = include( 'settings/class-kopa-settings-sidebar-manager.php' );
				}

				if ( $enables['layout-manager'] ) {
					$settings[] = include( 'settings/class-kopa-settings-layout-manager.php' );
				}

				if ( $enables['backup-manager'] ) {
					$settings[] = include( 'settings/class-kopa-settings-backup-manager.php' );
				}

				self::$settings = apply_filters( 'kopa_get_settings_pages', $settings );

				// merge all settings arguments to an array
				foreach ( $settings as $setting_obj ) {
					$options_settings = $setting_obj->get_page_settings();
					self::$settings_arguments = wp_parse_args( self::$settings_arguments, $options_settings );
				} // end outer foreach
			}
			return self::$settings;
		}

		/**
		 * Get all settings arguments
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function get_settings_arguments() {
			return self::$settings_arguments;
		}

		/**
		 * Save the settings
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function save() {
			global $kopa_current_tab;

			if ( empty( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'kopa-settings' ) ) {
				die( __( 'Action failed. Please refresh the page and retry.', 'kopa-framework' ) );
			}

			// Trigger actions
			// restore default settings
			if ( isset( $_POST['kopa_reset'] ) ) {
				if ( isset( $_POST['kopa_restore_default'] ) ) {
					$restore_options = $_POST['kopa_restore_default'];
					$restore_options = explode( ',', $restore_options );

					foreach ( $restore_options as $tab_id ) {
						do_action( 'kopa_settings_reset_' . $tab_id );
					}
				} else {
					do_action( 'kopa_settings_reset_' . $kopa_current_tab );
				}
				self::add_message( __( 'Default options restored.', 'kopa-framework' ) );
			}
			// save current tab settings
			else {
				do_action( 'kopa_settings_save_' . $kopa_current_tab );
				self::add_message( __( 'Your settings have been saved.', 'kopa-framework' ) );
			}

			do_action( 'kopa_settings_saved' );
		}

		/**
		 * Add a message
		 *
		 * @param string $text error|info message
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function add_message( $text ) {
			self::$messages[] = $text;
		}

		/**
		 * Add an error
		 *
		 * @param string $text
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function add_error( $text ) {
			self::$errors[] = $text;
		}

		/**
		 * Output messages + errors
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function show_messages() {
			if ( sizeof( self::$errors ) > 0 ) {
				foreach ( self::$errors as $error ) {
					echo '<div class="wrap"><div class="kopa_message error fade inline"><p><strong>' . esc_html( $error ) . '</strong></p></div></div>';
				}
			} elseif ( sizeof( self::$messages ) > 0 ) {
				foreach ( self::$messages as $message ) {
					echo '<div class="wrap"><div class="kopa_message updated fade inline"><p><strong>' . esc_html( $message ) . '</strong></p></div></div>';
				}
			}
		}

		/**
		 * Settings page.
		 *
		 * Handles the display of the main theme settings page in admin.
		 *
		 * @return void
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function output() {
			global $kopa_current_tab, $kopa_show_save_button;

			do_action( 'kopa_settings_start' );

			// Include settings pages
			self::get_settings_pages();

			// Get current tab/section
			$kopa_current_tab = empty( $_GET['tab'] ) ? apply_filters( 'kopa_current_tab_default', 'theme-options' ) : sanitize_title( $_GET['tab'] );

			// Determines whether or not show save submit button
			$kopa_show_save_button = true;
			if ( 'backup-manager' === $kopa_current_tab ) {
				$kopa_show_save_button = false;
			}
			$kopa_show_save_button = apply_filters( 'kopa_settings_show_save_button', $kopa_show_save_button, $kopa_current_tab );

			// Save settings if data has been posted
			if ( !empty( $_POST ) ) {
				self::save();
			}

			// Add any posted messages
			if ( !empty( $_GET['kopa_error'] ) ) {
				self::add_error( stripslashes( $_GET['kopa_error'] ) );
			}

			if ( !empty( $_GET['kopa_message'] ) ) {
				self::add_message( stripslashes( $_GET['kopa_message'] ) );
			}

			self::show_messages();

			// Get tabs for the settings page
			$tabs = apply_filters( 'kopa_settings_tabs_array', array() );

			include 'views/html-admin-settings.php';
		}

		/**
		 * Get a setting from the settings API.
		 *
		 * @param string $option_name Option id
		 * @param string $default Force default value
		 * @param array $settings Option arguments
		 * @return string|array $value Option value
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function get_option( $option_name, $default = null, $settings = array() ) {
			$options = get_theme_mods(); // get all theme options
			$value = null;
			$value_isset = false; // flag to check option value is set
			$type = ''; // option type

			/**
			 * Get option value
			 */
			if ( isset( $options[$option_name] ) ) {
				$value = $options[$option_name];
				$value_isset = true;
			}
			// return force default value
			elseif ( $default ) {
				$value = $default;
				$value_isset = true;
			}
			// return default value from third argument if not empty
			elseif ( isset( $settings['default'] ) &&
					isset( $settings['id'] ) &&
					$option_name === $settings['id'] ) {
				$value = $settings['default'];
				$value_isset = true;
			}

			// get option type
			if ( isset( $settings['type'] ) ) {
				$type = $settings['type'];
			}

			// fall back for backend and frontend get_option
			if ( empty( $type ) || !$value_isset || empty( $settings ) ) {
				$option = self::get_option_arguments( $option_name );

				if ( empty( $type ) && isset( $option['type'] ) ) {
					$type = $option['type']; // get option type
				}

				if ( !$value_isset && isset( $option['default'] ) ) {
					$value = $option['default'];
					$value_isset = true;
				}

				if ( empty( $settings ) ) {
					$settings = $option;
				}
			}

			// sanitize the option value
			$type = sanitize_title( $type );

			if ( is_array( $value ) ) {
				$value = array_map( 'stripslashes_deep', $value );
			} elseif ( !is_null( $value ) ) {
				$value = stripslashes( $value );
			}

			// return the filter value
			return apply_filters( 'kopa_get_option_' . $type, $value, $settings );
		}

		/**
		 * Get option arguments from option id
		 * 
		 * @param string $option_name option id
		 * @return array $option_arg option argument
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function get_option_arguments( $option_name ) {
			$settings_arguments = self::$settings_arguments;

			foreach ( $settings_arguments as $option ) {
				if ( isset( $option['id'] ) && $option_name === $option['id'] ) {
					return $option;
				}
			} // end foreach

			return array();
		}

		/**
		 * Sanitize option arguments, make sure option 
		 * arguments does not missing essential arguments 
		 *
		 * @uses wp_parse_args() to sanitize missing option arguments
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function sanitize_option_arguments( $option = array() ) {
			$option = wp_parse_args( $option, array(
				// common arguments
				'type' => '',
				'id' => '',
				'title' => '',
				'class' => '',
				'css' => '',
				'default' => '',
				'desc' => '',
					) );

			// Sanitize fields
			$allowed_tags = array(
				'abbr' => array( 'title' => true ),
				'acronym' => array( 'title' => true ),
				'code' => true,
				'em' => true,
				'strong' => true,
				'br' => true,
				'i' => true,
				'small' => true,
				'a' => array(
					'href' => true,
					'title' => true,
				),
			);

			$option['desc'] = wp_kses( $option['desc'], $allowed_tags );

			return $option;
		}

		/**
		 * Output admin fields.
		 *
		 * Loops though the theme options array and outputs each field.
		 *
		 * @param array $options Opens array to output
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function output_fields( $options ) {
			//Use for field title
			global $kopa_title_counter;
			$kopa_title_counter = 0;

			$wrap_start = '';
			$wrap_end = '';
			$output = '';


			/**
			 * All Form Fields are available in framework
			 * @since 1.1.9
			 */
			global $kopa_form_fields;

			foreach ( $options as $settings ) {

				if ( !isset( $settings['type'] ) )
					continue;

				$settings = self::sanitize_option_arguments( $settings );

				// folding class
				$fold = '';
				if ( array_key_exists( 'fold', $settings ) ) {
					if ( self::get_option( $settings['fold'] ) ) {
						$fold = 'kopa_fold_' . $settings['fold'] . ' ';
					} else {
						$fold = 'kopa_fold_' . $settings['fold'] . ' kopa_hide ';
					}
				}

				// option classes
				$class = 'kopa_section';
				$class .= ' kopa_section_' . $settings['type'];
				if ( $settings['class'] ) {
					$class .= ' ' . $settings['class'];
				}

				// start option wrap
				$wrap_start = '<div id="kopa_section_' . esc_attr( $settings['id'] ) . '" class="' . esc_attr( $class . ' ' . $fold ) . '">';

				if ( $settings['title'] ) {
					$wrap_start .= '<h4 class="kopa_heading">' . esc_html( $settings['title'] ) . '</h4>';
				}

				$wrap_start .= '<div class="kopa_option">';

				if ( $settings['desc'] ) {
					$wrap_start .= '<div class="kopa_description">' . wpautop( $settings['desc'] ) . '</div>';
				}
				$wrap_start .= '<div class="kopa_controls">';

				// end option wrap
				$wrap_end = '</div></div></div>';

				/**
				 * Field value
				 */
				$value = self::get_option( $settings['id'], null, $settings );

				/**
				 * Search field index
				 * @since 1.1.9
				 */
				$field_keys = array_keys( $kopa_form_fields, $settings['type'] );

				$form_field_callback = 'kopa_form_field_';
				if(isset($field_keys[0])){
					$form_field_callback = $form_field_callback.$kopa_form_fields[$field_keys[0]];
				}
				
				/**
				 * Check form field is exist then call function
				 * @since 1.1.9
				 */
				if ( function_exists( $form_field_callback ) ) {
					$output.=call_user_func( $form_field_callback, $wrap_start, $wrap_end, $settings, $value );
				} else {
					$output .= apply_filters( 'kopa_admin_field_' . $settings['type'], '', $wrap_start, $wrap_end, $settings, $value );
				}
			}

			if ( $kopa_title_counter ) {
				$output .= '</div>';
			} // end check if have title options

			echo '<div class="kopa_tab_content">' . $output . '</div>';
		}

		/**
		 * Save admin fields.
		 *
		 * Loops though the woocommerce options array and outputs each field.
		 *
		 * @param array $options Opens array to output
		 * @return bool
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function save_fields( $options ) {
			if ( empty( $_POST ) )
				return false;

			// Options to update will be stored here
			$update_options = array();

			// Loop options and get values to save
			foreach ( $options as $settings ) {

				if ( !isset( $settings['id'] ) ) {
					continue;
				}

				$type = isset( $settings['type'] ) ? sanitize_title( $settings['type'] ) : '';

				// Get the option name
				$value = null;

				if ( isset( $_POST[$settings['id']] ) ) {
					$value = $_POST[$settings['id']];
				}

				// Custom handling
				do_action( 'kopa_update_option_' . $type, $settings );

				// For a value to be submitted to database it must pass through a sanitization filter
				if ( has_filter( 'kopa_sanitize_option_' . $type ) ) {
					$value = apply_filters( 'kopa_sanitize_option_' . $type, $value, $settings );
				}

				if ( !is_null( $value ) ) {
					$update_options[$settings['id']] = $value;
				}

				// Custom handling
				do_action( 'kopa_update_option', $settings );
			}

			// Hook to run after validation
			do_action( 'kopa_options_after_validate', $update_options );

			// Now save the options
			foreach ( $update_options as $name => $settings ) {
				set_theme_mod( $name, $settings );
			}

			return true;
		}

		/**
		 * Get the default values for all the theme options
		 *
		 * Get an array of all default values as set in
		 * options.php. The 'id','std' and 'type' keys need
		 * to be defined in the configuration array. In the
		 * event that these keys are not present the option
		 * will not be included in this function's output.
		 *
		 * @return bool
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function reset_fields( $options ) {
			if ( empty( $_POST ) )
				return false;

			foreach ( $options as $settings ) {

				if ( !isset( $settings['id'] ) ) {
					continue;
				}

				$type = isset( $settings['type'] ) ? sanitize_title( $settings['type'] ) : '';

				if ( 'title' === $type ||
						'groupstart' === $type ||
						'groupend' === $type ) {
					continue;
				}

				/**
				 * @since 1.2.0
				 * 
				 * Does not edit option value
				 * Just remove it
				 */
				remove_theme_mod( $settings['id'] );
			}

			return true;
		}

	}

}
