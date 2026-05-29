<?php
/**
 * Kopa Admin Settings Sanitization Class.
 *
 * @author 		Kopatheme
 * @category 	Admin
 * @package 	KopaFramework/Admin
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Admin_Settings_Sanitization' ) ) {

/**
 * Kopa_Admin_Settings_Sanitization
 */
class Kopa_Admin_Settings_Sanitization {
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		/**
		 * Common built-in sanitization methods of this class
		 */
		// option type => sanitize function
		$sanitize_options = array(
			'text'                => 'sanitize_string',
			'email'               => 'sanitize_string',
			'url'                 => 'sanitize_string',
			'number'              => array( 'sanitize_string', 'sanitize_number' ),
			'select'              => 'sanitize_string',
			'color'               => 'sanitize_string',
			'password'            => 'sanitize_string',
			'radio'               => 'sanitize_string',
			'upload'              => 'sanitize_string',
			'textarea'            => 'sanitize_textarea',
			'checkbox'            => 'sanitize_checkbox',
			'multicheck'          => array( 'sanitize_array', 'sanitize_multicheck' ),
			'multiselect'         => 'sanitize_array',
			'custom_font_manager' => 'sanitize_array',
			'sidebar_manager'     => 'sanitize_sidebar_manager',
			'select_font'         => array( 'sanitize_array', 'sanitize_select_font' ),
			'layout_manager'      => 'sanitize_array',
		);

		foreach ( $sanitize_options as $type => $sanitize_function ) {
			if ( is_array( $sanitize_function ) ) {
				$priority = 10;
				foreach ( $sanitize_function as $function_to_add ) {
					add_filter( 'kopa_sanitize_option_' . $type, array( $this, $function_to_add ), $priority, 2 );

					$priority += 5; // increase priority by 5
				} // end inner foreach
			} else {
				add_filter( 'kopa_sanitize_option_' . $type, array( $this, $sanitize_function ), 10, 2 );
			} // end if
		} // end outer foreach

		/**
		 * Extra sanitizations
		 */
		// Remove data of deleted sidebars
		add_action( 'kopa_remove_data_of_deleted_sidebars', array( $this, 'remove_data_of_deleted_sidebars' ) );

		add_filter( 'kopa_sanitize_option_email', 'sanitize_email' );
		add_filter( 'kopa_sanitize_option_url', 'esc_url' );
	}

	/**
	 * Sanitize text, email, url, number,
	 * select, color, password, radio
	 * 
	 * @param string $input input data
	 * @param array $option option arguments
	 * @return string empty string if nothing submitted
	 * @return string clean version of input data
	 *
	 * @uses kopa_clean to sanitize potentially unsafe data
	 * @uses stripslashes to remove slashes
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize_string( $input, $option ) {
		if ( is_null( $input ) ) {
			return '';
		}

		return kopa_clean( stripslashes( $input ) );
	}

	/**
	 * Sanitize array, for multicheck, multiselect, custom_font_manager
	 *
	 * @param array $input input data
	 * @param array $option option arguments 
	 * @return array $input clean version of submitted data
	 * @return array empty array if nothing submitted
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize_array( $input, $option ) {
		if ( is_null( $input ) ) {
			return array();
		}

		$clean = array();
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				if ( is_array( $value ) ) {
					// call this function recursively if $value is array
					$clean[ $key ] = $this->sanitize_array( $value, $option );
				} else {
					$clean[ $key ] = $this->sanitize_string( $value, $option );
				}
			}
		}

		return $clean;
	}

	/**
	 * Sanitize number
	 *
	 * @param string $input input data
	 * @param array $option option arguments 
	 * @return string $input sanitized number
	 * @return string empty string if not is numeric
	 *
	 * @since 1.0.1
	 * @access public
	 */
	public function sanitize_number( $input, $option ) {
		if ( is_numeric( $input ) ) {
			return $input;
		}

		return '';
	}

	/**
	 * Sanitize checkbox option type
	 *
	 * @param string|int $input input data
	 * @param array $option option arguments 
	 * @return int 0 if checkbox is unchecked
	 * @return int 1 if checkbox is checked
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize_checkbox( $input, $option ) {
		if ( is_null( $input ) || 
			 empty( $input ) ) { // for restore default
			return 0;
		}

		return 1;
	}

	/**
	 * Sanitize multicheck option type
	 *
	 * @param array $input input data
	 * @param array $option option arguments 
	 * @return array $output
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize_multicheck( $input, $option ) {
		$output = array();
		if ( is_array( $input ) ) {
			foreach( $option['options'] as $key => $value ) {
				$output[$key] = 0;
			}
			foreach( $input as $key => $value ) {
				if ( array_key_exists( $key, $option['options'] ) && $value ) {
					$output[$key] = 1;
				}
			}
		}
		return $output;
	}

	/**
	 * Sanitize textarea field
	 *
	 * @param string $input input data
	 * @param array $option option arguments 
	 * @return string empty string if nothing submitted
	 * @return string clean version of input data
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize_textarea( $input, $option ) {
		if ( is_null( $input ) ) {
			return '';
		}

		/**
		 * check whether sanitize via kses methods or not
		 *
		 * @since 1.0.7
		 */
		if ( isset( $option['validate'] ) && ! $option['validate'] ) {
			return trim( stripslashes( $input ) );
		}

		return wp_kses_post( trim( stripslashes( $input ) ) );
	}

	/**
	 * Sanitize sidebar_manager
	 *
	 * @param array $input input data
	 * @param array $option option arguments 
	 * @return array $input
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize_sidebar_manager( $input, $option ) {
		// sanitize sidebar manager
		$output = array();
		if ( is_array( $input ) ) {
			foreach ( $input as $sidebar_id => $sidebar_atts ) {
				if ( is_array( $sidebar_atts ) ) {
					foreach ( $sidebar_atts as $key => $value ) {
						if ( 'name' === $key || 'description' === $key ) {
							$sidebar_atts[ $key ] = $this->sanitize_string( $value, null );
						} else {
							$sidebar_atts[ $key ] = stripslashes( $value );
						}
					} // end inner foreach
				}

				// assign after clean data
				$output[ $sidebar_id ] = $sidebar_atts;
			} // end outer foreach
		}

		// get old data
		$deleted_sidebars = array();
		$old_sidebars = Kopa_Admin_Settings::get_option( $option['id'], $option['default'], $option );

		// compares the old setting with the newly submitted setting
		// to find what sidebars were deleted
		if ( is_array( $old_sidebars ) ) {
			foreach ( $old_sidebars as $sidebar_id => $sidebar_args ) {
				if ( ! array_key_exists( $sidebar_id, $output ) ) {
					$deleted_sidebars[] = $sidebar_id;
				}
			}
		}

		// remove all widgets that were dragged into the deleted sidebars
		do_action( 'kopa_remove_data_of_deleted_sidebars', $deleted_sidebars );

		return $output;
	}

	/**
	 * Sanitize select font
	 *
	 * @param array $input input data
	 * @return array $input sanitized data
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize_select_font( $input, $option ) {
		$input = wp_parse_args( $input, array(
			'family' => '',
			'style'  => '',
			'size'   => '',
			'color'  => '',
		) );

		if ( isset( $input['size'] ) && is_numeric( $input['size'] ) ) {
			$input['size'] = abs( $input['size'] );
		} else {
			$input['size'] = '';
		}

		return $input;
	}

	/**
	 * Remove data of deleted sidebars
	 * @param array | $deleted_sidebars | List of deleted sidebars
	 * @return void
	 *
	 * @see $this->sanitize_sidebar_manager()
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function remove_data_of_deleted_sidebars( $deleted_sidebars ) {
		if ( empty( $deleted_sidebars ) ) {
			return;
		}

		$sidebars_widgets = get_option( 'sidebars_widgets' );

		// loop through all sidebars widgets
		// remove data of deleted sidebars
		foreach ( $deleted_sidebars as $sidebar_id ) {
			if ( isset( $sidebars_widgets[ $sidebar_id ] ) ) {
				unset( $sidebars_widgets[ $sidebar_id ] );
			}
		}

		// after removing data, update sidebars_widgets
		update_option( 'sidebars_widgets', $sidebars_widgets );

	}
} // end class Kopa_Admin_Settings_Sanitization

}

return new Kopa_Admin_Settings_Sanitization();