<?php

add_action( 'admin_init', 'nictitate_toolkit_options_post' );

function nictitate_toolkit_options_post() {

    $args = array(
        'id'          => 'nictitate-toolkit-slider-background-image-edit',
        'title'       => 'Sequence Slider Background Image',
        'desc'        => '',
        'pages'       => array( 'post' ),
        'context'     => 'normal',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'title'   => esc_html__('Slider Background Image', 'nictitate-toolkit'),
                'type'    => 'upload',
                'desc'    => esc_html__('Upload your own slider background image.'),
                'id'      => 'slider_background_image',
                'mimes'   => 'image',
            ),
        )
    );

    if ( function_exists( 'kopa_register_metabox' ) ) {
        kopa_register_metabox( $args );
    }
}