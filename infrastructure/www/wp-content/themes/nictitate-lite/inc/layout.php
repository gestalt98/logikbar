<?php

add_filter( 'kopa_layout_manager_settings', 'nictitate_lite_register_layouts');

function nictitate_lite_get_positions(){
	return apply_filters('friday_get_positions', array(		
		'pos_sidebar_1'  => esc_html__( 'WIDGET AREA 1', 'nictitate-lite' ),		
		'pos_sidebar_2'  => esc_html__( 'WIDGET AREA 2', 'nictitate-lite' ),
		'pos_sidebar_3'  => esc_html__( 'WIDGET AREA 3', 'nictitate-lite' ),
		'pos_sidebar_4'  => esc_html__( 'WIDGET AREA 4', 'nictitate-lite' ),
		'pos_sidebar_5'  => esc_html__( 'WIDGET AREA 5', 'nictitate-lite' ),
		'pos_sidebar_6'  => esc_html__( 'WIDGET AREA 6', 'nictitate-lite' ),
		'pos_sidebar_7'  => esc_html__( 'WIDGET AREA 7', 'nictitate-lite' ),
		'pos_sidebar_8'  => esc_html__( 'WIDGET AREA 8', 'nictitate-lite' ),
		'pos_sidebar_9'  => esc_html__( 'WIDGET AREA 9', 'nictitate-lite' ),
		'pos_sidebar_10' => esc_html__( 'WIDGET AREA 10', 'nictitate-lite' ),
		'pos_sidebar_11' => esc_html__( 'WIDGET AREA 11', 'nictitate-lite' ),
		'pos_sidebar_12' => esc_html__( 'WIDGET AREA 12', 'nictitate-lite' ),
		'pos_sidebar_13' => esc_html__( 'WIDGET AREA 13', 'nictitate-lite' ),
		'pos_sidebar_14' => esc_html__( 'WIDGET AREA 14', 'nictitate-lite' ),
		'pos_sidebar_15' => esc_html__( 'WIDGET AREA 15', 'nictitate-lite' ),
		'pos_sidebar_16' => esc_html__( 'WIDGET AREA 16', 'nictitate-lite' ),
		'pos_sidebar_17' => esc_html__( 'WIDGET AREA 17', 'nictitate-lite' )
	));
}

function nictitate_lite_get_sidebars(){
	return apply_filters('nictitate_lite_get_sidebars', array(		
		'pos_sidebar_1'  => 'sidebar_1',
		'pos_sidebar_2'  => 'sidebar_2',
		'pos_sidebar_3'  => 'sidebar_3',
		'pos_sidebar_4'  => 'sidebar_4',
		'pos_sidebar_5'  => 'sidebar_5',
		'pos_sidebar_6'  => 'sidebar_6',
		'pos_sidebar_7'  => 'sidebar_7',
		'pos_sidebar_8'  => 'sidebar_8',
		'pos_sidebar_9'  => 'sidebar_9',
		'pos_sidebar_10' => 'sidebar_10',
		'pos_sidebar_11' => 'sidebar_11',
		'pos_sidebar_12' => 'sidebar_12',
		'pos_sidebar_13' => 'sidebar_13',
		'pos_sidebar_14' => 'sidebar_14',
		'pos_sidebar_15' => 'sidebar_15',
		'pos_sidebar_16' => 'sidebar_16',
		'pos_sidebar_17' => 'sidebar_17'
	));
}

function nictitate_lite_register_layouts( $options ) {
	$positions = nictitate_lite_get_positions();
	$sidebars  = nictitate_lite_get_sidebars();

	$home_page_1 = array(
		'title'     => esc_html__( 'Home Page 1', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/home-page.jpg',
		'positions' => array(					
			'pos_sidebar_1',
            'pos_sidebar_2',
            'pos_sidebar_3',
            'pos_sidebar_4',
            'pos_sidebar_5',
            'pos_sidebar_6',
            'pos_sidebar_7',
            'pos_sidebar_8',
            'pos_sidebar_9',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$home_page_2 = array(
		'title'     => esc_html__( 'Home Page 2', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/home-page-2.jpg',
		'positions' => array(					
			'pos_sidebar_1',
            'pos_sidebar_2',
            'pos_sidebar_13',
            'pos_sidebar_5',
            'pos_sidebar_17',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$home_page_3 = array(
		'title'     => esc_html__( 'Home Page 3', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/home-page-3.jpg',
		'positions' => array(					
			'pos_sidebar_1',
            'pos_sidebar_2',
            'pos_sidebar_5',
            'pos_sidebar_16',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$blog_right_sidebar = array(
		'title'     => esc_html__( 'Blog 1', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/blog-1.jpg',
		'positions' => array(					
			'pos_sidebar_14',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$blog_2_right_sidebar = array(
		'title'     => esc_html__( 'Blog 2', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/blog-2.jpg',
		'positions' => array(					
			'pos_sidebar_14',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$single_right_sidebar = array(
		'title'     => esc_html__( 'Single 1', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/single-1.jpg',
		'positions' => array(	
			'pos_sidebar_14',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$single_2_right_sidebar = array(
		'title'     => esc_html__( 'Single 2', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/single-2.jpg',
		'positions' => array(
			'pos_sidebar_14',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$page_right_sidebar = array(
		'title'     => esc_html__( 'Page Right Sidebar', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/page.jpg',
		'positions' => array(					
			'pos_sidebar_14',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$page_fullwidth = array(
		'title'     => esc_html__( 'Page Full Width', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/page-fullwidth.jpg',
		'positions' => array(
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$page_fullwidth_widgets = array(
		'title'     => esc_html__( 'Page Full Width Widgets', 'nictitate-lite' ),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/page-fullwidth-widgets.jpg',
		'positions' => array(
			'pos_sidebar_2',
			'pos_sidebar_17',
            'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$error_404 = array(
		'title'     => esc_html__('404', 'nictitate-lite'),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/404.jpg',
		'positions' => array(			
			'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	$portfolio = array(
		'title'     => esc_html__('Portfolio', 'nictitate-lite'),
		'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/portfolio.jpg',
		'positions' => array(	
			'pos_sidebar_15',		
			'pos_sidebar_10',
            'pos_sidebar_11',
            'pos_sidebar_12'
		)
	);

	#1: Blog
	$options['blog-layout']['positions'] = $positions;
	$options['blog-layout']['layouts'] = array(		
		'blog-right-sidebar'   => $blog_right_sidebar,
		'blog-2-right-sidebar' => $blog_2_right_sidebar
	);
	$options['blog-layout']['default'] = array(
		'layout_id' => 'blog-right-sidebar',
		'sidebars'  => array(			
			'blog-right-sidebar'   => $sidebars,
			'blog-2-right-sidebar' => $sidebars
		)
	);

	#2: Single
	$options['post-layout']['positions'] = $positions;
	$options['post-layout']['layouts'] = array(
		'single-right-sidebar'   => $single_right_sidebar,
		'single-2-right-sidebar' => $single_2_right_sidebar
	);
	$options['post-layout']['default'] = array(
		'layout_id' => 'single-right-sidebar',
		'sidebars'  => array(
			'single-right-sidebar'   => $sidebars,
			'single-2-right-sidebar' => $sidebars
		)
	);

	#3: Page
	$options['page-layout']['positions'] = $positions;
    $options['page-layout']['layouts'] = array(
		'home-page-1'            => $home_page_1,
		'home-page-2'            => $home_page_2,
		'home-page-3'            => $home_page_3,
		'page-right-sidebar'     => $page_right_sidebar,
		'page-fullwidth'         => $page_fullwidth,
		'page-fullwidth-widgets' => $page_fullwidth_widgets
    );
    $options['page-layout']['default'] = array(
		'layout_id' => 'page-right-sidebar',
		'sidebars'  => array(
			'home-page-1'            => $sidebars,
			'home-page-2'            => $sidebars,
			'home-page-3'            => $sidebars,
			'page-right-sidebar'     => $sidebars,
			'page-fullwidth'         => $sidebars,
			'page-fullwidth-widgets' => $sidebars
        )
    );

    #4: Front Page
    $options['frontpage-layout']['positions'] = $positions;
    $options['frontpage-layout']['layouts'] = array(
        'home-page-1' => $home_page_1,
        'home-page-2' => $home_page_2,
        'home-page-3' => $home_page_3
    );
    $options['frontpage-layout']['default'] = array(
		'layout_id' => 'home-page-1',
		'sidebars'  => array(
            'home-page-1' => $sidebars,
	        'home-page-2' => $sidebars,
	        'home-page-3' => $sidebars
        )
    );

	#5: Search Page
    $options['search-layout']['positions'] = $positions;
    $options['search-layout']['layouts'] = array(
        'blog-right-sidebar'   => $blog_right_sidebar,
		'blog-2-right-sidebar' => $blog_2_right_sidebar
    );

    $options['search-layout']['default'] = array(
		'layout_id' => 'blog-right-sidebar',
		'sidebars'  => array(
            'blog-right-sidebar'   => $sidebars,
			'blog-2-right-sidebar' => $sidebars
        )
	);

	#6: Error 404
    $options['error404-layout']['positions'] = $positions;
    $options['error404-layout']['layouts'] = array(
        'error-404' => $error_404
    );

    $options['error404-layout']['default'] = array(
		'layout_id' => 'error-404',
		'sidebars'  => array(
            'error-404' => $sidebars
        )
    );

    if ( post_type_exists( 'portfolio' ) ) {
	    // Portfolio layout
		$options[] = array(
			'title'   => esc_html__( 'Portfolio', 'nictitate-lite' ),
			'type' 	  => 'title',
			'id' 	  => 'portfolio-title',
		);

		$options[] = array(
			'title'     => esc_html__( 'Portfolio', 'nictitate-lite' ),
			'type'      => 'layout_manager',
			'id'        => 'portfolio-layout',
			'positions' => $positions,
			'layouts'   => array(
				'portfolio'    => $portfolio,
			),
			'default' => array(
				'layout_id' => 'portfolio',
				'sidebars'  => array(
					'portfolio'    => $sidebars,
				),
			),
		);
	}

	return $options;
}

add_filter( 'kopa_custom_layout_arguments', 'nictitate_lite_edit_custom_layout_portfolio' );
add_filter( 'kopa_custom_template_setting_id', 'nictitate_lite_extra_template' );

function nictitate_lite_edit_custom_layout_portfolio( $args ) {

	$args[] = array(
		'screen'   => 'portfolio_project',
		'taxonomy' => true,
		'layout'   => 'portfolio-layout',
	);
	
	$args[] = array(
		'screen'   => 'portfolio_tag',
		'taxonomy' => true,
		'layout'   => 'portfolio-layout',
	);

	return $args;
}

function nictitate_lite_extra_template( $setting_id ) {
	if ( is_post_type_archive( 'portfolio' ) || 
		       is_tax( 'portfolio_project' ) || 
		       is_tax( 'portfolio_tag' ) ) {
		return 'portfolio-layout';
	}

	return $setting_id;
}