/**
 * Kopa Framework Admin Media Uploader JS
 * Author: Kopatheme
 * Copyright: 2014 Kopatheme
 * License: GPLv2 or later
 * Used localized variable: kopa_upload_l10n
 */
jQuery(document).ready(function($) {

	/* ========================================================================
	 * indexOf for IE8 compatibility
	 * @see http://stackoverflow.com/questions/3629183/why-doesnt-indexof-work-on-an-array-ie8
	 * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/indexOf
	 * ======================================================================== */
	if (!Array.prototype.indexOf) {
		Array.prototype.indexOf = function (searchElement, fromIndex) {

			var k;

			// 1. Let O be the result of calling ToObject passing
			//    the this value as the argument.
			if (this == null) {
				throw new TypeError('"this" is null or not defined');
			}

			var O = Object(this);

			// 2. Let lenValue be the result of calling the Get
			//    internal method of O with the argument "length".
			// 3. Let len be ToUint32(lenValue).
			var len = O.length >>> 0;

			// 4. If len is 0, return -1.
			if (len === 0) {
				return -1;
			}

			// 5. If argument fromIndex was passed let n be
			//    ToInteger(fromIndex); else let n be 0.
			var n = +fromIndex || 0;

			if (Math.abs(n) === Infinity) {
				n = 0;
			}

			// 6. If n >= len, return -1.
			if (n >= len) {
				return -1;
			}

			// 7. If n >= 0, then Let k be n.
			// 8. Else, n<0, Let k be len - abs(n).
			//    If k is less than 0, then let k be 0.
			k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

			// 9. Repeat, while k < len
			while (k < len) {
				var kValue;
				// a. Let Pk be ToString(k).
				//   This is implicit for LHS operands of the in operator
				// b. Let kPresent be the result of calling the
				//    HasProperty internal method of O with argument Pk.
				//   This step can be combined with c
				// c. If kPresent is true, then
				//    i.  Let elementK be the result of calling the Get
				//        internal method of O with the argument ToString(k).
				//   ii.  Let same be the result of applying the
				//        Strict Equality Comparison Algorithm to
				//        searchElement and elementK.
				//  iii.  If same is true, return k.
				if (k in O && O[k] === searchElement) {
					return k;
				}
				k++;
			}
			return -1;
		};
	}

	/* ========================================================================
	 * Media Uploader
	 * Used localized variable: kopa_upload_l10n
	 * ======================================================================== */
	var kopaframework_upload,           // current upload modal
		kopaframework_selector,         // current upload section
		kopaframework_mime_types = [];  // list of mime type
		kopaframework_modals = [];      // list of upload modal for each mime type

	function kopaframework_add_file(event, selector) {

		event.preventDefault();

		var $el = $(this),
			$upload = selector.find('.kopa_upload'),
			dataType = $upload.data('type'), // get the current mime type
			typeIndex = kopaframework_mime_types.indexOf( dataType ), // the index of the current mime type in kopaframework_mime_types and its modal in kopaframework_modals
			dataOptions = {
				// Set the title of the modal.
				title: $el.data('choose'),

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			};
		kopaframework_selector = selector;

		// if mime type is not empty
		if ( dataType ) {
			// if the current type is not existed in the list of mime type
			if ( -1 === typeIndex ) {
				// add it to the list of mime type
				kopaframework_mime_types.push( dataType );
			}

			// add 'library' property to dataOptions to filter mime type for upload modal
			dataOptions.library = { type: dataType };
		}

		// get the modal for the current mime type
		if ( kopaframework_modals ) {
			kopaframework_upload = kopaframework_modals[typeIndex];
		}

		// If the media frame already exists, reopen it.
		if ( kopaframework_upload ) {
			kopaframework_upload.open();
		} else {
			// Create the media frame.
			kopaframework_upload = wp.media.frames.kopaframework_upload =  wp.media( dataOptions );

			// When an image is selected, run a callback.
			kopaframework_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = kopaframework_upload.state().get('selection').first();
				kopaframework_upload.close();
				kopaframework_selector.find('.kopa_upload').val(attachment.attributes.url);
				if ( attachment.attributes.type == 'image' ) {
					kopaframework_selector.find('.kopa_screenshot').empty().hide().removeClass('kopa_hide').append('<img src="' + attachment.attributes.url + '"><a class="kopa_remove_image">' + kopa_upload_l10n.remove + '</a>').slideDown('fast');

					if ( $('body').hasClass('widgets-php') || $('body').hasClass('post-php') ) {
						kopaframework_selector.find('img').css('max-width', '100%');
						$('<br>').insertBefore( kopaframework_selector.find('.kopa_remove_image').addClass('button') );
					}
				}
				var $removeButton = kopaframework_selector.find('.kopa_upload_button').addClass('kopa_remove_file').removeClass('kopa_upload_button');
				// check a or input tag to change text
				if ( $removeButton.text() ) {
					$removeButton.text(kopa_upload_l10n.remove);
				} else if ( $removeButton.val() ) {
					$removeButton.val(kopa_upload_l10n.remove);
				}

				// kopaframework_selector.find('.of-background-properties').slideDown();
			});

			// after creating, push the upload modal for the current mime type
			// to the defined modal list to use later
			kopaframework_modals.push( kopaframework_upload );

		}

		// Finally, open the modal.
		kopaframework_upload.open();
	}

	function kopaframework_remove_file(event, selector) {
		event.preventDefault();

		selector.find('.kopa_remove_image').hide();
		selector.find('.kopa_upload').val('');
		// selector.find('.of-background-properties').hide();
		selector.find('.kopa_screenshot').slideUp('fast');
		var $uploadButton = selector.find('.kopa_remove_file').addClass('kopa_upload_button').removeClass('kopa_remove_file');
		// check a or input tag to change text
		if ( $uploadButton.text() ) {
			$uploadButton.text(kopa_upload_l10n.upload);
		} else if ( $uploadButton.val() ) {
			$uploadButton.val(kopa_upload_l10n.upload);
		}
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( $('.kopa_section_upload .kopa_upload_notice').length > 0 ) {
			$('.kopa_upload_button').remove();
		}
	}

	$('body').on('click', '.kopa_remove_image, .kopa_remove_file', function( event ) {
		kopaframework_remove_file(event, $(this).closest('.kopa_section'));
	});

	$('body').on('click', '.kopa_upload_button', function( event ) {
		kopaframework_add_file(event, $(this).closest('.kopa_section'));
	});
	/* ========================================================================
	 * END >>> Media Uploader
	 * ======================================================================== */
});