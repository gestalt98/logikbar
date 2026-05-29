<?php

add_action('after_setup_theme', 'nictitate_lite_init');

function nictitate_lite_init(){	
	add_filter('kopa_customization_init_options', 'nictitate_lite_init_options');
}

function nictitate_lite_init_options($options){
	#Panels
	$options['panels'][] = array(	
	  'id'    => 'nictitate_lite_panel_general_setting',
	  'title' => esc_html__('General Setting', 'nictitate-lite'));
	
	#Sections
	$options['sections'][] = array(
    'id'    => 'nictitate_lite_section_logo_setting',
    'panel' => 'nictitate_lite_panel_general_setting',
    'title' => esc_html__('Logo Margin', 'nictitate-lite'));

	$options['sections'][] = array(
    'id'    => 'nictitate_lite_section_general_setting',
    'panel' => 'nictitate_lite_panel_general_setting',
    'title' => esc_html__('General Setting', 'nictitate-lite'));

    $options['sections'][] = array(
    'id'    => 'nictitate_lite_section_single_post',
    'title' => esc_html__('Single Post', 'nictitate-lite'));

    $options['sections'][] = array(
    'id'    => 'nictitate_lite_section_social_links',
    'title' => esc_html__('Social Links', 'nictitate-lite'));

    $options['sections'][] = array(
    'id'    => 'nictitate_lite_section_custom_css',
    'title' => esc_html__('Custom CSS', 'nictitate-lite'));

    #GENERAL SETTING
	#1. Top Margin
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_logo_margin_top',
		'label'       => esc_html__('Top margin:', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'text',
		'section'     => 'nictitate_lite_section_logo_setting',
		'transport'   => 'refresh');
	#2. Top Margin
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_logo_margin_left',
		'label'       => esc_html__('Left margin:', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'text',
		'section'     => 'nictitate_lite_section_logo_setting',
		'transport'   => 'refresh');
	#3. Right Margin
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_logo_margin_right',
		'label'       => esc_html__('Right margin:', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'text',
		'section'     => 'nictitate_lite_section_logo_setting',
		'transport'   => 'refresh');
	#4. Bottom Margin
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_logo_margin_bottom',
		'label'       => esc_html__('Bottom margin:', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'text',
		'section'     => 'nictitate_lite_section_logo_setting',
		'transport'   => 'refresh');

	#GENERAL SETTING
	#1. Header Top links
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_header_top_links_status',
		'label'       => esc_html__('Header Top links', 'nictitate-lite'),
		'default'     => 'show',
		'description' => esc_html__('SHOW / HIDE TOP LINKS (LOGIN, REGISTER LINKS)', 'nictitate-lite'),
		'type'        => 'radio',
		'choices'     => array(
			'show' => esc_html__('Show', 'nictitate-lite'),
			'hide' => esc_html__('Hide', 'nictitate-lite')
		),
		'section'     => 'nictitate_lite_section_general_setting',
		'transport'   => 'refresh');
	#2. Sticky Main Menu
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_sticky_menu_status',
		'label'       => esc_html__('Sticky Main Menu', 'nictitate-lite'),
		'default'     => 'enable',
		'description' => esc_html__('ENABLE / DISABLE STICKY MAIN MENU', 'nictitate-lite'),
		'type'        => 'radio',
		'choices'     => array(
			'enable'  => esc_html__('Enable', 'nictitate-lite'),
			'disable' => esc_html__('Disable', 'nictitate-lite')
		),
		'section'     => 'nictitate_lite_section_general_setting',
		'transport'   => 'refresh');
	#3. Site Layout
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_layout',
		'label'       => esc_html__('Site Layout', 'nictitate-lite'),
		'default'     => 'wide',
		'description' => esc_html__('CHOOSE A LAYOUT', 'nictitate-lite'),
		'type'        => 'radio',	
		'choices'     => array(
			'wide' => esc_html__('Wide', 'nictitate-lite'),
			'box' => esc_html__('Box', 'nictitate-lite')
		),
		'section'     => 'nictitate_lite_section_general_setting',
		'transport'   => 'refresh');
	#3. Main Content
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_play_video_in_lightbox',
		'label'       => esc_html__('Main Content', 'nictitate-lite'),
		'default'     => 'enable',
		'description' => esc_html__('PLAY VIDEO IN LIGHTBOX', 'nictitate-lite'),
		'type'        => 'radio',	
		'choices'     => array(
			'enable'  => esc_html__('Enable', 'nictitate-lite'),
			'disable' => esc_html__('Disable', 'nictitate-lite')
		),
		'section'     => 'nictitate_lite_section_general_setting',
		'transport'   => 'refresh');
	#7. Header Information
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_left_header_description',
		'label'       => esc_html__('Header Information', 'nictitate-lite'),
		'default'     => '',
		'description' => esc_html__('LEFT HEADER DESCRIPTION', 'nictitate-lite'),
		'type'        => 'text',
		'section'     => 'nictitate_lite_section_general_setting',
		'transport'   => 'refresh');
	#8. Header Information
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_left_header_description',
		'label'       => esc_html__('Header Information', 'nictitate-lite'),
		'default'     => '',
		'description' => esc_html__('LEFT HEADER DESCRIPTION', 'nictitate-lite'),
		'type'        => 'text',
		'section'     => 'nictitate_lite_section_general_setting',
		'transport'   => 'refresh');

	if ( post_type_exists( 'portfolio' ) ) {
		#9. Portfolio - Tags/Cat
		$options['settings'][] = array(
			'settings'    => 'nictitate_lite_options_portfolio_related_get_by',
			'label'       => esc_html__('Portfolio', 'nictitate-lite'),
			'default'     => 'portfolio_tag',
			'description' => esc_html__('SHOW RELATED PORTFOLIO BY', 'nictitate-lite'),
			'type'        => 'select',	
			'choices'     => array(
				'hide'              => esc_html__('-- Hide --', 'nictitate-lite'),
				'portfolio_project' => esc_html__('Project', 'nictitate-lite'),
				'portfolio_tag'     => esc_html__('Tag', 'nictitate-lite')
			),
			'section'     => 'nictitate_lite_section_general_setting',
			'transport'   => 'refresh');
		#10. Portfolio - Limit
		$options['settings'][] = array(
			'settings'    => 'nictitate_lite_options_portfolio_related_limit',
			'label'       => esc_html__('Portfolio', 'nictitate-lite'),
			'default'     => 3,
			'description' => esc_html__('LIMIT', 'nictitate-lite'),
			'type'        => 'text',
			'section'     => 'nictitate_lite_section_general_setting',
			'transport'   => 'refresh');
	}
	#11. Footer
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_copyright',
		'label'       => esc_html__('Footer', 'nictitate-lite'),
		'default'     => '',
		'description' => esc_html__('CUSTOM FOOTER', 'nictitate-lite'),
		'type'        => 'textarea',
		'section'     => 'nictitate_lite_section_general_setting',
		'transport'   => 'refresh');

	#SINGLE POST
	#1. About Author
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_post_about_author',
		'label'       => esc_html__('About Author', 'nictitate-lite'),
		'default'     => 'show',
		'choices'     => array(
			'show' => esc_html__('Show', 'nictitate-lite'),
			'hide' => esc_html__('Hide', 'nictitate-lite')
		),
		'type'        => 'radio',	
		'section'     => 'nictitate_lite_section_single_post',
		'transport'   => 'refresh');
	#2. Related Posts - Tags/Cat
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_post_related_get_by',
		'label'       => esc_html__('Related Posts', 'nictitate-lite'),
		'default'     => 'category',
		'description' => esc_html__('GET BY', 'nictitate-lite'),
		'type'        => 'select',	
		'choices'     => array(
			'hide'     => esc_html__('-- Hide --', 'nictitate-lite'),
			'post_tag' => esc_html__('Tags', 'nictitate-lite'),
			'category' => esc_html__('Category', 'nictitate-lite')
		),
		'section'     => 'nictitate_lite_section_single_post',
		'transport'   => 'refresh');
	#3. Related Posts - Limit
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_post_related_limit',
		'label'       => esc_html__('Related Posts', 'nictitate-lite'),
		'default'     => 5,
		'description' => esc_html__('LIMIT', 'nictitate-lite'),
		'type'        => 'number',
		'section'     => 'nictitate_lite_section_single_post',
		'transport'   => 'refresh');

	#SOCIAL LINKS
	#1. RSS URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_rss_url',
		'label'       => esc_html__('RSS URL', 'nictitate-lite'),
		'default'     => '',
		'description' => __('Display the RSS feed button with the default RSS feed or enter a custom feed below.<code>Enter "HIDE" if you want to hide it</code>', 'nictitate-lite'),
		'type'        => 'text',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#2. FACEBOOK URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_facebook_url',
		'label'       => esc_html__('FACEBOOK URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#3. TWITTER URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_twitter_url',
		'label'       => esc_html__('TWITTER URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#4. PINTEREST URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_pinterest_url',
		'label'       => esc_html__('PINTEREST URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#5. DRIBBBLE URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_dribbble_url',
		'label'       => esc_html__('DRIBBBLE URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#6. YOUTUBE URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_youtube_url',
		'label'       => esc_html__('YOUTUBE URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#7. FLICKR URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_flickr_url',
		'label'       => esc_html__('FLICKR URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#8. VIMEO URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_vimeo_url',
		'label'       => esc_html__('VIMEO URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');
	#9. INSTAGRAM URL
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_social_links_instagram_url',
		'label'       => esc_html__('INSTAGRAM URL', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'url',	
		'section'     => 'nictitate_lite_section_social_links',
		'transport'   => 'refresh');

	#CUSTOM CSS
	#1. Custom CSS
	$options['settings'][] = array(
		'settings'    => 'nictitate_lite_options_custom_css',
		'label'       => esc_html__('Custom CSS', 'nictitate-lite'),
		'default'     => '',
		'type'        => 'textarea',
		'section'     => 'nictitate_lite_section_custom_css',
		'transport'   => 'refresh');

	return apply_filters( 'nictitate_lite_init_options', $options );
}