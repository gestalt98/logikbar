<?php


class Inbound_Core_Email_Templates_Activation {

    /**
     *  	flush rewrite rules
     */
    public function __construct() {

    }

    public static function activate() {
        Inbound_Core_Email_Templates_Post_Type::register_templates();

    }

    public static function deactivate() {

    }


}

/* Add Activation Hook */
register_activation_hook( INBOUND_EDIT_CORE_EMAIL_TEMPLATES_FILE , array( 'Inbound_Core_Email_Templates_Activation' , 'activate' ) );
register_deactivation_hook( INBOUND_EDIT_CORE_EMAIL_TEMPLATES_FILE , array( 'Inbound_Core_Email_Templates_Activation' , 'deactivate' ) );


