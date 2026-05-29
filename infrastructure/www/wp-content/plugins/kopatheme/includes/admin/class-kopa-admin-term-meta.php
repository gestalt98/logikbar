<?php

/**
 * Kopa Framework Term_Meta
 *
 * This module allows you to define custom metabox for built-in or custom taxonomy
 *
 * @author 		Kopatheme
 * @category 	Term Meta
 * @package 	KopaFramework
 * @since       1.0.11
 */
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( !class_exists( 'Kopa_Admin_Term_Meta' ) ) {

	/**
	 * Kopa_Admin_Term_Meta Class
	 */
	class Kopa_Admin_Term_Meta {

		/**
		 * @access private
		 * @var array meta boxes settings
		 */
		private $settings = array();

		/**
		 *
		 * @var string
		 * @access protected
		 * $since 1.0
		 */
		protected $form_type;

		/**
		 * Constructor
		 *
		 * @since 1.0.5
		 * @access public
		 */
		public function __construct( $settings ) {
			$this->settings = $settings;
			$this->add_meta_boxes();
			add_action( 'admin_enqueue_scripts', array( $this, 'meta_box_scripts' ) );
		}

		public function meta_box_scripts() {
			wp_enqueue_script( 'kopa_media_uploader' );
			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}
		}

		function add_meta_boxes() {

			$metabox = $this->settings;

			foreach ( (array) $metabox['pages'] as $page ) {
				add_action( $page . '_add_form_fields', array( $this, 'output_new_form' ), 10, 2 );
				add_action( $page . '_edit_form_fields', array( $this, 'output_edit_form' ), 10, 2 );

				add_action( 'created_' . $page, array( $this, 'created_term_meta' ), 10, 2 );
				add_action( 'edited_' . $page, array( $this, 'edited_term_meta' ), 10, 2 );
			}
		}

		/**
		 * Adding Term form fields.
		 * 
		 * @param string $taxonomy The taxonomy slug.
		 */
		public function output_new_form( $taxonomy ) {

			$this->fields( '<div class="form-field term-group">%s', '</div>' );
		}

		/**
		 * Editing Term form fields are displayed.
		 * 
		 * @param object $tag      Current taxonomy term object.
		 * @param string $taxonomy The taxonomy slug.
		 */
		public function output_edit_form( $term, $taxonomy ) {
			$this->fields( '<tr class="form-field term-group-wrap"><th scope="row">%s</th><td>', '</td></tr>', $term->term_id );
		}

		/**
		 * Taxonomy fields
		 * @since 1.1.9
		 * 
		 * @param string $wrap_start Markup Html to start a field
		 * @param string $wrap_end Markup Html to close a field
		 * @param int $term_id
		 */
		public function fields( $_wrap_start, $_wrap_end, $term_id = 0 ) {

			/* Use nonce for verification */
			$output = sprintf( '<input type="hidden" name="%s_nonce" value="%s">', $this->settings['id'], wp_create_nonce( $this->settings['id'] ) );

			/**
			 * All Form Fields are available in framework
			 * @since 1.1.9
			 */
			global $kopa_form_fields;
			
			
			foreach ( $this->settings['fields'] as $settings ) {

				$settings['id'] = isset( $settings['id'] ) ? $settings['id'] : '';

				$value = '';
				if ( $term_id ) {
					$value = get_term_meta( $term_id, $settings['id'], true );
				}

				$label = !empty( $settings['title'] ) ? sprintf( '<label for="%s">%s</label>', $settings['id'], $settings['title'] ) : '';
				$description = !empty( $settings['desc'] ) ? sprintf( '<p class="description">%s</p>', $settings['desc'] ) : '';

				$wrap_start = sprintf( $_wrap_start, $label );

				$wrap_end = $description . $_wrap_end;

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
					$output .= apply_filters( 'kopa_admin_taxonomy_field_' . $settings['type'], '', $wrap_start, $wrap_end, $settings, $value );
				}
			}

			echo $output;
		}

		public function created_term_meta( $term_id, $tt_id ) {

			$metabox = $this->settings;

			/* don't save if $_POST is empty */
			if ( empty( $_POST ) )
				return $term_id;
			
			/* verify nonce */
			if ( !isset( $_POST[$metabox['id'] . '_nonce'] ) || !wp_verify_nonce( $_POST[$metabox['id'] . '_nonce'], $metabox['id'] ) )
				return $term_id;

			foreach ( $this->settings['fields'] as $value ) {
				if ( isset( $_POST[$value['id']] ) && '' !== $_POST[$value['id']] ) {

					// Get the option name
					$option_value = null;

					if ( isset( $_POST[$value['id']] ) ) {
						$option_value = $_POST[$value['id']];
					}

					// For a value to be submitted to database it must pass through a sanitization filter
					if ( has_filter( 'kopa_sanitize_option_' . $value['id'] ) ) {
						$option_value = apply_filters( 'kopa_sanitize_option_' . $value['id'], $option_value, $value );
					}

					if ( !is_null( $option_value ) ) {
						add_term_meta( $term_id, $value['id'], $option_value, true );
					}
				}
			}
			return true;
		}

		public function edited_term_meta( $term_id, $tt_id ) {
			$metabox = $this->settings;

			/* don't save if $_POST is empty */
			if ( empty( $_POST ) )
				return $term_id;

			
			/* verify nonce */
			if ( !isset( $_POST[$metabox['id'] . '_nonce'] ) || !wp_verify_nonce( $_POST[$metabox['id'] . '_nonce'], $metabox['id'] ) )
				return $term_id;

			foreach ( $this->settings['fields'] as $value ) {
				// Get the option name
				$option_value = null;

				if ( isset( $_POST[$value['id']] ) ) {
					$option_value = $_POST[$value['id']];
				}

				// For a value to be submitted to database it must pass through a sanitization filter
				if ( has_filter( 'kopa_sanitize_option_' . $value['id'] ) ) {
					$option_value = apply_filters( 'kopa_sanitize_option_' . $value['id'], $option_value, $value );
				}

				if ( !is_null( $option_value ) ) {
					update_term_meta( $term_id, $value['id'], $option_value );
				}
			}
			return true;
		}

	}

}