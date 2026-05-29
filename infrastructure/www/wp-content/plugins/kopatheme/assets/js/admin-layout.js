/**
 * Kopa Framework Admin Dynamic Select Layout
 * Author: Kopatheme
 * Copyright: 2014 Kopatheme
 * License: GPLv2 or later
 */
jQuery(document).ready(function($){

	// hide all properties of layout
	$('.kopa_section_select_area_container').hide();
	$('.kopa_section_layout_image').hide();

	// for the first time loaded
	$('.kopa_section_select_layout select').each( change_layout );
	
	// change widget areas section and layout image section
	function change_layout(e) {
		var $this = $(this),
			sectionID = $this.data('layout-section-id'),
			layoutID = $this.val() ? $this.val() : $this.find('option:first').val();

		// show properties of current selected layout
		$('#' + sectionID + '_' + layoutID).show()
			.siblings('.kopa_section_select_area_container').hide();
		$('#' + sectionID + '_' + layoutID + '_image').show()
			.siblings('.kopa_section_layout_image').hide();
	}

	// on change event
	$('.kopa_section_select_layout select').on('change', change_layout);
});