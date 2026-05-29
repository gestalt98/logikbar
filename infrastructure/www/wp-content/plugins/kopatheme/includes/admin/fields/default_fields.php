<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * KopaFramework default attributes functions for rendering.
 *
 * @author 		vutuansw
 * @category 	Fields
 * @package 	KopaFramework/Admin
 * @since       1.1.9
 */

/**
 * Textfield
 *
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_text( $wrap_start, $wrap_end, $settings, $value ) {
	if ( $settings['type'] != 'password' ) {
		$settings['type'] = 'text';
	}
	$class = 'large-text';
	if ( isset( $value['class'] ) && !empty( $value['class'] ) ) {
		$class = esc_attr( $value['class'] );
	}

	$output = $wrap_start;
	$output .= '<input 
							class="' . esc_attr( $class ) . '" 
							type="' . esc_attr( $settings['type'] ) . '" 
							name="' . esc_attr( $settings['id'] ) . '" 
							id="' . esc_attr( $settings['id'] ) . '" 
							value="' . esc_attr( $value ) . '">';
	$output .= $wrap_end;

	return $output;
}

/**
 * Number
 * 
 * @see kopa_form_field_text
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_number( $wrap_start, $wrap_end, $settings, $value ) {
	return kopa_form_field_text( $wrap_start, $wrap_end, $settings, $value );
}

/**
 * Url
 * 
 * @see kopa_form_field_text
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_url( $wrap_start, $wrap_end, $settings, $value ) {
	return kopa_form_field_text( $wrap_start, $wrap_end, $settings, $value );
}

/**
 * Email
 * 
 * @see kopa_form_field_text
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_email( $wrap_start, $wrap_end, $settings, $value ) {
	return kopa_form_field_text( $wrap_start, $wrap_end, $settings, $value );
}

/**
 * Password
 * 
 * @see kopa_form_field_text
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_password( $wrap_start, $wrap_end, $settings, $value ) {
	return kopa_form_field_text( $wrap_start, $wrap_end, $settings, $value );
}

/**
 * Textarea
 *
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_textarea( $wrap_start, $wrap_end, $settings, $value ) {
	$rows = !empty( $settings['rows'] ) ? 'rows="' . $settings['rows'] . '"' : '';
	
	$class = 'large-text';
	if ( isset( $value['class'] ) && !empty( $value['class'] ) ) {
		$class = esc_attr( $value['class'] );
	}

	$output = '';
	$output.=$wrap_start;
	$output .= sprintf( '<textarea class="%4$s" type="text" id="%1$s" name="%1$s" %2$s>%3$s</textarea>', $settings['id'], $rows, esc_textarea( $value ), $class );
	$output.=$wrap_end;

	return $output;
}

/**
 * Select/MultiSelect
 *
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_select( $wrap_start, $wrap_end, $settings, $value ) {
	$attrs = array();
	$attrs[] = $settings['type'] == 'multiselect' ? ' multiple="multiple"' : '';
	$attrs[] = isset( $settings['size'] ) ? " size='{$settings['size']}'" : '';
	$attrs[] = " id='{$settings['id']}'";
	$attrs[] = $settings['type'] == 'multiselect' ? " name='{$settings['id']}[]'" : " name='{$settings['id']}'";

	$options = '';
	$output ='';
	if ( !empty( $settings['options'] ) ) {
		foreach ( $settings['options'] as $key => $text ) {

			if ( is_array( $value ) ) {
				$selected = selected( in_array( $key, $value ), true, false );
			} else {
				$selected = selected( $key, $value, false );
			}

			$options .= sprintf( '<option value="%s" %s>%s</option>', $key, $selected, $text );
		}
	}
	$output.=$wrap_start;
	$output.=sprintf( '<select %1$s>%2$s</select>', implode( '', $attrs ), $options );
	$output.=$wrap_end;

	return $output;
}

/**
 * Multiselect
 * 
 * @see kopa_form_field_select
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_multiselect( $wrap_start, $wrap_end, $settings, $value ) {
	return kopa_form_field_select( $wrap_start, $wrap_end, $settings, $value );
}

/**
 * Checkbox/Checkbox Group
 *
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_checkbox( $wrap_start, $wrap_end, $settings, $value ) {
	$settings = wp_parse_args( $settings, array(
		'label' => '',
			) );
	$output = '';
	if ( !isset( $settings['checkboxgroup'] ) ||
			( isset( $settings['checkboxgroup'] ) && 'start' == $settings['checkboxgroup'] ) ) {
		$output .= $wrap_start;
	}

	$fold = '';
	if ( array_key_exists( 'folds', $settings ) ) {
		$fold = 'kopa_fold ';
	}

	$output .= '<label>';
	$output .= '<input 
						class="' . $fold . '" 
	    				style="' . esc_attr( $settings['css'] ) . '" 
						type="' . esc_attr( $settings['type'] ) . '" 
						name="' . esc_attr( $settings['id'] ) . '" 
						id="' . esc_attr( $settings['id'] ) . '" value="1"' .
			( checked( $value, 1, false ) ) .
			'>';
	$output .= ' ' . esc_html( $settings['label'] ) . '</label>';
	$output .= '<br>';

	if ( !isset( $settings['checkboxgroup'] ) ||
			( isset( $settings['checkboxgroup'] ) && 'end' == $settings['checkboxgroup'] ) ) {
		$output .= $wrap_end;
	}

	return $output;
}

/**
 * Multi Checkbox
 *
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_multicheck( $wrap_start, $wrap_end, $settings, $value ) {
	$output = $wrap_start;
	if ( isset( $settings['options'] ) ) {
		foreach ( $settings['options'] as $key => $val ) {
			$name = $settings['id'] . '[' . $key . ']';
			$checked = isset( $value[$key] ) ? checked( $value[$key], 1, false ) : '';

			$output .= '<label>';
			$output .= '<input 
								type="checkbox" 
								name="' . esc_attr( $name ) . '" 
								id="' . esc_attr( $settings['id'] ) . '" 
								value="1" ' . $checked .
					'>';
			$output .= ' ' . esc_html( $val ) . '</label>';
			$output .= '<br>';
		}
	}
	$output .= $wrap_end;
	return $output;
}

/**
 * Multi Checkbox
 *
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.5
 * @return string - html string.
 */
function kopa_form_field_radio( $wrap_start, $wrap_end, $settings, $value ) {
	
	$output= $wrap_start;

	foreach ( $settings['options'] as $key => $val ) {
		$output .= '<label>';
		$output .= '<input 
								type="' . esc_attr( $settings['type'] ) . '" 
								name="' . esc_attr( $settings['id'] ) . '" 
								id="' . esc_attr( $settings['id'] . '_' . $key ) . '" 
								value="' . esc_attr( $key ) . '" ' .
				checked( $key, $value, false ) . '>';
		$output .= ' ' . esc_html( $val ) . '</label>';
		$output .= '<br>';
	}

	$output .= $wrap_end;
	return $output;
}

/**
 * Datetime
 * 
 * An input datetime
 * 
 * @param string $wrap_start Start Container of the field
 * @param string $wrap_end End Container of the field
 * @param array $settings see Kopa_Admin_Settings::sanitize_option_arguments()
 * @param $value
 *
 * @since 1.0.11
 * @return string - html string.
 */
function kopa_form_field_datetime( $wrap_start, $wrap_end, $settings, $value ) {

	$settings = wp_parse_args( $settings, array(
		'format' => 'Y/m/d',
		'datepicker' => 1,
		'timepicker' => 0,
	) );
	
	$value = date( $settings['format'], $value );
	$output='';
	$output .= $wrap_start;
	$output .= '<input
										class="medium-text kopa-framework-datetime"
										type="' . esc_attr( $settings['type'] ) . '"
										name="' . esc_attr( $settings['id'] ) . '"
										id="' . esc_attr( $settings['id'] ) . '"
										value="' . esc_attr( $value ) . '"
										data-timepicker="' . esc_attr( $settings['timepicker'] ) . '"
										data-datepicker="' . esc_attr( $settings['datepicker'] ) . '"
										data-format="' . wp_kses_post( $settings['format'] ) . '"
										autocomplete="off">';
	$output .= $wrap_end;

	return $output;
}
