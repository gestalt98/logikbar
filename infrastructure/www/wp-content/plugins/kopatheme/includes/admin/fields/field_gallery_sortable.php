<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Gallery Sortable
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.1.5
 * @return string - html string.
 */
function kopa_form_field_gallery_sortable( $wrap_start, $wrap_end, $settings, $value ) {
	$html = '<div class="kopa-ui-gallery" data-name="' . esc_attr( $settings['id'] ) . '">';
	$html .= '<ul>';

	$thumbnail_size = isset( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'thumbnail';

	if ( is_array( $value ) && $value ) {

		foreach ( $value as $image_id ) {

			$thumb = '';
			if ( $image_id ) {
				$image = wp_get_attachment_image_src( $image_id, $thumbnail_size );
				if ( isset( $image[0] ) ) {
					$thumb = $image[0];
				}
			}


			$html .= '<li class="kopa-ui-gallery__image">';
			$html .= '<input type="hidden" name="' . esc_attr( $settings['id'] ) . '[]" value="' . esc_attr( $image_id ) . '">';
			$html .= '<img src="' . esc_url( $thumb ) . '" alt="">';
			$html .= '<span class="kopa-ui-gallery__remove dashicons dashicons-trash"></span>';
			$html .= '</li>';
		}
	}

	$html .= '</ul>';

	$html .= '<p class="kopa-ui-gallery__placehold">';
	$html .= '<span class="kopa-ui-gallery__upload button button-secondary">' . esc_html__( 'Add new image', 'kopa-framework' ) . '</span>';
	$html .= '</p>';
	
	$html .= '</div>';

	$output = $wrap_start . $html . $wrap_end;

	return $output;
}