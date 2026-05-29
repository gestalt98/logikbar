<?php
/**
 * Register metaboxes for Pricing Tables.
 */
function rpt_register_group_metabox() {

    /* Custom sanitization call-back to allow HTML in most fields */
    function rpt_html_allowed_sani_cb($content) {
        return wp_kses_post( $content );
    }

    /* Custom sanitization call-back for custom button field */
    function rpt_custom_button_sani_cb($content) {
        return balanceTags( $content, true );
    }

    $prefix = '_rpt_';

    // Tables group
    $main_group = new_cmb2_box( array(
        'id' => $prefix . 'plan_metabox',
        'title' => '<span style="font-weight:400;">'.__( 'Manage Plans', 'dk-pricr-responsive-pricing-table' ).'</span> <a target="_blank" class="wpd_free_pro" title="'.__( 'Unlock more features with Responsive Pricing Table PRO!', 'dk-pricr-responsive-pricing-table' ).'" href="http://wpdarko.com/items/responsive-pricing-table-pro"><span style="color:#8a7463;font-size:15px; font-weight:400; float:right; padding-right:14px;"><span class="dashicons dashicons-lock"></span> '.__( 'Free version', 'dk-pricr-responsive-pricing-table' ).'</span></a>',
        'object_types' => array( 'rpt_pricing_table' ),
    ));

        $rpt_plan_group = $main_group->add_field( array(
            'id' => $prefix . 'plan_group',
            'type' => 'group',
            'options' => array(
                'group_title' => __('Plan {#}', 'dk-pricr-responsive-pricing-table' ),
                'add_button' => __('Add another plan', 'dk-pricr-responsive-pricing-table' ),
                'remove_button' => __('Remove plan', 'dk-pricr-responsive-pricing-table' ),
                'sortable' => true,
                'single' => false,
            ),
        ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Plan header', 'dk-pricr-responsive-pricing-table' ),
                'id' => $prefix . 'head_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Title', 'dk-pricr-responsive-pricing-table' ),
                'id' => $prefix . 'title',
                'type' => 'text',
                'row_classes' => 'de_first de_fifty de_text de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Subtitle', 'dk-pricr-responsive-pricing-table' ),
                'id' => $prefix . 'subtitle',
                'type' => 'text',
                'row_classes' => 'de_fifty de_text de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Recurrence', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'eg. \'per month/year\'', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id' => $prefix . 'recurrence',
                'type' => 'text',
                'row_classes' => 'de_first de_twentyfive de_text de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
            ));

             $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Price', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'No currency sign here (it can be set in the Settings tab)', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id' => $prefix . 'price',
                'type' => 'text',
                'row_classes' => 'de_twentyfive de_text de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
            ));

             $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Description', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'Short text that will appear below the subtitle', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id' => $prefix . 'description',
                'type' => 'text',
                'row_classes' => 'de_fifty de_text de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Small icon', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'Recommended:', 'dk-pricr-responsive-pricing-table' ).' 30x30px"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id'   => $prefix . 'icon',
                'type' => 'file',
                'options' => array('add_upload_file_text' => __( 'Upload', 'dk-pricr-responsive-pricing-table' )),
                'row_classes' => 'de_first de_hundred de_upload de_input',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Recommended plan', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'Check this to highlight this plan', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'desc' => __( 'Mark as recommended', 'dk-pricr-responsive-pricing-table' ),
		      'id'   => $prefix . 'recommended',
		      'type' => 'checkbox',
              'row_classes' => 'de_first de_fifty de_checkbox_side',
              'default' => false,
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Remove currency sign', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'Check this to hide the currency sign (for free plans for example)', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'desc' => __( 'Check to remove', 'dk-pricr-responsive-pricing-table' ),
		      'id'   => $prefix . 'free',
		      'type' => 'checkbox',
              'row_classes' => 'de_fifty de_checkbox_side',
              'default' => false,
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Plan features', 'dk-pricr-responsive-pricing-table' ),
                'id' => $prefix . 'features_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Feature list', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'One per line — Read the Tips & Tricks section for useful information', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
				        'id' => $prefix . 'features',
				        'type' => 'textarea',
                'attributes'  => array('rows' => 9),
                'row_classes' => 'de_first de_fifty de_textarea de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
                'attributes'  => array(
                    'placeholder' => __('One feature per line', 'dk-pricr-responsive-pricing-table' ),
                ),
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Tips & Tricks', 'dk-pricr-responsive-pricing-table' ),
                'desc' => '<span class="dashicons dashicons-yes"></span> '.__( 'Add images (not recommended)', 'dk-pricr-responsive-pricing-table' ).'<br/><span style="color:#bbb;">&lt;img src="http://yoursite.com/yourimage.png"/&gt;</span><br/><br/><span class="dashicons dashicons-yes"></span> '.__( 'Add links', 'dk-pricr-responsive-pricing-table' ).'<br/><span style="color:#bbb;">&lt;a href="http://yoursite.com"&gt;Go to yoursite.com&lt;/a&gt;</span><br/><br/><span class="dashicons dashicons-yes"></span> '.__( 'Add bold text', 'dk-pricr-responsive-pricing-table' ).'<br/><span style="color:#bbb;">&lt;strong&gt;Something <strong>important</strong>&lt;/strong&gt;</span><br/><br/><span class="dashicons dashicons-yes"></span> '.__( 'Show feature as unavailable with', 'dk-pricr-responsive-pricing-table' ).' "-n"<br/><span style="color:#bbb;">-nMy feature</span>',
                'id'   => $prefix . 'features_desc',
                'type' => 'title',
                'row_classes' => 'de_fifty de_info',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Plan button...', 'dk-pricr-responsive-pricing-table' ),
                'id' => $prefix . 'button_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));

             $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Button text', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'eg. \'Sign up, Buy\'', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id' => $prefix . 'btn_text',
                'type' => 'text',
                'row_classes' => 'de_fifty de_text de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
            ));

             $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Button link', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'eg. \'http://anything.com\'', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id' => $prefix . 'btn_link',
                'type' => 'text',
                'row_classes' => 'de_fifty de_text de_input',
                'sanitization_cb' => 'rpt_html_allowed_sani_cb',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( '...or a custom button instead', 'dk-pricr-responsive-pricing-table' ),
                'id' => $prefix . 'cust_button_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Custom button code', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'This field will overwrite the standard button fields', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
				'id' => $prefix . 'btn_custom_btn',
				'type' => 'textarea',
                'attributes'  => array('rows' => 9),
                'row_classes' => 'de_first de_fifty de_textarea de_input',
                'sanitization_cb' => 'rpt_custom_button_sani_cb',
                'attributes'  => array(
                    'placeholder' => __('Paste any button code here (Stripe, Paypal...)', 'dk-pricr-responsive-pricing-table' ),
                ),
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'What is a custom button?', 'dk-pricr-responsive-pricing-table' ),
                'desc' => '<span class="dashicons dashicons-editor-help"></span> '.__( 'Custom buttons are provided by third-party payment plateforms to allow direct redirection to the payment page. Paypal, Stripe as well as many other companies will generate buying buttons for you.', 'dk-pricr-responsive-pricing-table' ).' <br/><br/><span class="dashicons dashicons-admin-generic"></span> '.__( 'If you want your Pricing plan\'s footer to be replaced by a custom button, copy-paste the button code in this box. This will override the default button settings.', 'dk-pricr-responsive-pricing-table' ).'<br/><br/>'.__( 'We do <strong>not</strong> recommend doing this as it may not always go well with the design.', 'dk-pricr-responsive-pricing-table' ),
                'id'   => $prefix . 'custom_button_desc',
                'type' => 'title',
                'row_classes' => 'de_fifty de_info',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Others', 'dk-pricr-responsive-pricing-table' ),
                'id'   => $prefix . 'styling_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Custom CSS classes', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'Add classes to this plan like \'my-class another-class\'', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id' => $prefix . 'custom_classes',
                'type' => 'text',
                'row_classes' => 'de_first de_fifty de_text de_input',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => __( 'Color', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'This color will be used for several elements of the plan', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'id' => $prefix . 'color',
                'type' => 'colorpicker',
	              'default'  => '#9fdb80',
                'row_classes' => 'de_first de_hundred de_color de_input',
            ));

    // Settings
    $side_group = new_cmb2_box( array(
        'id' => $prefix . 'settings_metabox',
        'title' => '<span style="font-weight:400;">'.__( 'Settings', 'dk-pricr-responsive-pricing-table' ).'</span>',
        'object_types' => array( 'rpt_pricing_table' ),
        'context' => 'side',
        'priority' => 'high',
    ));

        $side_group->add_field( array(
            'name' => __( 'General settings', 'dk-pricr-responsive-pricing-table' ),
            'id'   => $prefix . 'other_settings_desc',
            'type' => 'title',
            'row_classes' => 'de_hundred de_heading_side',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Change currency', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'Add your currency sign here', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
			'id'      => $prefix . 'currency',
			'type'    => 'text',
            'row_classes' => 'de_hundred de_text_side',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Links behavior', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'open_newwindow',
			'type'    => 'select',
			'options' => array(
			    'currentwindow'   => __( 'Open in current window', 'dk-pricr-responsive-pricing-table' ),
			    'newwindow'   => __( 'Open in new window/tab', 'dk-pricr-responsive-pricing-table' ),

			),
			'default' => 'currentwindow',
            'row_classes' => 'de_hundred de_text_side',
        ));

        $side_group->add_field( array(
            'name' => __( 'Force original fonts', 'dk-pricr-responsive-pricing-table' ).' <a class="wpd_tooltip" title="'.__( 'Check this to use the plugin\'s font instead of your theme\'s', 'dk-pricr-responsive-pricing-table' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
            'desc' => __( 'Check to enable', 'dk-pricr-responsive-pricing-table' ),
		    'id'   => $prefix . 'original_font',
		    'type' => 'checkbox',
            'row_classes' => 'de_hundred de_checkbox_side',
            'default' => false,
        ));


        $side_group->add_field( array(
            'name' => '',
                'desc' => '<a id="rpt_font_sett_button" style="margin-top:-10px; cursor:pointer;"><span class="dashicons dashicons-admin-settings"></span> '.__( 'Adjust font sizes', 'dk-pricr-responsive-pricing-table' ).'</a>',
                'id'   => $prefix . 'pro_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_info de_info_side',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Title', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'title_fontsize',
			'type'    => 'select',
			'options' => array(
			    'tiny'   => __( 'Tiny', 'dk-pricr-responsive-pricing-table' ),
			    'small'   => __( 'Small', 'dk-pricr-responsive-pricing-table' ),
			    'normal'   => __( 'Normal', 'dk-pricr-responsive-pricing-table' ),
			),
            'default' => 'normal',
            'row_classes' => 'de_hundred de_text_side rpt_font_sett',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Subtitle', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'subtitle_fontsize',
			'type'    => 'select',
			'options' => array(
			    'tiny'   => __( 'Tiny', 'dk-pricr-responsive-pricing-table' ),
			    'small'   => __( 'Small', 'dk-pricr-responsive-pricing-table' ),
			    'normal'   => __( 'Normal', 'dk-pricr-responsive-pricing-table' ),
			),
			'default' => 'normal',
            'row_classes' => 'de_hundred de_text_side rpt_font_sett',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Description', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'description_fontsize',
			'type'    => 'select',
			'options' => array(
			    'small'   => __( 'Small', 'dk-pricr-responsive-pricing-table' ),
			    'normal'   => __( 'Normal', 'dk-pricr-responsive-pricing-table' ),
			),
            'default' => 'normal',
            'row_classes' => 'de_hundred de_text_side rpt_font_sett',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Price', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'price_fontsize',
			'type'    => 'select',
			'options' => array(
			    'supertiny'   => __( 'Tiny', 'dk-pricr-responsive-pricing-table' ),
			    'tiny'   => __( 'Small', 'dk-pricr-responsive-pricing-table' ),
			    'small'   => __( 'Normal', 'dk-pricr-responsive-pricing-table' ),
			    'normal'   => __( 'Big', 'dk-pricr-responsive-pricing-table' ),
			),
			'default' => 'normal',
            'row_classes' => 'de_hundred de_text_side rpt_font_sett',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Recurrence', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'recurrence_fontsize',
			'type'    => 'select',
			'options' => array(
			    'small'   => __( 'Small', 'dk-pricr-responsive-pricing-table' ),
			    'normal'   => __( 'Normal', 'dk-pricr-responsive-pricing-table' ),
			),
            'default' => 'normal',
            'row_classes' => 'de_hundred de_text_side rpt_font_sett',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Button', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'button_fontsize',
			'type'    => 'select',
			'options' => array(
			    'small'   => __( 'Small', 'dk-pricr-responsive-pricing-table' ),
			    'normal'   => __( 'Normal', 'dk-pricr-responsive-pricing-table' ),
			),
			'default' => 'normal',
            'row_classes' => 'de_hundred de_text_side rpt_font_sett',
        ));

        $side_group->add_field( array(
            'name'    => __( 'Features', 'dk-pricr-responsive-pricing-table' ),
			'id'      => $prefix . 'features_fontsize',
			'type'    => 'select',
			'options' => array(
			    'small'   => __( 'Small', 'dk-pricr-responsive-pricing-table' ),
			    'normal'   => __( 'Normal', 'dk-pricr-responsive-pricing-table' ),
			),
            'default' => 'normal',
            'row_classes' => 'de_hundred de_text_side rpt_font_sett',
        ));


    $main_group->add_field( array(
		'name'    => '<span style="font-weight:400; color:#8a7463;">'.__( 'Up to 5 plans', 'dk-pricr-responsive-pricing-table').'</span>',
		'desc' => '<span class="dashicons dashicons-flag"></span> '.__( 'You can add up to 5 plans per Pricing Table.', 'dk-pricr-responsive-pricing-table').'<br/> '.__( 'Adding more than that will result in display issues.', 'dk-pricr-responsive-pricing-table'),
		'id'      => $prefix . 'max',
		'type'    => 'title',
        'row_classes' => 'de_hundred de_instructions',
	) );


    // PRO version
    $pro_group = new_cmb2_box( array(
        'id' => $prefix . 'pro_metabox',
        'title' => '<span style="font-weight:400;">Upgrade to <strong>PRO version</strong></span>',
        'object_types' => array( 'rpt_pricing_table' ),
        'context' => 'side',
        'priority' => 'low',
        'row_classes' => 'de_hundred de_heading',
    ));

        $pro_group->add_field( array(
            'name' => '',
                'desc' => '<div><span class="dashicons dashicons-yes"></span> New layouts/designs<br/><span style="color:#999999; font-size:12px;">Choose from different layouts for your Pricing Tables</span><br/><br/><span class="dashicons dashicons-yes"></span> Tooltips for your features<br/><span style="color:#999999; font-size:12px;">Info bubbles that will show more text on hover</span><br/><br/><a style="display:inline-block; background:#33b690; padding:8px 25px 8px; border-bottom:3px solid #33a583; border-radius:3px; color:white;" class="wpd_pro_btn" target="_blank" href="http://wpdarko.com/items/responsive-pricing-table-pro">See all PRO features</a><br/><span style="display:block;margin-top:14px; font-size:13px; color:#0073AA; line-height:20px;"><span class="dashicons dashicons-tickets"></span> Code <strong>7832949</strong> (10% OFF)</span></div>',
                'id'   => $prefix . 'pro_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_info de_info_side',
        ));


    // Help
    $help_group = new_cmb2_box( array(
        'id' => $prefix . 'help_metabox',
        'title' => '<span style="font-weight:400;">'.__( 'Help & Support', 'dk-pricr-responsive-pricing-table' ).'</span>',
        'object_types' => array( 'rpt_pricing_table' ),
        'context' => 'side',
        'priority' => 'low',
        'row_classes' => 'de_hundred de_heading',
    ));

        $help_group->add_field( array(
            'name' => '',
                'desc' => '<span style="font-size:15px;">'.__( 'Display your Pricing Table', 'dk-pricr-responsive-pricing-table' ).'</span><br/><br/>'.__( 'To display your Pricing Table on your site, copy-paste the <strong>[Shortcode]</strong> in your post/page. You can find this shortcode by clicking <strong>All Pricing Tables</strong> in the menu on the left.', 'dk-pricr-responsive-pricing-table' ).'<br/><br/><span style="font-size:15px;">'.__( 'Get support', 'dk-pricr-responsive-pricing-table' ).'</span><br/><br/><a style="font-size:13px !important;" target="_blank" href="http://wpdarko.com/support/">— '.__( 'Submit a ticket', 'dk-pricr-responsive-pricing-table' ).'</a><br/><a style="font-size:13px !important;" target="_blank" href="https://wpdarko.zendesk.com/hc/en-us/articles/206303517-Get-started-with-the-Responsive-Pricing-Table-plugin">— '.__( 'View documentation', 'dk-pricr-responsive-pricing-table' ).'</a>',
                'id'   => $prefix . 'help_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_info de_info_side',
        ));



}

?>
