<?php

class Inbound_Core_Email_Templates_Settings {

    public function __construct() {
        self::add_hooks();
    }

    public static function add_hooks() {


        add_filter( 'wpleads_define_global_settings' , array( __CLASS__ , 'extend_settings') ) ;
        add_filter( 'inbound_email_response/subject' , array( __CLASS__ , 'filter_lead_email_response_subject') , 10 ,2 );
        add_filter( 'inbound_email_response/body' , array( __CLASS__ , 'filter_lead_email_response_body') , 10 ,2 );
        add_filter( 'inbound_new_lead_notification/subject' , array( __CLASS__ , 'replace_new_lead_notification_template_subject') );
        add_filter( 'inbound_new_lead_notification/body' , array( __CLASS__ , 'replace_new_lead_notification_template_body') );
        add_action( 'inbound-forms/before-email-reponse-setup' , array( __CLASS__ , 'extend_inbound_forms' ));
    }


    /**
     * Add setting to Leads 'Global Settings' to enable/disable email template replacement
     */
    public static function extend_settings( $settings ) {
        // Setup navigation and display elements
        $tab_slug = 'wpl-main';
        $settings[$tab_slug]['settings'][] = array(
            'id'  => 'inbound_email_replace_core_template',
            'option_name'  => 'inbound_email_replace_core_template',
            'label' => __('Replace WordPress Email Templates with Inbound Now Email Templates' , 'leads' ),
            'description' => __("This option replaces frequently used core WordPress email templates with Inbound Now templates that are editable within the Leads->Email Templates area. If your website is set to a a language besides English it may be best to turn this off until test strings have been translated for your language." , 'leads' ),
            'type'  => 'radio',
            'default'  => '1',
            'options' => array('1'=>'On','0'=>'Off')
        );


        return $settings;
    }



    /**
     * replace new lead notification email template subject with user defined one
     */
    public static function replace_new_lead_notification_template_subject( $template ) {

        $email_template = array();
        $templates = get_posts(array(
            'post_type' => 'email-template',
            'posts_per_page' => 1,
            'meta_key' => '_inbound_template_id',
            'meta_value' => 'inbound-new-lead-notification'
        ));
        foreach ( $templates as $template ) {
            $email_template['ID'] = $template->ID;
            $email_template['subject'] = get_post_meta( $template->ID , 'inbound_email_subject_template' , true );
        }

        return ( !empty($email_template['subject'])) ? $email_template['subject'] : $template;
    }

    /**
     * replace new lead notification email template body with user defined one
     */
    public static function replace_new_lead_notification_template_body( $template ) {

        $email_template = array();
        $templates = get_posts(array(
            'post_type' => 'email-template',
            'posts_per_page' => 1,
            'meta_key' => '_inbound_template_id',
            'meta_value' => 'inbound-new-lead-notification'
        ));
        foreach ( $templates as $template ) {
            $email_template['ID'] = $template->ID;
            $email_template['body'] = get_post_meta( $template->ID , 'inbound_email_body_template' , true );
        }

        return ( !empty($email_template['body'])) ? $email_template['body'] : $template;
    }


    /**
     * Add email response setup options back into Inbound Forms
     */
    public static function extend_inbound_forms() {
        global $post;
        $email_templates = self::get_email_templates();
        $email_template =	get_post_meta( $post->ID, 'inbound_email_send_notification_template' , TRUE );
        ?>

        <div style='display:block; overflow: auto;'>
            <div id=''>
                <label for="inbound_email_send_notification_template"><?php _e( 'Select Response Email Template' , 'inbound-pro' ); ?></label>
                <select name="inbound_email_send_notification_template" id="inbound_email_send_notification_template">
                    <option value='custom' <?php	selected( 'custom' , $email_template); ?>><?php _e( 'Do not use a premade email template' , 'inbound-pro' ); ?></option>
                    <?php

                    foreach ($email_templates as $id => $label) {
                        echo '<option value="'.$id.'" '. selected($id , $email_template , false ) .'>'.$label.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }

    /**
     * filter inbound form email response subject
     */
    public static function filter_lead_email_response_subject( $confirm_subject , $form_meta_data ) {
        $template_id = $form_meta_data['inbound_email_send_notification_template'];

        /* If Email Template Selected Use That */
        if ( !$template_id && $template_id == 'custom' ) {
            return $confirm_subject;
        }

        $template_array = self::get_email_template( $template_id );
        return ( !empty($template_array['subject']) ) ? $template_array['subject'] :  $confirm_subject;

    }


    /**
     * filter inbound form email response body
     */
    public static function filter_lead_email_response_body( $confirm_body , $form_meta_data ) {

        $template_id = $form_meta_data['inbound_email_send_notification_template'];

        /* If Email Template Selected Use That */
        if ( empty($template_id) || $template_id == 'custom' ) {
            return $confirm_body;
        }

        $template_array = self::get_email_template( $template_id );

        return ( !empty($template_array['body']) ) ? $template_array['body'] :  $confirm_body;

    }

    /**
     * get array of email templates
     * @return array
     */
    public static function get_email_templates() {


        $templates = get_posts(array(
            'post_type' => 'email-template',
            'posts_per_page' => -1
        ));


        foreach ( $templates as $template ) {
            $email_templates[$template->ID] = $template->post_title;
        }

        $email_templates = ( isset($email_templates) ) ? $email_templates : array();

        return $email_templates;

    }

    /**
     *  Get Email Template by ID
     */
    public static function get_email_template( $ID ) {

        $email_template = array();


        $email_template['subject'] = get_post_meta(  $ID , 'inbound_email_subject_template' , true );
        $email_template['body'] = get_post_meta(  $ID , 'inbound_email_body_template' , true );

        return $email_template;
    }
}

new Inbound_Core_Email_Templates_Settings;
