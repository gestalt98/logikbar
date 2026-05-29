<?php

add_action( 'admin_init', 'nictitate_toolkit_options_client' );

function nictitate_toolkit_options_client() {

    $args = array(
        'id'          => 'nictitate-toolkit-client-edit',
        'title'       => 'Meta box',
        'desc'        => '',
        'pages'       => array( 'clients' ),
        'context'     => 'normal',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'title'   => esc_html__('Client URL', 'nictitate-toolkit'),
                'type'    => 'url',
                'desc'    => 'Ex: http://kopatheme.com',
                'id'      => 'client_url',
                'default' => '#',
            ),
        )
    );

    if ( function_exists( 'kopa_register_metabox' ) ) {
        kopa_register_metabox( $args );
    }
}