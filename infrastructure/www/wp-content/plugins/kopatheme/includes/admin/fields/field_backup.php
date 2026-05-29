<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Import
 * 
 * @author kopatheme <http://kopatheme.com>
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function kopa_form_field_import( $wrap_start, $wrap_end, $settings, $value ) {
	$output = $wrap_start;
	$output .= '<input type="file" name="kopa_import_file" size="25">';
	$output .= '<input type="submit" class="button-secondary kopa_import" name="kopa_backup_import" value="' . esc_attr__( 'Import', 'kopa-framework' ) . '">';
	$output .= $wrap_end;
	return $output;
}

/**
 * Export
 * 
 * @author kopatheme <http://kopatheme.com>
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.0
 * @return string - html string.
 */
function kopa_form_field_export( $wrap_start, $wrap_end, $settings, $value ) {
	$output = $wrap_start;

	foreach ( $settings['options'] as $key => $val ) {
		$output .= '<label><input value="' . esc_attr( $key ) . '" type="radio" name="kopa_export_type" ' . checked( $settings['default'], $key, false ) . '> ' . esc_html( $val ) . '</label><br>';
	}

	$output .= '<input type="submit" class="button-secondary kopa_export" name="kopa_backup_export" value="' . esc_attr__( 'Download Export File', 'kopa-framework' ) . '">';

	$output .= $wrap_end;
	
	return $output;
}
