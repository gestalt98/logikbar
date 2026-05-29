<?php

add_action( 'admin_init', 'nictitate_toolkit_options_testimonial' );

function nictitate_toolkit_options_testimonial() {

    $args = array(
        'id'          => 'nictitate-toolkit-testimonial-edit',
        'title'       => 'Meta box',
        'desc'        => '',
        'pages'       => array( 'testimonials' ),
        'context'     => 'normal',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'title'   => esc_html__('Author URL', 'nictitate-toolkit'),
                'type'    => 'url',
                'desc'    => 'Ex: http://kopatheme.com',
                'id'      => 'author_url',
                'default' => '#',
            ),
        )
    );

    if ( function_exists( 'kopa_register_metabox' ) ) {
        kopa_register_metabox( $args );
    }
}