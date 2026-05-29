<?php

add_action( 'admin_init', 'nictitate_toolkit_options_staff' );

function nictitate_toolkit_options_staff() {

    $args = array(
        'id'          => 'nictitate-toolkit-staff-edit',
        'title'       => 'Meta box',
        'desc'        => '',
        'pages'       => array( 'staffs' ),
        'context'     => 'normal',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'title'   => esc_html__('Position', 'nictitate-toolkit'),
                'type'    => 'text',
                'desc'    => 'Ex: Project Manager',
                'id'      => 'position',
                'default' => '#',
            ),
            array(
                'title'   => esc_html__('Facebook', 'nictitate-toolkit'),
                'type'    => 'url',
                'desc'    => 'Ex: http://kopatheme.com',
                'id'      => 'facebook',
                'default' => '#',
            ),
            array(
                'title'   => esc_html__('Twitter', 'nictitate-toolkit'),
                'type'    => 'url',
                'desc'    => 'Ex: http://kopatheme.com',
                'id'      => 'twitter',
                'default' => '#',
            ),
            array(
                'title'   => esc_html__('Google Plus', 'nictitate-toolkit'),
                'type'    => 'url',
                'desc'    => 'Ex: http://kopatheme.com',
                'id'      => 'gplus',
                'default' => '#',
            ),
        )
    );

    if ( function_exists( 'kopa_register_metabox' ) ) {
        kopa_register_metabox( $args );
    }
}