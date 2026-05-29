<?php
/**
 * KopaFramework Admin Functions
 *
 * @author      Kopatheme
 * @category    Core
 * @package     KopaFramework/Admin/Functions
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get all KopaFramework screen ids
 *
 * @return array list of setting page id
 *
 * @since 1.0.0
 */
function kopa_get_screen_ids() {
	$menu = Kopa_Admin_Menus::menu_settings();
	$menu_slug = empty( $menu['menu_slug'] ) ? 'kopa-framework' : $menu['menu_slug'];
	$kopa_screen_id = sanitize_title( $menu_slug );

    return apply_filters( 'kopa_screen_ids', array(
    	'appearance_page_' . $kopa_screen_id,
    ) );
}

/**
 * Register metabox
 *
 * @uses Kopa_Admin_Meta_Box class
 * @param array $args metabox settings
 *
 * @since 1.0.5
 */
if ( ! function_exists( 'kopa_register_metabox' ) ) {
	function kopa_register_metabox( $args = array() ) {

		if ( empty( $args ) ) {
			return;
		}

		new Kopa_Admin_Meta_Box( $args );

	}
}

/**
 * Register meta data for term
 *
 * @uses Kopa_Admin_Term_Meta class
 * @param array $args term meta settings
 *
 * @since 1.0.11
 */

if ( ! function_exists( 'kopa_register_term_meta' ) ) {
    function kopa_register_term_meta( $args = array() ) {
        if ( empty( $args ) ) {
            return;
        }

        if ( function_exists('add_term_meta') ) {
            new Kopa_Admin_Term_Meta( $args );
        } else {
            return;
        }
    }
}