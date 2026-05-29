<?php
/**
 * Plugin Name: Kopa Framework
 * Description: A WordPress framework by Kopatheme.
 * Version: 1.2.1
 * Author: Kopa Theme
 * Author URI: http://kopatheme.com
 * License: GPLv2 or later
 * Requires at least: 4.1
 * Tested up to: 4.5.3
 *
 * Text Domain: kopa-framework
 * Domain Path: /languages/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Kopa_Framework' ) ) {

/**
 * Main Kopa Framework Class
 *
 * A WordPress framework by Kopatheme
 * 
 * @author    Kopatheme
 * @copyright 2014 Kopatheme
 * @license   GPLv2 or later
 * @version   1.0.10
 * @package   KopaFramework
 * @link      http://kopatheme.com
 */
final class Kopa_Framework {

	/**
	 * @access public
	 * @var string framework version
	 */
	public $version = '1.2.0';

	/**
	 * @access protected
	 * @static
	 * @var Kopa_Framework The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Kopa_Framework Instance
	 *
	 * Ensures only one instance of Kopa_Framework is loaded or can be loaded.
	 *
	 * @see KF()
	 * @return Kopa_Framework - Main instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * 
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Kopa Framework Constructor.
	 *
	 * @return Kopa_Framework instance
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'widgets_init', array( $this, 'include_widgets' ) );
	}

	/**
	 * Define Kopa Constants
	 * 
	 * @since 1.0.0
	 * @access private
	 */
	private function define_constants() {
		define( 'KOPA_FRAMEWORK_VERSION', $this->version );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function includes() {
		// functions
		include_once( 'includes/kopa-core-functions.php' );

		// include master-widget
		include_once( 'includes/abstracts/abstract-kopa-widget.php' );

		// settings class (special important class)
		include_once( 'includes/admin/class-kopa-admin-settings.php' );

		// frontend assets class
		include_once( 'includes/class-kopa-frontend-assets.php' );

		if ( defined( 'DOING_AJAX' ) ) {
			$this->ajax_includes();
		}

		if ( is_admin() ) {
			include_once( 'includes/admin/class-kopa-admin.php' );
		}

	}

	/**
	 * Include required ajax files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ajax_includes() {
		include_once( 'includes/class-kopa-ajax.php' );
	}

	/**
	 * Sets up the default filters and actions for
	 * the WordPress hooks and Kopa Framework hooks
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function default_filters() {
		add_filter( 'upload_mimes', array( $this, 'more_mimes' ) );

		// Live update when add new layouts (also their sidebars)
		// or add new positions into existed layouts
		add_filter( 'kopa_get_option_layout_manager', array( $this, 'live_update_layout_manager' ), 10, 2 );

		/**
		 * Disable help tab by default, determine its content later
		 *
		 * @see admin/class-kopa-admin.php
		 * @since 1.0.0
		 */
		add_filter( 'kopa_enable_admin_help_tab', '__return_false' );

        #metabox-custom-wrap
        $is_metabox_wrapper = apply_filters('kopa_admin_metabox_wrapper', true);
        if ( $is_metabox_wrapper ) {
            add_filter('kopa_admin_meta_box_wrap_start', array( $this, 'meta_box_wrap_start' ), 10, 3);
            add_filter('kopa_admin_meta_box_wrap_end', array( $this, 'meta_box_wrap_end' ), 10, 3);
        }

        $advanced_field = apply_filters('kopa_admin_metabox_advanced_field', false);
        if ( $advanced_field ) {
            add_filter('kopa_sanitize_option_datetime', array($this, 'kopa_sanitize_option_datetime'), 10, 2);
        }
	}

	/**
	 * Init KopaFramwork when WordPress Initialises.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		// Load Localisation files.
		load_plugin_textdomain( 'kopa-framework', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Initialize common feature for using later
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setup() {
        // Sets up the default filters
        $this->default_filters();

		// set static properties in Kopa_Admin_Settings class
		Kopa_Admin_Settings::get_settings_pages();
		

		/**
		 * Add custom layout feature
		 * By default, just built-in post types and taxonomies have this feature
		 *   Built-in post types: post, page
		 *   Built-in taxonomies: category, post_tag
		 * Use this filter hook 'kopa_custom_layout_arguments' to customize
		 *  this feature for custom post types and custom taxonomies
		 */
		add_theme_support( 'kopa_custom_layout', apply_filters( 'kopa_custom_layout_arguments', array(
			array(
				'screen'   => 'post',
				'taxonomy' => false,
				'layout'   => 'post-layout',
			),
			array(
				'screen'   => 'page',
				'taxonomy' => false,
				'layout'   => 'page-layout',
			),
			array(
				'screen'   => 'category',
				'taxonomy' => true,
				'layout'   => 'blog-layout',
			),
			array(
				'screen'   => 'post_tag',
				'taxonomy' => true,
				'layout'   => 'blog-layout',
			),					
		) ) );
	}

	/**
	 * Include core widgets and register sidebars
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function include_widgets() {
		

		$sidebar_settings = kopa_get_sidebar_arguments();

		if ( is_null( $sidebar_settings ) ) {
			return;
		}

		if ( ! isset( $sidebar_settings['id'] ) ) {
			return;
		}

		$sidebars = kopa_get_option( $sidebar_settings['id'] );

		if ( empty( $sidebars ) ) {
			return;
		}

		if ( $sidebars && is_array( $sidebars ) ) {
			foreach ( $sidebars as $sidebar_id => $sidebar_args ) {
				$sidebar_args['id'] = $sidebar_id;

				// must sanitize and validate
				// if ( empty( $sidebar_args['name'] ) ) {
				// 	$sidebar_args['name'] = $sidebar_id;
				// }

				/**
				 * Check before_widget, after_widget, before_title, after_title
				 * before registering
				 * If empty, use the default common attributes
				 */
				foreach ( $sidebar_settings['default_atts'] as $key => $val ) {
					// if attribute is empty and default common attribute is not empty
					// use default common attribute value
					if ( empty( $sidebar_args[ $key ] ) && ! empty( $val ) ) {
						$sidebar_args[ $key ] = $val;
					}

					// if still empty after checking,
					// unset this attribute to use default value of wordpress core
					if ( empty( $sidebar_args[ $key ] ) ) {
						unset( $sidebar_args[ $key ] );
					}
				}

				register_sidebar( $sidebar_args );
			}
		}
	}

	/**
	 * Support for mim-types for upload
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function more_mimes( $mimes ) {
		$mimes['eot']  = 'font/eot';
		$mimes['woff'] = 'font/woff';
		$mimes['ttf']  = 'font/truetype';
		$mimes['svg']  = 'font/svg';
		return $mimes;
	}

    public function meta_box_wrap_start($wrap, $value, $loop_index){
        if(0 == $loop_index){
            $wrap = '<div class="kopa-metabox-wrap kopa-metabox-wrap-first kopa-row">';
        }else{
            $wrap = '<div class="kopa-metabox-wrap kopa-row">';
        }

        if ( $value['title'] ) {
            $wrap .= '<div class="kopa-col-xs-3">';
            $wrap .= esc_html($value['title']);
            $wrap .= '</div>';
            $wrap .= '<div class="kopa-col-xs-9">';
        }else{
            $wrap .= '<div class="kopa-col-xs-12">';
        }

        return $wrap;
    }

    public function meta_box_wrap_end($wrap, $value, $loop_index){
        $wrap = '';

        if ( $value['desc'] ) {
            $wrap .= '<p class="kopa-help">'. $value['desc'] . '</p>';
        }

        $wrap .= '</div>';
        $wrap .= '</div>';

        return $wrap;
    }

    public function kopa_sanitize_option_datetime( $option_value, $value ) {
        $option_value = strtotime($option_value);
        return $option_value;
    }

	/**
	 * Live update when add new layouts (also their sidebars)
	 * or add new positions into existed layouts
	 *
	 * @param array $option_value The current layout data in database
	 * @param array $option The layout_manager option arguments
	 * @return array $option_value The merge data between data in database
	 *     and data in code
	 *
	 * @see Kopa_Admin_Settings::get_option() in class-kopa-admin-settings.php
	 * @see class-kopa-layout-post-type.php
	 * @see class-kopa-layout-taxonomy.php
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function live_update_layout_manager( $option_value, $option ) {
		if ( isset( $option_value['sidebars'] ) && isset( $option['default']['sidebars'] ) ) {
			// for updating new layouts and their sidebars
			$option_value['sidebars'] = wp_parse_args( $option_value['sidebars'], $option['default']['sidebars'] );
			
			// for updating new positions of existed layouts
			foreach ( $option['layouts'] as $layout_id => $layout_args ) {
				if ( isset( $option_value['sidebars'][ $layout_id ] ) && isset( $option['default']['sidebars'][ $layout_id ] ) ) {
					
					$option_value['sidebars'][ $layout_id ] = wp_parse_args( $option_value['sidebars'][ $layout_id ], $option['default']['sidebars'][ $layout_id ] );
				}
			}
		}

		return $option_value;
	}

	/** Utility functions ******************************************************/

	/**
	 * Get the framework url.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function framework_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the framework path.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function framework_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
}

} // class_exists check

if ( ! function_exists( 'KF' ) ) {

	function KF() {
		return Kopa_Framework::instance();
	}

} // function_exists check

/**
 * Init Kopa_Framework class
 */
$GLOBAL['kopa_framework'] = KF();