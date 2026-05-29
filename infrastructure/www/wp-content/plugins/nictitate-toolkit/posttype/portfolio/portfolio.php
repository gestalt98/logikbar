<?php

add_action('init', 'nictitate_toolkit_portfolio_init');

function nictitate_toolkit_portfolio_init() {
    $labels = array(
        'name' => esc_html__('Portfolios', 'nictitate-toolkit'),
        'singular_name' => esc_html__('Portfolio', 'nictitate-toolkit'),
        'add_new' => esc_html__('Add New', 'nictitate-toolkit'),
        'add_new_item' => esc_html__('Add New Item', 'nictitate-toolkit'),
        'edit_item' => esc_html__('Edit Item', 'nictitate-toolkit'),
        'new_item' => esc_html__('New Item', 'nictitate-toolkit'),
        'all_items' => esc_html__('All Items', 'nictitate-toolkit'),
        'view_item' => esc_html__('View Item', 'nictitate-toolkit'),
        'search_items' => esc_html__('Search Items', 'nictitate-toolkit'),
        'not_found' => esc_html__('No items found', 'nictitate-toolkit'),
        'not_found_in_trash' => esc_html__('No items found in Trash', 'nictitate-toolkit'),
        'parent_item_colon' => '',
        'menu_name' => esc_html__('Portfolio', 'nictitate-toolkit')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'menu_position' => 100,
        'supports' => array('title', 'thumbnail', 'excerpt', 'editor'),
        'can_export' => true,
        'register_meta_box_cb' => ''
    );

    register_post_type('portfolio', $args);

    $taxonomy_category_args = array(
        'public' => true,
        'hierarchical' => true,
        'labels' => array(
            'name' => esc_html__('Portfolio Projects', 'taxonomy general name', 'nictitate-toolkit'),
            'singular_name' => esc_html__('Project', 'taxonomy singular name', 'nictitate-toolkit'),
            'search_items' => esc_html__('Search Project', 'nictitate-toolkit'),
            'popular_items' => esc_html__('Popular Projects', 'nictitate-toolkit'),
            'all_items' => esc_html__('All Projects', 'nictitate-toolkit'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Project', 'nictitate-toolkit'),
            'update_item' => esc_html__('Update Project', 'nictitate-toolkit'),
            'add_new_item' => esc_html__('Add New Project', 'nictitate-toolkit'),
            'new_item_name' => esc_html__('New Project Name', 'nictitate-toolkit'),
            'separate_items_with_commas' => esc_html__('Separate projects with commas', 'nictitate-toolkit'),
            'add_or_remove_items' => esc_html__('Add or remove category', 'nictitate-toolkit'),
            'choose_from_most_used' => esc_html__('Choose from the most used projects', 'nictitate-toolkit'),
            'menu_name' => esc_html__('Projects', 'nictitate-toolkit')
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'portfolio_project')
    );

    register_taxonomy('portfolio_project', 'portfolio', $taxonomy_category_args);

    #TAXONOMY TAG
    $taxonomy_tag_args = array(
        'public' => true,
        'hierarchical' => false,
        'labels' => array(
            'name' => esc_html__('Portfolio Tags', 'taxonomy general name', 'nictitate-toolkit'),
            'singular_name' => esc_html__('Tag', 'taxonomy singular name', 'nictitate-toolkit'),
            'search_items' => esc_html__('Search Tag', 'nictitate-toolkit'),
            'popular_items' => esc_html__('Popular Tags', 'nictitate-toolkit'),
            'all_items' => esc_html__('All Tags', 'nictitate-toolkit'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Tag', 'nictitate-toolkit'),
            'update_item' => esc_html__('Update Tag', 'nictitate-toolkit'),
            'add_new_item' => esc_html__('Add New Tag', 'nictitate-toolkit'),
            'new_item_name' => esc_html__('New Tag Name', 'nictitate-toolkit'),
            'separate_items_with_commas' => esc_html__('Separate tags with commas', 'nictitate-toolkit'),
            'add_or_remove_items' => esc_html__('Add or remove tag', 'nictitate-toolkit'),
            'choose_from_most_used' => esc_html__('Choose from the most used tags', 'nictitate-toolkit'),
            'menu_name' => esc_html__('Tags', 'nictitate-toolkit')
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'portfolio_tag')
    );

    register_taxonomy('portfolio_tag', 'portfolio', $taxonomy_tag_args);

    flush_rewrite_rules(false);    
}