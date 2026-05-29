/**
 * Kopa Framework Admin JS
 * Author: Kopatheme
 * Copyright: 2014 Kopatheme
 * License: GPLv2 or later
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
	 * (un)fold options in a checkbox-group
	 * ======================================================================== */
  	jQuery('.kopa_fold').click(function() {
    	var $fold='.kopa_fold_'+this.id;
    	$($fold).slideToggle('fast');
  	});
  	/* ========================================================================
	 * END >>> (un)fold options in a checkbox-group
	 * ======================================================================== */



	/* ========================================================================
	 * fading updated, error messages when submit form
	 * ======================================================================== */
	setTimeout( function () {
		$('.kopa_message.updated.fade, .kopa_message.error.fade').fadeOut('slow');
	}, 3000 );
	/* ========================================================================
	 * END >>> fading updated, error messages when submit form
	 * ======================================================================== */
	


	/* ========================================================================
	 * Loads the color pickers
	 * @see http://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
	 * ======================================================================== */
	$('.kopa_color').wpColorPicker();
	/* ========================================================================
	 * END >>> Loads the color pickers
	 * ======================================================================== */



	/* ========================================================================
	 * Loads tabbed sections if they exist
	 * ======================================================================== */
	if ( $('.kopa_nav_title').length > 0 ) {
		kopa_options_framework_tabs();
	}
	
	function kopa_options_framework_tabs() {
		// Hides all the .kopa_tab_pane sections to start
		$('.kopa_tab_pane').hide();

		// Find if a selected tab is saved in localStorage
		var kopa_active_tab = '';

		if ( typeof(localStorage) != 'undefined' ) {
			kopa_active_tab = localStorage.getItem("kopa_active_tab");
		}

		// Loads tabbed sections
		// If active tab is saved and exists, load it's .kopa_tab_pane
		if (kopa_active_tab != '' && $(kopa_active_tab).length ) {
			$(kopa_active_tab + '_tab').addClass('kopa_nav_active');
			$(kopa_active_tab).show();
		} else {
			$('.kopa_nav_tabs li').first().addClass('kopa_nav_active');
			$('.kopa_tab_pane').first().show();
		}

		// Bind tabs clicks
		$('.kopa_nav_tabs li a').on('click', function (e) {
			e.preventDefault();
		});

		$('.kopa_nav_tabs li').on( 'click', function () {
			var $this = $(this),
				$anchor = $this.children('.kopa_nav_title'),
				$activeSection = $($anchor.attr('href'));

			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem( 'kopa_active_tab', $anchor.attr('href') );
			}

			$this.addClass('kopa_nav_active')
				.siblings().removeClass('kopa_nav_active');

			$activeSection.show()
				.siblings().hide();
		} );
	}
	/* ========================================================================
	 * END >>> Loads tabbed sections if they exist
	 * ======================================================================== */



	/* ========================================================================
	 * Mobile menu
	 * ======================================================================== */
	$('#kopa_options_wrap .kopa_sidebar_menu_mobile_icon').on('click', function () {
		$('#kopa_options_wrap ul.kopa_nav_tabs').toggle();
	});
	$(window).resize(function () {
		if ( $(this).width() > 980 ) {
			$('#kopa_options_wrap ul.kopa_nav_tabs').show()
				.removeAttr('style');
		}
	});
	/* ========================================================================
	 * END >>> Mobile menu
	 * ======================================================================== */



	/* ========================================================================
	 * Fonts
	 * Dependencies 	 : google.com, jquery
	 * Feature added by : Smartik - http://smartik.ws/
	 * Date 			 : 03.17.2013
	 * Modified by      : Kopatheme
	 * Modified date    : 07.26.2014
	 * Used localized variables:
	 *   kopa_google_fonts
	 *   kopa_google_font_families
	 *   kopa_system_fonts
	 *   kopa_font_styles
	 *   kopa_custom_font_attributes
	 * ======================================================================== */
	var the_font_category = 'sans-serif', // fallback for google font
		kopa_custom_fonts_props = [], // contains custom fonts properties
		kopa_custom_fonts = [];  // contains custom font list

	// grab all custom fonts and their properties
	jQuery('.kopa_custom_font_item').each(function () {
		var $this = jQuery(this),
			dataName  = $this.find('.kopa_custom_font_item_name').val(),
			dataWoff  = $this.find('.kopa_custom_font_item_woff').val(),
			dataTtf   = $this.find('.kopa_custom_font_item_ttf').val(),
			dataSvg   = $this.find('.kopa_custom_font_item_svg').val(),
			dataEot   = $this.find('.kopa_custom_font_item_eot').val();

		kopa_custom_fonts_props.push({
			woff: dataWoff,
			ttf:  dataTtf,
			svg:  dataSvg,
			eot:  dataEot
		});

		kopa_custom_fonts.push( dataName );
	});

	function KopaFontSelect( slctr, mainID ){
		
		var _selected = $( '#' + mainID ).val(), //get current value - selected and saved
			_linkclass = 'kopa_style_link_'+ mainID,
			_previewer = mainID + '_preview',
			_style = mainID + '_style',
			_custom_font_name = mainID + '_custom_font_name',
			_custom_font_url = mainID + '_custom_font_url',
			_typeface_class = 'kopa_typeface_' + $('#' + mainID).parents('.kopa_section').attr('id'),
			_font_obj = undefined,
			_is_system_font = false, // flag to check system font
			_is_google_font = false, // flag to check google font
			_is_custom_font = false; // flag to check custom font

		// check for system font, google font or custom font 
		if ( kopa_system_fonts.indexOf( _selected ) >= 0 ) {
			_is_system_font = true;
		} else if ( kopa_google_font_families.indexOf( _selected ) >= 0 ) {
			_is_google_font = true;
		} else if ( kopa_custom_fonts.indexOf( _selected ) >= 0 ) {
			_is_custom_font = true;
		}

		// check custom font
		if ( _is_custom_font ) {
			var typefaceName = _selected,
				typefaceSvgID = '',
				index = kopa_custom_fonts.indexOf( _selected ),
				props = kopa_custom_fonts_props[ index ],
				woff  = props.woff,
				ttf   = props.ttf,
				svg   = props.svg,
				eot   = props.eot,
				customTypefaceElement = '';

			// disable font style selection to use custom typeface
			$( '#' + _style ).attr( 'disabled', 'disabled' );
			$( '#' + _previewer ).fadeIn();

			customTypefaceElement += '<style class="' + _typeface_class + '">' + "\n";
			customTypefaceElement += '@font-face {' + "\n";
			customTypefaceElement += 'font-family: "' + typefaceName + '";' + "\n";
			customTypefaceElement += 'src: ';
			if ( eot ) {
				customTypefaceElement += 'url("' + eot + '");' + "\n";
				customTypefaceElement += 'src: url("' + eot + '?#iefix") format("embedded-opentype")';
			}

			if ( woff && eot) {
				customTypefaceElement += ",\n" + 'url("' + woff + '") format("woff")';
			} else if ( woff ) {
				customTypefaceElement += 'url("' + woff + '") format("woff")';
			}

			if ( ttf && (woff || eot) ) {
				customTypefaceElement += ",\n" + 'url("' + ttf + '") format("truetype")';
			} else if ( ttf ) {
				customTypefaceElement += 'url("' + ttf + '") format("truetype")';
			}

			if ( svg ) {
				$.get(svg, function ( data ) {
					typefaceSvgID = $(data).find('font').attr('id');

					if ( ttf || woff || eot ) {
						customTypefaceElement += ",\n" + 'url("' + svg + '#' + typefaceSvgID + '") format("svg")';
					} else {
						customTypefaceElement += 'url("' + svg + '#' + typefaceSvgID + '") format("svg")';
					}

					customTypefaceElement += ';' + "\n";
					customTypefaceElement += '}' + "\n";
					customTypefaceElement += '</style>' + "\n";

					// add new custom typeface
					$( '.' + _typeface_class ).remove();
					$('head').append( customTypefaceElement );
					$('#' + _previewer)
						.css('font-family', '"' + typefaceName + '"')
						.css('font-style', '')
						.css('font-weight', '');
				});
			} else {
				customTypefaceElement += ';' + "\n";
				customTypefaceElement += '}' + "\n";
				customTypefaceElement += '</style>' + "\n";

				// add new custom typeface
				$( '.' + _typeface_class ).remove();
				$('head').append( customTypefaceElement );
				$('#' + _previewer)
					.css('font-family', '"' + typefaceName + '"')
					.css('font-style', '')
					.css('font-weight', '');
			}

			return;
		} else {

			// enable font style selection for system fonts and google fonts
			$( '#' + _style ).removeAttr('disabled');
		
		} // end check custom font

		// if the current selected font is google font, grab font's properties
		if ( $(slctr).attr('id') === mainID && _is_google_font ) {
			// get the index of the selected font in the font family list
			var index = kopa_google_font_families.indexOf( _selected );
			
			// get the corresponding property object of selected font in the font property list
			if ( index >= 0 ) {
				_font_obj = kopa_google_fonts[index];

				// get the font category as fallback for google font
				if ( _font_obj.category ) {
					the_font_category = _font_obj.category; 
				}
			}
		}

		if( _selected ){ //if var exists and isset

			$('#' + _previewer ).fadeIn();
			
			//Check if selected is not equal with "Select a font" and execute the script.
			if ( _selected !== 'none' && _selected !== 'Select a font' ) {
				
				// system font
				if ( _is_system_font ) {
					var the_font_style = $( '#' + _style ).val();

					// if change font family, reload variants of font style again
					if ( $(slctr).attr('id') === mainID ) {
						$( '#' + _style + ' option' ).remove();
						// system font style variants set
						var variants = ['regular', 'italic', 'bold', 'bolditalic'],
							styleLabel = '';
						for ( var index in variants ) {
							styleLabel = KopaFontStyleLabel( variants[index] ); 
							if (  styleLabel ) {
								$( '#' + _style ).append( '<option value="' + variants[index] + '">' + styleLabel + '</option>' );
							}
						}

						// check for changing font style
						// if the current font style is available in the current font family, keep it the same
						if ( the_font_style && variants.indexOf( the_font_style ) >= 0 ) {
							$( '#' + _style ).val( the_font_style )
								.find( 'option[value="' + the_font_style + '"]' ).attr( 'selected', 'selected' );
						} 
						// if the current font style is not available, 
						// and the 'regular' style is available in the current font family, 
						// change font style to 'regular'
						else if ( variants.indexOf( 'regular' ) >= 0 ) {
							the_font_style = 'regular';
							$( '#' + _style ).val( 'regular' )
								.find( 'option[value="regular"]' ).attr( 'selected', 'selected' );
						} 
						// if the current font style is not available, 
						// and the 'regular' style is not available in the current font family, 
						// change font style to the first value of variants set
						else {
							the_font_style = variants[0];
							$( '#' + _style ).val( the_font_style )
								.find( 'option:first' ).attr( 'selected', 'selected' );
						}
					}

					if ( 'regular' === the_font_style ) {
						the_font_style = 'normal';
					}

					$('#' + _previewer ).css('font-family', _selected);
					$('#' + _previewer ).css('font-weight', the_font_style.replace('italic', ''));

					if (the_font_style.indexOf('italic') >= 0) {
						$('#' + _previewer).css('font-style', 'italic');
					} else {
						$('#' + _previewer).css('font-style', 'normal');
					}
				}
				// google font
				else { 

					//remove other elements crested in <head>
					$( '.'+ _linkclass ).remove();
					
					//replace spaces with "+" sign
					var the_font = _selected.replace(/\s+/g, '+'),
						the_font_style = $( '#' + _style ).val();

					// check for loading font styles
					// if change font family, reload variants of font style again
					if ( _font_obj !== undefined ) {
						$( '#' + _style + ' option' ).remove();
						var variants = _font_obj.variants,
							styleLabel = '';
						for ( var index in variants ) {
							styleLabel = KopaFontStyleLabel( variants[index] );

							// check for IE8, I don't know why in IE8
							// it always push the last element and make styleLabel = false
							if ( styleLabel ) { 
								$( '#' + _style ).append( '<option value="' + variants[index] + '">' + styleLabel + '</option>' );
							}
						}

						// check for changing font style
						// if the current font style is available in the current font family, keep it the same
						if ( the_font_style && variants.indexOf( the_font_style ) >= 0 ) {
							$( '#' + _style ).val( the_font_style )
								.find( 'option[value="' + the_font_style + '"]' ).attr( 'selected', 'selected' );
						} 
						// if the current font style is not available, 
						// and the 'regular' style is available in the current font family, 
						// change font style to 'regular'
						else if ( variants.indexOf( 'regular' ) >= 0 ) {
							the_font_style = 'regular';
							$( '#' + _style ).val( 'regular' )
								.find( 'option[value="regular"]' ).attr( 'selected', 'selected' );
						} 
						// if the current font style is not available, 
						// and the 'regular' style is not available in the current font family, 
						// change font style to the first value of variants set
						else {
							the_font_style = variants[0];
							$( '#' + _style ).val( the_font_style )
								.find( 'option:first' ).attr( 'selected', 'selected' );
						}
					}
					
					if ( 'regular' === the_font_style ) {
						the_font_style = '400';
					}

					//add reference to google font family
					$('head').append('<link href="http://fonts.googleapis.com/css?family='+ the_font +':' + the_font_style + '" rel="stylesheet" type="text/css" class="'+ _linkclass +'">');
					
					//show in the preview box the font
					$('#' + _previewer ).css('font-family', _selected + ',' + the_font_category ); // the_font_category as fallback
					$('#' + _previewer ).css('font-weight', the_font_style.replace('italic', ''));

					if (the_font_style.indexOf('italic') >= 0) {
						$('#' + _previewer).css('font-style', 'italic');
					} else {
						$('#' + _previewer).css('font-style', 'normal');
					}

				} // end check system font or google font

			} else {
				
				//if selected is not a font remove style "font-family" at preview box
				$('#' + _previewer ).css('font-family', '' );
				$('#' + _previewer ).fadeOut();
				
			}
		
		}
	
	}

	/**
	 * Get font style label
	 * Dependencies: kopa_font_styles ( localized font styles variable )
	 */
	function KopaFontStyleLabel( style ) {
		var styleObj = {}; // empty style object

		for ( var styleIndex in kopa_font_styles ) {
			styleObj = kopa_font_styles[ styleIndex ];
			if ( styleObj.style === style ) {
				return styleObj.label;
			}
		}

		return false;
	}

	/**
	 * change font size event
	 */
	function KopaFontSize( slctr, mainID ) {
		var $this = jQuery(slctr),
			fontSize = $this.val(),
			_previewer = mainID + '_preview';
		jQuery( '#' + _previewer ).css('font-size', fontSize + 'px');
	}

	/**
	 * on Change call back function for wpColorPicker
	 * @see http://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
	 */
	function KopaFontColor() {
		var $this = jQuery(this),
			mainID = $this.data('main-id');

		// if not is the color of select font option type, return to exit
		if ( undefined === mainID ) {
			return;
		}

		var fontColor = $this.val() ? $this.val() : $this.data('default-color'),
			_previewer = mainID + '_preview';

		if (fontColor) {
			jQuery( '#' + _previewer ).css('color', fontColor);
		} else {
			jQuery( '#' + _previewer ).css('color', 'inherit');
		}
	}
	
	/**
	 * Font family, font style
	 */
	//init for each element
	jQuery( '.kopa_select_font, .kopa_select_font_style' ).each(function(){ 
		var mainID = jQuery(this).data('main-id');
		KopaFontSelect( this, mainID );
	});
	
	//init when value is changed
	jQuery( '.kopa_select_font, .kopa_select_font_style' ).change(function(){ 
		var mainID = jQuery(this).data('main-id');
		KopaFontSelect( this, mainID );
	});

	/**
	 * Font size
	 */ 
	// init font size
	jQuery('.kopa_select_font_size').each(function(){
		var mainID = jQuery(this).data('main-id');
		KopaFontSize( this, mainID );
	});

	// change font size
	jQuery('.kopa_select_font_size').on( 'keyup change', function(){
		var mainID = jQuery(this).data('main-id');
		KopaFontSize( this, mainID );
	});

	/**
	 * Font color
	 */
	// init font color
	jQuery('.kopa_select_font_color').wpColorPicker({
		change: KopaFontColor,
		clear: KopaFontColor,
		palettes: false,
	});
	jQuery('.kopa_select_font_color').each( KopaFontColor );
	/* ========================================================================
	 * END >>> Fonts
	 * ======================================================================== */



	/* ========================================================================
	 * Custom font manager
	 * Used localized variable: kopa_admin_l10n
	 * ======================================================================== */
	/**
	 * Click handle function for add font button
	 */
	function add_custom_font(e) {
		var $this      = $(this),
			$container = $this.parents('.kopa_controls'),
			$fontList  = $container.find('.kopa_custom_font_list'),
			$fontItems = $container.find('.kopa_custom_font_item'),
			name       = $this.data('name'),
			orders     = $this.data('orders').toString(), // list of font orders
			orderList  = [];
			itemCount  = 0, // the order of new font, determines by list of font orders
			newItem    = '';

		// get the list of font orders
		if ( orders !== '' ) {
			orderList = orders.split(',');
			orderList = $.map( orderList, function (i) {
				return parseInt(i); 
			});
		}

		// get the new order for the new font
		if ( orderList.length ) {
			itemCount = Math.max.apply(null, orderList);
			itemCount += 1;
		}

		// push the new order to the order list
		orderList.push(itemCount);

		// set new data of orders to use in later click
		orders = orderList.join(',');
		$this.data('orders', orders); 

		orderList = $fontList.find('.kopa_custom_font_order').map(function () {
			var str = this.value;

			str = str.replace(/\D/g,'');
			str = parseFloat(str);
			return str;
		}).get();


		// create new font item
		newItem += '<div class="kopa_custom_font_item">';
			newItem += '<div class="kopa_custom_font_top">';
				newItem += '<div class="kopa_custom_font_title_action">';
				newItem += '</div>'; // kopa_custom_font_title_action
				
				newItem += '<div class="kopa_custom_font_title">';
				newItem += '<strong>Custom Font '+(itemCount + 1)+'</strong>';
				newItem += '</div>'; // kopa_custom_font_title
			newItem += '</div>'; // kopa_custom_font_top

			newItem += '<div class="kopa_custom_font_inside kopa_hide">';

				for ( var key in kopa_custom_font_attributes ) {
					var attribute_data = kopa_custom_font_attributes[key],
						attribute_classes = 'kopa_custom_font_item_' + key,
						attribute_required = false,
						attribute_mimes = '',
						attribute_value = '';

					if ( 'name' === key && attribute_data.value ) {
						attribute_value = attribute_data.value + ' ' + ( itemCount + 1 );
					}

					if ( 'upload' === attribute_data.type ) {
						attribute_classes += ' kopa_upload';
					}

					if ( attribute_data.required ) {
						attribute_required = true;
					}

					if ( attribute_data.mimes ) {
						attribute_mimes = attribute_data.mimes;
					}

					newItem += '<div class="kopa_section"><div class="kopa_controls">';

					newItem += '<input class="'+attribute_classes+'" type="text" name="'+name+'['+itemCount+']['+key+']" placeholder="'+attribute_data.placeholder+'" '+(attribute_required ? 'required ' : '')+'data-type="'+attribute_mimes+'" value="'+attribute_value+'">';

					if ( 'upload' === attribute_data.type ) {
						newItem += '<input class="kopa_upload_button kopa_button button" type="button" value="'+kopa_admin_l10n.upload+'">';
					}

					newItem += '</div></div>';
				}

				newItem += '<div class="kopa_custom_font_control_actions">';
				newItem += '<a class="kopa_custom_font_remove" href="#">'+kopa_sidebar_attributes_l10n.remove+'</a>';
				newItem += ' | ';
				newItem += '<a class="kopa_custom_font_close" href="#">'+kopa_sidebar_attributes_l10n.close+'</a>';
				newItem += '</div>'; // kopa_custom_font_control_actions
			newItem += '</div>'; // kopa_custom_font_inside
		newItem += '</div>'; // kopa_custom_font_item

		// append it to the last item of the custom font list
		$fontList.append( newItem );
		
		// get the jquery object of the new font item
		var $newItem = $fontList.children('.kopa_custom_font_item:last'),
			$newItemTitleAction = $newItem.find('.kopa_custom_font_title_action');

		// change to up-arrow to indicate click to close header
		$newItemTitleAction.addClass('kopa_action_close');

		// open its properties to edit
		$newItem.children('.kopa_custom_font_inside').slideDown('fast');
		// bind events 
		// for its top header
		$newItem.on('click', '.kopa_custom_font_top', toggle_custom_font_properties);
		// for its close button
		$newItem.on('click', '.kopa_custom_font_close', close_custom_font_properties);
		// and for its delete button
		$newItem.on('click', '.kopa_custom_font_remove', delete_custom_font);

		return;
	}

	/**
	 * Toggling custom font properties
	 */
	function toggle_custom_font_properties(e) {
		var $this = $(this),
			$customFontTitleAction = $this.find('.kopa_custom_font_title_action'),
			$customFontInside = $this.siblings('.kopa_custom_font_inside');

		// add class 'kopa_action_close' to title action when body is showed
		if ( $customFontTitleAction.hasClass('kopa_action_close') ) {
			$customFontTitleAction.removeClass('kopa_action_close');
		} 
		// and remove it from title action when body is hidden
		else {
			$customFontTitleAction.addClass('kopa_action_close');
		}

		$customFontInside.slideToggle('fast');
	}

	/**
	 * Close custom font properties
	 */
	function close_custom_font_properties(e) {
		e.preventDefault();

		var $this = $(this),
			$customFontInside = $this.parents('.kopa_custom_font_inside'),
			$customFontTop = $customFontInside.siblings('.kopa_custom_font_top'),
			$customFontTitleAction = $customFontTop.find('.kopa_custom_font_title_action');

		// remove class 'kopa_action_close' from title action when body is hidden
		$customFontTitleAction.removeClass('kopa_action_close');
		
		$customFontInside.slideUp('fast');
	}

	/**
	 * Delete custom font
	 */
	function delete_custom_font(e) {
		e.preventDefault();

		var $this = $(this),
			$fontItem = $this.parents('.kopa_custom_font_item');

		if ( confirm( kopa_admin_l10n.confirm_delete ) ) {
			$fontItem.slideUp('fast', function () {
				$fontItem.remove();
			});
		}
	}

	/**
	 * Init
	 */
	// $('.kopa_custom_font_inside').hide();
	$('.kopa_custom_font_list').sortable({
		items: '.kopa_custom_font_item',
		handle: '.kopa_custom_font_top'
	});

	/**
	 * Bind events
	 */
	// bind event for adding custom font dynamically
	$('.kopa_add_font_button').on('click', add_custom_font);

	// toggle on click for font's properties
	$('.kopa_custom_font_top').on('click', toggle_custom_font_properties);

	// close font's properties
	$('.kopa_custom_font_close').on('click', close_custom_font_properties);

	// delete custom font
	$('.kopa_custom_font_remove').on('click', delete_custom_font);
	/* ========================================================================
	 * END >>> Custom font manager
	 * ======================================================================== */



	/* ========================================================================
	 * Confirm for backup manager
	 * Used localized variable: kopa_admin_l10n
	 * ======================================================================== */ 
	$('.kopa_reset').on('click', function () {
		return confirm( kopa_admin_l10n.confirm_reset );
	});

	$('.kopa_import').on('click', function () {
		return confirm( kopa_admin_l10n.confirm_import );
	});
	/* ========================================================================
	 * END >>> Confirm for backup manager
	 * ======================================================================== */ 
});