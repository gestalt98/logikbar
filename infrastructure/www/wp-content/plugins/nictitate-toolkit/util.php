<?php

function nictitate_toolkit_get_post_widget_args(){
	
	$all_cats = get_terms('portfolio_project');
	$categories = array('' => __('-- none --', 'nictitate-toolkit'));
	foreach ( $all_cats as $cat ) {
		$categories[ $cat->slug ] = $cat->name;
	}

	$all_tags = get_terms('portfolio_tag');
	$tags = array('' => __('-- none --', 'nictitate-toolkit'));
	foreach( $all_tags as $tag ) {
		$tags[ $tag->slug ] = $tag->name;
	}

	return array(
		'title'  => array(
			'type'  => 'text',
			'std'   => '',
			'label' => __( 'Title:', 'nictitate-toolkit' ),
		),
		'categories' => array(
			'type'    => 'multiselect',
			'std'     => '',
			'label'   => __( 'Categories:', 'nictitate-toolkit' ),
			'options' => $categories,
			'size'    => '5',
		),
		'relation'    => array(
			'type'    => 'select',
			'label'   => __( 'Relation:', 'nictitate-toolkit' ),
			'std'     => 'OR',
			'options' => array(
				'AND' => __( 'AND', 'nictitate-toolkit' ),
				'OR'  => __( 'OR', 'nictitate-toolkit' ),
			),
		),
		'tags' => array(
			'type'    => 'multiselect',
			'std'     => '',
			'label'   => __( 'Tags:', 'nictitate-toolkit' ),
			'options' => $tags,
			'size'    => '5',
		),
		'order' => array(
			'type'  => 'select',
			'std'   => 'DESC',
			'label' => __( 'Order:', 'nictitate-toolkit' ),
			'options' => array(
				'ASC'  => __( 'ASC', 'nictitate-toolkit' ),
				'DESC' => __( 'DESC', 'nictitate-toolkit' ),
			),
		),
		'orderby' => array(
			'type'  => 'select',
			'std'   => 'date',
			'label' => __( 'Orderby:', 'nictitate-toolkit' ),
			'options' => array(
				'date'          => __( 'Date', 'nictitate-toolkit' ),
				'rand'          => __( 'Random', 'nictitate-toolkit' ),
				'comment_count' => __( 'Number of comments', 'nictitate-toolkit' ),
			),
		),
		'number' => array(
			'type'    => 'number',
			'std'     => '5',
			'label'   => __( 'Number of posts:', 'nictitate-toolkit' ),
			'min'     => '1',
		)
	);
}

function nictitate_toolkit_get_post_widget_query( $instance ){
	$query = array(
		'post_type'      => 'post',
		'posts_per_page' => $instance['number'],
		'order'          => $instance['order'] == 'ASC' ? 'ASC' : 'DESC',
		'orderby'        => $instance['orderby'],
		'ignore_sticky_posts' => true
	);

	if ( $instance['categories'] ) {		
		if($instance['categories'][0] == '')
			unset($instance['categories'][0]);

		if ( $instance['categories'] ) {
			$query['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $instance['categories'],
			);
		}
	}

	if ( $instance['tags'] ) {
		if($instance['tags'][0] == '')
			unset($instance['tags'][0]);

		if ( $instance['tags'] ) {
			$query['tax_query'][] = array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => $instance['tags'],
			);
		}
	}

	if ( isset( $query['tax_query'] ) && 
		 count( $query['tax_query'] ) === 2 ) {
		$query['tax_query']['relation'] = $instance['relation'];
	}

	return apply_filters( 'friday_toolkit_get_post_widget_query', $query );
}

function nictitate_toolkit_widget_posttype_build_query( $instance ) {
    $default_query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post__not_in'   => array(),
        'ignore_sticky_posts' => 1,
        'categories'     => array(),
        'tags'           => array(),
        'relation'       => 'OR',
        'orderby'        => 'lastest',
        'cat_name'       => 'category',
        'tag_name'       => 'post_tag'
    );

    $instance = wp_parse_args( $instance, $default_query_args );

    $args = array(
        'post_type'           => $instance['post_type'],
        'posts_per_page'      => $instance['posts_per_page'],
        'post__not_in'        => $instance['post__not_in'],
        'ignore_sticky_posts' => $instance['ignore_sticky_posts']
    );

    $tax_query = array();

    if ( $instance['categories'] ) {
    	if($instance['categories'][0] == '')
			unset($instance['categories'][0]);

		if ( $instance['categories'] ) {
	        $tax_query[] = array(
	            'taxonomy' => $instance['cat_name'],
	            'field'    => 'slug',
	            'terms'    => $instance['categories']
	        );
	    }
    }

    if ( $instance['tags'] ) {
    	if($instance['tags'][0] == '')
			unset($instance['tags'][0]);

		if ( $instance['tags'] ) {
	        $tax_query[] = array(
	            'taxonomy' => $instance['tag_name'],
	            'field'    => 'slug',
	            'terms'    => $instance['tags']
	        );
	    }
    }

    if ( $instance['relation'] && count( $tax_query ) == 2 )
        $tax_query['relation'] = $instance['relation'];

    if ( $tax_query ) {
        $args['tax_query'] = $tax_query;
    }

    switch ( $instance['orderby'] ) {
    case 'most_comment':
        $args['orderby'] = 'comment_count';
        break;
    case 'random':
        $args['orderby'] = 'rand';
        break;
    default:
        $args['orderby'] = 'date';
        break;
    }
    
    return new WP_Query( $args );
}

function nictitate_toolkit_get_video_thumbnails_url($type, $url) {
    $thubnails = '';
    $matches = array();
    if ('youtube' === $type) {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        $file_url = "http://gdata.youtube.com/feeds/api/videos/" . $matches[0] . "?v=2&alt=jsonc";
        $results = wp_remote_get($file_url);

        if (!is_wp_error($results)) {
            $json = json_decode($results['body']);
            $thubnails = $json->data->thumbnail->hqDefault;
        }
    } else if ('vimeo' === $type) {
        preg_match_all('#(http://vimeo.com)/([0-9]+)#i', $url, $matches);
        $imgid = $matches[2][0];

        $results = wp_remote_get("http://vimeo.com/api/v2/video/$imgid.php");

        if (!is_wp_error($results)) {
            $hash = unserialize($results['body']);
            $thubnails = $hash[0]['thumbnail_large'];
        }
    }
    return $thubnails;
}