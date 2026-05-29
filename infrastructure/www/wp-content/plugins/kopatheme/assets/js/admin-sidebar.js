/**
 * Kopa Framework Admin Dynamic Sidebar JS
 * Author: Kopatheme
 * Copyright: 2014 Kopatheme
 * License: GPLv2 or later
 * Used localized variable: kopa_sidebar_attributes_l10n
 */
jQuery(document).ready(function($){

	/**
	 * Check sidebar name field is empty or not
	 * if not empty remove inactive class
	 * if empty add inactive class
	 */
	function check_sidebar_name_field(e) {
		var $this = $(this),
			$addButton = $this.siblings('.kopa_sidebar_add_button');

		if ( $this.val() ) {
			$addButton.removeClass('kopa_button_inactive')
			return;
		}

		$addButton.addClass('kopa_button_inactive');

		return;
	}

	function toggle_edit_sidebar(e) {
		e.preventDefault();

		var $this = $(this),
			$headerAction = $this.find('.kopa_sidebar_title_action');

		// add class 'kopa_action_close' to title action when body is showed
		if ( $headerAction.hasClass('kopa_action_close') ) {
			$headerAction.removeClass('kopa_action_close');
		} 
		// and remove it from title action when body is hidden
		else {
			$headerAction.addClass('kopa_action_close');
		}

		$this.siblings('.kopa_sidebar_body').slideToggle('fast');
	}

	function close_edit_sidebar(e) {
		e.preventDefault();

		var $this = $(this),
			$sidebarBody = $this.parents('.kopa_sidebar_body'),
			$sidebarHeader = $sidebarBody.siblings('.kopa_sidebar_header'),
			$headerAction = $sidebarHeader.find('.kopa_sidebar_title_action');
		
		$sidebarBody.slideUp('fast');

		// remove class 'kopa_action_close' from title action when body is hidden
		$headerAction.removeClass('kopa_action_close');
	}

	// add sidebar
	function add_sidebar (e) {
		e.preventDefault();

		var $this = $(this),
			$sidebarNameField = $this.siblings('.kopa_sidebar_add_field'),
			sidebarName = $sidebarNameField.val(),
			sidebarCount = $this.data('registered-sidebars') + 1,
			registerSidebarIDs = $this.data('register-sidebar-ids'),
			sidebarID = 'sidebar-' + sidebarCount,
			name = $this.data('name'),
			containerID = $this.data('container-id'),
			$container = $( '#' + containerID ),
			newElement = '';

		if (  $this.hasClass('kopa_button_inactive') || '' === sidebarName ) {
			return;
		}

		// check sidebar id
		// if already existed, increase sidebarCount by 1 and
		// change sidebarID
		while ( registerSidebarIDs.search( sidebarID ) >= 0 ) {
			sidebarCount++;
			sidebarID = 'sidebar-' + sidebarCount;
		}

		newElement = '<li class="kopa_sidebar">';
		newElement += '<div class="kopa_sidebar_header">';
			newElement += '<div class="kopa_sidebar_title_action"></div>';
			newElement += '<strong>' + sidebarName + '</strong>';
		newElement += '</div>';

		newElement += '<div class="kopa_sidebar_body">';
			var attributes = kopa_sidebar_attributes_l10n.attributes;
			
			// folding checkbox for advanced settings
			newElement += '<label><input class="kopa_sidebar_advanced_settings" type="checkbox"> ' + kopa_sidebar_attributes_l10n.advanced_settings + '</label>';
			
			for ( var key in attributes ) {
				newElement += '<div class="kopa_sidebar_' + key + '">';
				newElement += '<input type="text" class="kopa_sidebar kopa_sidebar_attr" name="' + name + '[' + sidebarID + '][' + key + ']" id="kopa_sidebar_' + sidebarCount + '_' + key + '" ' + ( 'name' === key ? 'value="' + sidebarName + '"' : '' ) + ' placeholder="' + attributes[key] + '">';
				newElement += '</div>';
			}

			newElement += '<div class="kopa_sidebar_control_actions">';
			newElement += '<a class="kopa_sidebar_delete_button" href="#">' + kopa_sidebar_attributes_l10n.remove + '</a>';
			newElement += ' | ';
			newElement += '<a class="kopa_sidebar_close_button" href="#">' + kopa_sidebar_attributes_l10n.close + '</a>';
			newElement += '<span class="spinner"></span>';
			newElement += '</div>';
		newElement += '</div>';
		newElement += '</li>';

		// append the new sidebar as the last item of sidebar list
		$container.append(newElement);

		var $newElement = $container.children('li:last')
			viewportTop = $(window).scrollTop(),
			viewportBottom = $(window).scrollTop() + $(window).height(),
			sidebarBounds = $newElement.offset(),
			$newElementHeader = $newElement.children('.kopa_sidebar_header'),
			$newElementBody = $newElement.children('.kopa_sidebar_body'),
			$newElementTitleAction = $newElement.find('.kopa_sidebar_title_action'),
			$newElementCloseButton = $newElement.find('.kopa_sidebar_close_button'),
			$newElementDeleteButton = $newElement.find('.kopa_sidebar_delete_button'),
			$newElementAdvancedButton = $newElement.find('.kopa_sidebar_advanced_settings');

		$newElementTitleAction.addClass('kopa_action_close');

		if ( viewportBottom < sidebarBounds.top ) {
			$('html, body').animate({
				scrollTop: $newElement.offset().top - 130
			}, {
				duration: 200,
				complete: function () {
					$newElementBody.slideDown('fast');
				}
			});
		} else {
			$newElementBody.slideDown('fast');
		}

		$this.data('registered-sidebars', sidebarCount);

		// after appending completely, make sidebar name field empty
		// and make add button inactive
		$sidebarNameField.val('');
		$this.addClass('kopa_button_inactive');

		// bind events for items of new sidebar
		// toggle show/hide for close button
		$newElementHeader.on('click', toggle_edit_sidebar);
		// close sidebar button
		$newElementCloseButton.on('click', close_edit_sidebar);
		// remove sidebar button
		$newElementDeleteButton.on('click', remove_sidebar);
		// folding for advanced settings checkbox
		$newElementAdvancedButton.on('click', folding_advanced_settings);

		return;
	}

	// send ajax request to check before removing sidebar
	function remove_sidebar(e) {
		e.preventDefault();

		var $this = $(this),
			sidebarID = $this.data('sidebar-id'),
			confirmMessage = '<strong>' + kopa_sidebar_attributes_l10n.confirm_message + '</strong>';
		
		// check empty sidebarID
		// determines this is new sidebar which has just been added
		// or registered sidebar
		if ( sidebarID ) {
	
			// send ajax request to check the sidebar
			// is currently using in layout or containing widgets
			$.ajax({
				url: kopa_sidebar_attributes_l10n.ajax_url,
				type: 'POST',
				data: {
					action: 'kopa_remove_sidebar',
					sidebar_id: sidebarID
				},
				beforeSend: function () {
					$this.siblings('.spinner').show(); // indicate loading before sending
				},
				success: function ( responses ) {
					$this.siblings('.spinner').hide(); // hide loading when complete

					responses = $.parseJSON( responses );

					var allowDelete = responses.allow_delete,
						warnings = responses.warnings,
						errors = responses.errors,
						messages = '';

					if ( allowDelete ) {
						warnings.push( confirmMessage );
						messages = warnings.join("<br>");

						open_modal( 'confirm', $this, kopa_sidebar_attributes_l10n.warning, messages );
						
						// if ( confirm( messages ) ) {
						// 	$this.parents('.kopa_sidebar').slideUp( function () {
						// 		$(this).remove();
						// 	} );
						// }
					} else {
						messages += errors.join("<br>");
						open_modal( 'alert', $this, kopa_sidebar_attributes_l10n.error, messages );
						// alert( messages );
					} // end check allow delete or not

				}
			});
	
		} else {
	
			open_modal( 'confirm', $this, kopa_sidebar_attributes_l10n.info, confirmMessage );

			// if ( confirm( confirmMessage ) ) {
			// 	// for the new sidebar which has just been added
			// 	// delete immediately without ajax checking
			// 	$this.parents('.kopa_sidebar').slideUp( function () {
			// 		$(this).remove();
			// 	} );
			// }

		} // end check empty sidebarID
	}

	// open modal
	function open_modal( type, selector, title, messages ) {
		var $modalSection = $( '#kopa_modal_info' ),
			$modalConfirmSection = $modalSection.find( '#kopa_modal_confirm' ),
			$modalConfirmOK = $modalConfirmSection.find( '#kopa_confirm_ok' ),
			$modalConfirmCancel = $modalConfirmSection.find( '#kopa_confirm_cancel' );

		if ( 'confirm' != type ) {
			$modalConfirmSection.hide();
		} else {
			$modalConfirmSection.show();
		}
		
		// insert messages
		$modalSection.find( '.kopa_modal_info' ).empty().append(messages);
		
		// show modal box
		tb_show( title, '#TB_inline?width=450&height=420&inlineId=kopa_modal_info' );

		// bind event for buttons in modal box
		$modalConfirmOK.off( 'click', close_modal )
			.on( 'click', {allowDelete: true, selector: selector}, close_modal );
		
		$modalConfirmCancel.off( 'click', close_modal )
			.on( 'click', {allowDelete: false, selector: selector}, close_modal );

	}

	// close modal
	function close_modal( e ) {
		e.preventDefault();

		var allowDelete = e.data.allowDelete,
			selector = e.data.selector;
		
		if ( allowDelete ) {
			selector.parents('.kopa_sidebar').slideUp( 'fast', function () {
				$(this).remove();
			} );
		}

		tb_remove();
	}

	// advanced settings checkbox
	function folding_advanced_settings(e) {
		var $this = $(this);

		$this.parent().siblings('.kopa_sidebar_before_widget, .kopa_sidebar_after_widget, .kopa_sidebar_before_title, .kopa_sidebar_after_title').slideToggle('fast');
	}

	// sortable sidebar
	$('.kopa_sidebar_sortable').sortable({
		handle: '.kopa_sidebar_header'
	});

	/**
	 * Bind events
	 */
	$('.kopa_sidebar_add_field').on('keyup', check_sidebar_name_field);
	
	// add new dynamic sidebar button click event
	$('.kopa_sidebar_add_button').on('click', add_sidebar);

	// toggle show/hide for edit button
	$('.kopa_sidebar_header').on('click', toggle_edit_sidebar);
	$('.kopa_sidebar_close_button').on('click', close_edit_sidebar);

	// remove sidebar button
	$('.kopa_sidebar_delete_button').on('click', remove_sidebar);

	// advanced settings checkbox
	$('.kopa_sidebar_advanced_settings').on('click', folding_advanced_settings);

	

});