<?php
/**
 * Kopa Framework Formatting Functions
 *
 * Functions for formatting data.
 *
 * @author      Kopatheme
 * @category    Core
 * @package     KopaFramework/Functions
 * @since       1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Sanitize a string or an array from user input or from the db.
 *
 * @uses sanitize_text_field()
 * @see http://codex.wordpress.org/Function_Reference/sanitize_text_field
 *
 * @param string|array $value input data
 * @return string|array $value clean version of input data
 *
 * @since 1.0.0
 */
function kopa_clean( $value ) {
	if ( is_array( $value ) ) {
		$value = array_map( 'kopa_clean', $value );
	} elseif ( is_string( $value ) ) {
		$value = sanitize_text_field( $value );
	}

	return $value;
}

add_filter( 'kopa_sanitize_option_gallery_sortable', 'kopa_sanitize_option_gallery_sortable_fn', 10, 2 );

function kopa_sanitize_option_gallery_sortable_fn( $items, $field ) {				
	if( !$items || !is_array( $items ) ) {
		$items = array();
	}					

	return $items;
}
