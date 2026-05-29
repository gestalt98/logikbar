<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Image
 * 
 * @author vutuansw
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.1.9
 * @return string - html string.
 */
function kopa_form_field_image( $wrap_start, $wrap_end, $settings, $value ) {
	ob_start();

	echo $wrap_start;

	$thumbnail_id = absint( $value );
	$image_url = '';
	$hasimage = '';

	if ( $thumbnail_id ) {
		$image_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );
		$hasimage = 'hasimage';
	}

	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	} else {
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
	}
	?>

	<div class="kopa-field kopa-field-image <?php echo esc_attr( $hasimage ) ?>">
		<a href="#" class="item-add" title="<?php echo esc_html__( 'Select an image', 'hub-toolkit' ) ?>">
			<?php
			printf( '<div class="img" style="background-image:url(%s)"></div>', esc_url( $image_url ) );
			?>
		</a>
		<input id="<?php echo esc_attr( $settings['id'] ) ?>" name="<?php echo esc_attr( $settings['id'] ) ?>" type="hidden" value="<?php echo esc_attr( $thumbnail_id ) ?>"/>
		<button class="item-remove"></button>
	</div>

	<?php
	echo $wrap_end;

	return ob_get_clean();
}
