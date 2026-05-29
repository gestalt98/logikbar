<?php

add_action( 'admin_init', 'nictitate_toolkit_options_service' );

function nictitate_toolkit_options_service() {

    $percent = array();
    for($i = 0; $i <= 100; $i++){
        $percent[$i] = $i.'%';
    }

    $pages = get_pages(); 
    $pages_arr = array('' => '---Select---');
    foreach ($pages as $page) {
        $pages_arr[$page->ID] = $page->post_title;
    }

    $args = array(
        'id'          => 'nictitate-toolkit-service-edit',
        'title'       => 'Meta box',
        'desc'        => '',
        'pages'       => array( 'services' ),
        'context'     => 'normal',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'title'   => esc_html__('Link to external page', 'nictitate-toolkit'),
                'type'    => 'url',
                'desc'    => 'Leave it blank if you want to use static page option below.',
                'id'      => 'service_external_page',
                'default' => '',
            ),
            array(
                'title'   => esc_html__('Link to static page', 'nictitate-toolkit'),
                'type'    => 'select',
                'id'      => 'service_static_page',
                'default' => '',
                'options' => $pages_arr
            ),
            array(
                'title'   => esc_html__('Service Expertise', 'nictitate-toolkit'),
                'type'    => 'select',
                'id'      => 'service_percentage',
                'default' => '',
                'options' => $percent,
            ),
            array(
                'title'   => esc_html__('Icon', 'nictitate-toolkit'),
                'type'    => 'icon',
                'id'      => 'icon_class',
            ),
        )
    );

    if ( function_exists( 'kopa_register_metabox' ) ) {
        kopa_register_metabox( $args );
    }
}