var Kopa_Icon_Picker, Kopa_Datetime_Js, Kopa_UI_Gallery, kopa_gallery_iframe, kopa_gallery_button;

var kopa_gallery_sortable_uploader;
var kopa_gallery_iframe;
var kopa_gallery_button;
var kopa_lighbox_icons_id = '#kopa_advanced_field_lighbox_icons';

jQuery(document).ready(function ($) {
    Kopa_UI.init();
    Kopa_Field_Image.init($);
});

jQuery( document ).ajaxSuccess( function() {
    Kopa_UI.init();
});

var Kopa_UI = {
    init: function (){
        Kopa_Datetime_Js.init_field_datetime();
        Kopa_UI_Gallery.init();
        Kopa_UI_Gallery_Sortable.init();
        Kopa_Icon_Picker.init();        
    }
}

var Kopa_Datetime_Js = {
    init_field_datetime: function() {
        if (jQuery('.kopa-framework-datetime').length > 0) {
            jQuery('.kopa-framework-datetime').each(function(index, element) {
                var kopa_timepicker = jQuery(element).attr('data-timepicker');
                var kopa_datepicker = jQuery(element).attr('data-datepicker');
                if ( 1 == kopa_timepicker ) {
                    kopa_timepicker = true;
                } else {
                    kopa_timepicker = false;
                }
                if ( 1 == kopa_datepicker ) {
                    kopa_datepicker = true;
                } else {
                    kopa_datepicker = false;
                }
                jQuery(element).datetimepicker({
                    lang: 'en',
                    timepicker: kopa_timepicker,
                    datepicker: kopa_datepicker,
                    format: jQuery(element).attr('data-format'),
                    i18n: {
                        en: {
                            months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            dayOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"]
                        }
                    }
                });
            });
        }
    }
};

var Kopa_UI_Gallery = {
    init: function() {
        jQuery('.kopa-framework-gallery-box').on('click', '.kopa-framework-gallery-config', function(event) {
            event.preventDefault();
            kopa_gallery_button = jQuery(this);
            if (kopa_gallery_iframe) {
                kopa_gallery_iframe.open();
                return;
            }
            kopa_gallery_iframe = wp.media.frames.kopa_gallery_iframe = wp.media({
                title: 'Gallery config',
                button: {
                    text: 'Use'
                },
                library: {
                    type: 'image'
                },
                multiple: true
            });
            kopa_gallery_iframe.on('open', function() {
                var ids, selection;
                ids = kopa_gallery_button.parents('.kopa-framework-gallery-box').find('input.kopa-framework-gallery').val();
                if ('' !== ids) {
                    selection = kopa_gallery_iframe.state().get('selection');
                    ids = ids.split(',');
                    jQuery(ids).each(function(index, element) {
                        var attachment;
                        attachment = wp.media.attachment(element);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                }
            });
            kopa_gallery_iframe.on('select', function() {
                var result, selection;
                result = [];
                selection = kopa_gallery_iframe.state().get('selection');
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    return result.push(attachment.id);
                });
                if (result.length > 0) {
                    result = result.join(',');
                    kopa_gallery_button.parents('.kopa-framework-gallery-box').find('input.kopa-framework-gallery').val(result);
                }
            });
            kopa_gallery_iframe.open();
        });
    }
};

var Kopa_Icon_Picker = {
    init: function() {
        if (jQuery('.kopa-icon-picker').length > 0) {
            jQuery('.kopa-icon-picker').click(function(event) {                 	

                var btn;
                event.preventDefault();
                btn = jQuery(this);
                if (jQuery(kopa_lighbox_icons_id).length !== 1) {
                    jQuery('body').append('<div id="kopa_advanced_field_lighbox_icons" class="upside-hide"></div>');
                    jQuery.ajax({
                        beforeSend: function(jqXHR) {},
                        success: function(data, textStatus, jqXHR) {
                            jQuery(kopa_lighbox_icons_id).html(data);
                        },
                        complete: function() {
                            Kopa_Icon_Picker.open_lighbox(btn);
                        },
                        url: kopa_advanced_field.ajax_url,
                        dataType: "html",
                        type: 'GET',
                        async: false,
                        data: {
                            action: 'get_lighbox_icons'
                        }
                    });
                } else {
                    Kopa_Icon_Picker.open_lighbox(btn);
                }
            });
        }
    },
    open_lighbox: function(btn) {
        jQuery(kopa_lighbox_icons_id).dialog({
            width: 360,
            height: 480,
            modal: true,
            closeText: '<i class="dashicons dashicons-dismiss"></i>',
            title: kopa_advanced_field.i18n.icon_picker,
            buttons: [
            	{
								'text': 'Select',
								'class': 'kopa-ui-button-use button button-primary',
								'click': function() {
									var icon;
									icon = Kopa_Icon_Picker.click_ok();
									btn.parent().find('.kopa-icon-picker-value').val(icon);
									btn.parent().find('.kopa-icon-picker-preview i').attr('class', icon);
								}
            	}
            ]
        });
    },
    click_ok: function() {
        var icon;
        icon = jQuery(kopa_lighbox_icons_id).find('.kopa-icon-item.upside-active i').attr('class');
        jQuery(kopa_lighbox_icons_id).dialog('close');
        return icon;
    },
    select_a_icon: function(event, obj) {
        event.preventDefault();
        obj.parents('.kopa-wrap').find('.kopa-icon-item').removeClass('upside-active');
        obj.addClass('upside-active');
    },
    filter_icons: function(event, obj) {
        var filter, regex, wrap;
        event.preventDefault();
        wrap = obj.parents('.kopa-list-of-icon');
        filter = obj.val();
        if (!filter) {
            wrap.find('.kopa-icon-item').show();
            return false;
        }
        regex = new RegExp(filter, "i");
        wrap.find('.kopa-icon-item i').each(function(index, element) {
            if (jQuery(this).data('title').search(regex) < 0) {
                jQuery(this).parent().hide();
            } else {
                jQuery(this).parent().show();
            }
        });
    }
};

var Kopa_UI_Gallery_Sortable = {
    init: function() {
        $galleries = jQuery( 'div.kopa-ui-gallery' );
        if( $galleries.length ) {
            
            $galleries.map( function( $gallery ) {
                Kopa_UI_Gallery_Sortable._init_sortable( jQuery(this).find('ul').first() );
            } );
    
            $galleries.on( 'click', '.kopa-ui-gallery__upload', function( $event ) {
                Kopa_UI_Gallery_Sortable._edit( $event, jQuery(this) );
            } );

            $galleries.on( 'click', '.kopa-ui-gallery__remove', function( $event ) {
                Kopa_UI_Gallery_Sortable._remove( $event, jQuery(this) );
            } );
        }
    },
    _edit: function( $event, $button ) {
        $event.preventDefault();        

        $wrap         = $button.parents('.kopa-ui-gallery');
        $sortable     = $wrap.find( 'ul' ).first();
        $inputs       = $wrap.find( 'input[type="hidden"]' );
        $previews     = $wrap.find( 'img' );
        $gallery_name = $wrap.attr( 'data-name' );

        if (kopa_gallery_sortable_uploader) {
            kopa_gallery_sortable_uploader.open();
            return;
        }

        kopa_gallery_sortable_uploader = wp.media.frames.kopa_gallery_sortable_uploader = wp.media({
            title: 'Uploader',
            $button: {
                text: 'Select'
            },
            library: { type: 'image' },
            multiple: true
        });

        kopa_gallery_sortable_uploader.on( 'open', function() {

            if( $inputs.length ) {

                selection = kopa_gallery_sortable_uploader.state().get('selection');              
                jQuery.each( $inputs, function(){
                    var attachment = wp.media.attachment( parseInt( jQuery(this).val() ) );
                    attachment.fetch();
                    selection.add( attachment );
                } );
                              
            }
        });

        kopa_gallery_sortable_uploader.on( 'select', function() {
            
            var $results  = [];         
            var selection = kopa_gallery_sortable_uploader.state().get('selection');

            selection.map( function( $attachment ) {
                $attachment = $attachment.toJSON();
                $results.push( [ $attachment.id, $attachment.sizes.thumbnail.url] );
            });
            console.log($results);
            if ( $results.length ) {
                $sortable.html( '' );

                $results.map( function( $image ) {
                    $html = Kopa_UI_Gallery_Sortable._build_single( $gallery_name, $image[0], $image[1] );
                    $sortable.append( jQuery( $html ) );
                } );
            }

        });

        kopa_gallery_sortable_uploader.open();
    },
    _remove: function( $event, $button ) {
        $event.preventDefault();        
        $button.closest('.kopa-ui-gallery__image').remove();      
    },
    _build_single: function( $gallery_name, $image_id, $image_src ){

        $html = '<li class="kopa-ui-gallery__image">';
        $html += '<input type="hidden" value="' + $image_id + '" name="' + $gallery_name + '[]">';
        $html += '<img alt="" src="' + $image_src + '">';
        $html += '<span class="kopa-ui-gallery__remove dashicons dashicons-trash">';
        $html += '</span>';
        $html += '</li>';

        return $html;
    },
    _init_sortable: function( $sortable ) {
        $sortable.sortable({
            containment: 'parent'
        });
        $sortable.disableSelection();
    }
}


/**
 * Image Select
 * @since 1.1.9
 */
var Kopa_Field_Image = {
    init: function ($) {
        /**
         * Image Select
         */

        $('.kopa-field-image .item-add').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var file_frame;
            // Create the media frame.
            file_frame = wp.media.frames.downloadable_file = wp.media({
                title: 'Select an image',
                button: {
                    text: 'Use image'
                },
                multiple: false
            });


            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                var source = attachment.sizes.thumbnail;
                if (attachment.sizes.full != undefined) {
                    source = attachment.sizes.full;
                }
                $this.html('<div class="img" style="background-image:url(' + source.url + ')"></div>');
                $this.next("input").val(attachment.id);
                $this.parent().addClass('hasimage');
            });

            // Finally, open the modal.
            file_frame.open();
        });

        $(document).on("click", ".kopa-field-image .item-remove", function (e) {
            $(this).parent().find('.item-add').empty();
            $(this).prev("input").val("");
            $(this).parent().removeClass('hasimage');
            e.preventDefault();
        });
    }
};