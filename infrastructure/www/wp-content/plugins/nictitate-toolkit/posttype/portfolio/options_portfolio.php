<?php

add_action( 'admin_init', 'nictitate_toolkit_options_portfolio' );

function nictitate_toolkit_options_portfolio() {

    $args = array(
        'id'          => 'nictitate-toolkit-portfolio-edit',
        'title'       => 'Meta box',
        'desc'        => '',
        'pages'       => array( 'portfolio' ),
        'context'     => 'normal',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'title'   => esc_html__('Thumbnail size', 'nictitate-toolkit'),
                'type'    => 'select',
                'id'      => 'portfolio_thumbnail_size',
                'default' => '118x118',
                'options' => array(
                    '118x118' => '118 x 118',
                    '118x239' => '118 x 239',
                    '239x118' => '239 x 118',
                    '239x239' => '239 x 239'
                ),
            ),
        )
    );

    if ( function_exists( 'kopa_register_metabox' ) ) {
        kopa_register_metabox( $args );
    }
}