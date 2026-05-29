<?php
/**
 * Kopa Settings Options Functions
 *
 * @author      Kopatheme
 * @category    Core
 * @package     KopaFramework/Functions
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get a setting from the settings API.
 * 
 * @uses Kopa_Admin_Settings::get_option()
 *
 * @param string $option_name option id
 * @param mixed $default default value
 * @return string|array option value
 *
 * @since 1.0.0
 */
function kopa_get_option( $option_name, $default = null ) {
    // needs to set options key first
    return Kopa_Admin_Settings::get_option( $option_name, $default );
}

/**
 * Get sidebar settings
 *
 * @uses Kopa_Admin_Settings::get_settings_arguments()
 *
 * @return array $option the first sidebar_manager setting arguments
 *
 * @since 1.0.0
 */
function kopa_get_sidebar_arguments() {
	$options_settings = Kopa_Admin_Settings::get_settings_arguments();

	foreach ( $options_settings as $option ) {
		if ( isset( $option['type'] ) && isset( $option['id'] ) && 'sidebar_manager' === $option['type'] ) {
			return $option;
		}
	}

	return null;
}

/**
 * Get template settings
 * 
 * @uses kopa_get_option()
 *
 * @return array layout settings
 *
 * @since 1.0.0
 */
function kopa_get_template_setting() {
	$setting_id = '';
	$setting = null;
	$return_data = null;

	// get custom layout of post types
	if ( ! is_home() && ( is_singular() || is_front_page() ) ) {
		$metas = get_theme_mod( '_custom_layout_posts' );
		$post_id = get_queried_object_id();
		if ( isset( $metas[ $post_id ] ) ) {
			$meta = $metas[ $post_id ];
			if ( ! empty( $meta['use'] ) && ! empty( $meta['data'] ) ) {
				$setting_id = $meta['use'];
				$setting = $meta['data'];
			}
		}
	}
	// get custom layout of taxonomies
	elseif ( is_category() || is_tag() || is_tax() ) {
		$metas = get_theme_mod( '_custom_layout_taxonomies' );
		$term_id = get_queried_object_id();
		if ( isset( $metas[ $term_id ] ) ) {
			$meta = $metas[ $term_id ];
			if ( ! empty( $meta['use'] ) && ! empty( $meta['data'] ) ) {
				$setting_id = $meta['use'];
				$setting = $meta['data'];
			}
		}
	}

	if ( empty( $setting_id ) ) {
		// default layouts
		if ( ! is_home() && is_front_page() ) {
			$setting_id = 'frontpage-layout';
		} elseif ( is_home() || is_archive() ) {
			$setting_id = 'blog-layout';
		} elseif ( is_page() ) {
			$setting_id = 'page-layout';
		} elseif ( is_singular() ) {
			$setting_id = 'post-layout';
		} elseif ( is_search() ) {
			$setting_id = 'search-layout';
		} elseif ( is_404() ) {
			$setting_id = 'error404-layout';
		}
	}

	// custom id for custom layout (custom post type...)
	$setting_id = apply_filters( 'kopa_custom_template_setting_id', $setting_id );

	// check empty or not
	// if $setting is set above for the custom layout, don't set it again
	// if not set it
	if ( $setting_id && empty( $setting ) ) {
		$setting = kopa_get_option( $setting_id );
	}

	$setting = apply_filters( 'kopa_custom_template_setting', $setting, $setting_id);

	// live update layout manager
	if ( $setting_id ) {
		$option = Kopa_Admin_Settings::get_option_arguments( $setting_id );
		$setting = apply_filters( 'kopa_get_option_layout_manager', $setting, $option );
	}

	// filter the return data
	if ( $setting ) {
		$current_layout_id = '';
		if ( ! empty( $setting['layout_id'] ) ) {
			$current_layout_id = $setting['layout_id'];
		}

		if ( $current_layout_id ) {
			$return_data['layout_id'] = $current_layout_id;
			$return_data['sidebars'] = isset( $setting['sidebars'][ $current_layout_id ] ) ? $setting['sidebars'][ $current_layout_id ] : array();
		}
	}

	return $return_data;
}