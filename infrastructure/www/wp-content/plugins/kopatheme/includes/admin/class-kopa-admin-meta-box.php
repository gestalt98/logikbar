<?php

/**
 * Kopa Framework Metabox
 *
 * This module allows you to define custom metabox for built-in or custom post types 
 *
 * @author 		Kopatheme
 * @category 	Metabox
 * @package 	KopaFramework
 * @since       1.0.5
 */
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( !class_exists( 'Kopa_Admin_Meta_Box' ) ) {

	/**
	 * Kopa_Admin_Meta_Box Class
	 */
	class Kopa_Admin_Meta_Box {

		/**
		 * @access private
		 * @var array meta boxes settings
		 */
		private $settings = array();

		/**
		 * Constructor
		 *
		 * @since 1.0.5
		 * @access public
		 */
		public function __construct( $settings ) {
			$this->settings = $settings;

			add_action( 'admin_enqueue_scripts', array( $this, 'meta_box_scripts' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );
		}

		/**
		 * Metabox scripts
		 * 
		 * @since 1.0.5
		 * @access public
		 */
		public function meta_box_scripts() {
			$screen = get_current_screen();
			$metabox = $this->settings;

			if ( in_array( $screen->id, (array) $metabox['pages'] ) ) {
				wp_enqueue_script( 'kopa_media_uploader' );
			}
		}

		/**
		 * Add metaboxes
		 *
		 * @since 1.0.5
		 * @access public
		 */
		public function add_meta_boxes() {
			$metabox = $this->settings;

			foreach ( (array) $metabox['pages'] as $page ) {
				add_meta_box( $metabox['id'], $metabox['title'], array( $this, 'output' ), $page, $metabox['context'], $metabox['priority'], $metabox['fields'] );
			}
		}

		/**
		 * Check if we're saving, the trigger an action based on the post type
		 *
		 * @param  int $post_id
		 * @param  object $post
		 * 
		 * @since 1.0.5
		 * @access public
		 */
		public function save_meta_boxes( $post_id, $post ) {
			$metabox = $this->settings;

			/* don't save if $_POST is empty */
			if ( empty( $_POST ) )
				return $post_id;

			/* don't save during autosave */
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;

			/* verify nonce */
			if ( !isset( $_POST[$metabox['id'] . '_nonce'] ) || !wp_verify_nonce( $_POST[$metabox['id'] . '_nonce'], $metabox['id'] ) )
				return $post_id;

			/* check permissions */
			if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id ) )
					return $post_id;
			} else {
				if ( !current_user_can( 'edit_post', $post_id ) )
					return $post_id;
			}

			// Options to update will be stored here
			$update_options = array();

			foreach ( $metabox['fields'] as $settings ) {
				if ( !isset( $settings['id'] ) ) {
					continue;
				}

				$type = isset( $settings['type'] ) ? sanitize_title( $settings['type'] ) : '';

				// Get the option name
				$value = null;

				if ( isset( $_POST[$settings['id']] ) ) {
					$value = $_POST[$settings['id']];
				}

				// For a value to be submitted to database it must pass through a sanitization filter
				if ( has_filter( 'kopa_sanitize_option_' . $type ) ) {
					$value = apply_filters( 'kopa_sanitize_option_' . $type, $value, $settings );
				}

				if ( !is_null( $value ) ) {
					$update_options[$settings['id']] = $value;
				}
			}

			// Now save the options
			foreach ( $update_options as $name => $settings ) {
				update_post_meta( $post_id, $name, $settings );
			}

			do_action( sprintf( 'kopa_%s_saved', $metabox['id'] ), $post_id, $post );

			return true;
		}

		/**
		 * Output meta box fields
		 *
		 * @since 1.0.5
		 * @access public
		 */
		public function output( $post, $args ) {


			$metabox = $this->settings;

			$wrap_start = '';
			$wrap_end = '';
			$output = '';

			$output .= '<div class="kopa-metabox-wrapper">';

			/* Use nonce for verification */
			$output .= '<input type="hidden" name="' . $metabox['id'] . '_nonce" value="' . wp_create_nonce( $metabox['id'] ) . '">';

			/* meta box description */
			if ( isset( $metabox['desc'] ) && !empty( $metabox['desc'] ) ) {
				$allowed_tags = array(
					'abbr' => array( 'title' => true ),
					'acronym' => array( 'title' => true ),
					'code' => true,
					'em' => true,
					'strong' => true,
					'a' => array(
						'href' => true,
						'title' => true,
					),
				);
				$metabox['desc'] = wp_kses( $metabox['desc'], $allowed_tags );
				$output .= '<p>' . $metabox['desc'] . '</p>';
			}

			$_loop_index = 0;


			/**
			 * All Form Fields are available in framework
			 * @since 1.1.9
			 */
			global $kopa_form_fields;

			foreach ( $metabox['fields'] as $settings ) {

				if ( !isset( $settings['type'] ) )
					continue;

				$settings = Kopa_Admin_Settings::sanitize_option_arguments( $settings );

				$wrap_start = '<p>';
				if ( $settings['title'] ) {
					$wrap_start .= '<strong>' . esc_html( $settings['title'] ) . '</strong>';
					$wrap_start .= '<br>';
				}
				if ( $settings['desc'] ) {
					$wrap_start .= '<span>' . $settings['desc'] . '</span>';
					$wrap_start .= '<br>';
				}

				$wrap_end = '</p>';

				$wrap_start = apply_filters( 'kopa_admin_meta_box_wrap_start', $wrap_start, $settings, $_loop_index );
				$wrap_end = apply_filters( 'kopa_admin_meta_box_wrap_end', $wrap_end, $settings, $_loop_index );



				/**
				 * @deprecated since 1.1.9
				 * 
				 * $advanced_field = apply_filters( 'kopa_admin_metabox_advanced_field', false );
				 */
				
				/**
				 * Field value
				 */
				$value = get_post_meta( $post->ID, $settings['id'] );
				if ( empty( $value ) ) {
					$value = $settings['default'];
				} elseif ( isset( $value[0] ) ) {
					$value = $value[0];
				} else {
					$value = '';
				}

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
					$output .= apply_filters( 'kopa_admin_meta_box_field_' . $settings['type'], '', $wrap_start, $wrap_end, $settings, $value );
				}

				$_loop_index++;
			}

			$output .= '</div>'; // .kopa-metabox-wrapper
			//
			// finally, output fields
			echo $output;
		}

	}

	// end class Kopa_Admin_Meta_Box
}