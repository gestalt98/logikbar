/**
 * Kopa Framework Custom Layout
 * Author: Kopatheme
 * Copyright: 2014 Kopatheme
 * License: GPLv2 or later
 */
jQuery(document).ready(function($){
	// enable or disable select fields in custom layout box
	// when checkbox '.kopa_use_custom_layout' is checked or not
	function enable_disable_custom_layout() {
		if ( this.checked ) {
			$('.kopa_section_group_layout').find('select').removeAttr('disabled');
		} else {
			$('.kopa_section_group_layout').find('select').attr('disabled', 'disabled');
		}
	}

	// init
	$('.kopa_use_custom_layout').each( enable_disable_custom_layout );

	// bind events
	$('.kopa_use_custom_layout').on('click', enable_disable_custom_layout);
});