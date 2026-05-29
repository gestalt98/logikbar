<?php

class Nictitate_Toolkit_Widget_Posts_List extends Kopa_Widget {

	public function __construct() {

		$all_cats = get_categories();
		$categories = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach ( $all_cats as $cat ) {
			$categories[ $cat->slug ] = $cat->name;
		}

		$all_tags = get_tags();
		$tags = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach( $all_tags as $tag ) {
			$tags[ $tag->slug ] = $tag->name;
		}

		$this->widget_cssclass    = 'kopa-latest-post-widget';
		$this->widget_description = esc_html__( 'Display a posts list widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_posts_list';
		$this->widget_name        = esc_html__( '[NICTITATE] - Posts List', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Title:', 'nictitate-toolkit' ),
			),
			'categories' => array(
				'type'    => 'multiselect',
				'std'     => '',
				'label'   => esc_html__( 'Categories:', 'nictitate-toolkit' ),
				'options' => $categories,
				'size'    => '5',
			),
			'relation'    => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Relation:', 'nictitate-toolkit' ),
				'std'     => 'OR',
				'options' => array(
					'AND' => esc_html__( 'AND', 'nictitate-toolkit' ),
					'OR'  => esc_html__( 'OR', 'nictitate-toolkit' ),
				),
			),
			'tags' => array(
				'type'    => 'multiselect',
				'std'     => '',
				'label'   => esc_html__( 'Tags:', 'nictitate-toolkit' ),
				'options' => $tags,
				'size'    => '5',
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'date',
				'label' => esc_html__( 'Orderby:', 'nictitate-toolkit' ),
				'options' => array(
					'date'         => esc_html__( 'Date', 'nictitate-toolkit' ),
					'random'       => esc_html__( 'Random', 'nictitate-toolkit' ),
					'most_comment' => esc_html__( 'Number of comments', 'nictitate-toolkit' ),
				),
			),
			'posts_per_page' => array(
				'type'    => 'number',
				'std'     => '5',
				'label'   => esc_html__( 'Number of posts:', 'nictitate-toolkit' ),
				'min'     => '1',
			)
		);
		parent::__construct();
	}

	public function widget( $args, $instance ) {	

		extract( $args );
		
		extract( $instance );

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$posts = nictitate_toolkit_widget_posttype_build_query($instance);
		
		echo wp_kses_post( $before_widget );

		if ( ! empty ( $title ) )
            echo sprintf( '%s', $before_title . '<span data-icon="&#xf040;"></span>' . $title . $after_title ); ?>
        
        <ul class="clearfix">

        <?php 
        $post_index = 1;
        
        while ( $posts->have_posts() ) : $posts->the_post();
            $thumbnail_id = get_post_thumbnail_id();
            $large_thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'large' );
            $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-1' );
        ?>
            <li>
                <article class="entry-item clearfix">
                    <?php if ( get_post_format() == 'gallery' ) : 
                        $gallery = nictitate_lite_content_get_gallery( get_the_content() );
                        $slug = $this->get_field_id('gallery').'-'.get_the_ID();

                        if ( ! empty ($gallery) ) {
                            $gallery = $gallery[0];
                            $shortcode = $gallery['shortcode'];

                            // get gallery string ids
                            preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                            $gallery_string_ids = $gallery_string_ids[0][0];

                            // get array of image id
                            preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                            $gallery_ids = $gallery_ids[0];

							$first_image_id       = array_shift($gallery_ids);
							$first_image_src      = wp_get_attachment_image_src( $first_image_id, 'kopa-image-size-1' );
							$first_full_image_src = wp_get_attachment_image_src( $first_image_id, 'full' );

                        }
                    ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                
                                <?php if ( ! isset($first_image_src[0]) && has_post_thumbnail() ) : ?>
                                    <a class="link-gallery" href="<?php echo esc_url( $large_thumbnail[0] ); ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                                <?php elseif ( isset($first_image_src[0]) ) : ?>
                                    <a class="link-gallery" href="<?php echo esc_url( $first_full_image_src[0] ); ?>" data-icon="&#xf03e;" rel="prettyPhoto[<?php echo esc_attr( $slug ); ?>]"></a>
                                <?php endif; ?>

                                <?php if (isset($gallery_ids) && ! empty($gallery_ids)) {
                                    foreach( $gallery_ids as $gallery_id ) {
                                        $gallery_image_src = wp_get_attachment_image_src($gallery_id, 'full');
                                        echo '<a style="display: none;" href="'.$gallery_image_src[0].'" rel="prettyPhoto['.$slug.']"></a>';
                                    }
                                }; ?>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title(); ?>">
                            <?php elseif ( isset( $first_image_src[0] ) ) : ?>
                                <img src="<?php echo esc_url( $first_image_src[0] ); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>

                    <?php elseif ( get_post_format() == 'video' ) : 
                        $video = nictitate_lite_content_get_video( get_the_content() );

                        if ( ! empty( $video ) ) {
                            $video = $video[0];

                            if ( isset($video['type']) && isset($video['url']) ) {
                                $video_thumbnail = nictitate_toolkit_get_video_thumbnails_url( $video['type'], $video['url'] );
                            }
                        }

                        $enableLightbox = get_theme_mod('nictitate_lite_options_play_video_in_lightbox', 'enable');
                    ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                <?php if ( isset( $video['url'] ) ) : ?>
                                    <a class="link-gallery" href="<?php echo esc_url( $video['url'] ); ?>" data-icon="&#xf04b;" rel="<?php echo esc_attr( $enableLightbox == 'enable' ? 'prettyPhoto' : '' ); ?>"></a>
                                <?php elseif ( has_post_thumbnail() ) : ?>
                                    <a class="link-gallery" href="<?php echo esc_url( $large_thumbnail[0] ); ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                                <?php endif; ?>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title(); ?>">
                            <?php elseif ( isset($video_thumbnail) ) : ?>
                                <img width="251" src="<?php echo esc_url( $video_thumbnail ); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>

                    <?php else : ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                <a class="link-gallery" href="<?php echo esc_url( $large_thumbnail[0] ); ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="entry-content">
                        <h6 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span></span></h6>
                        <span class="entry-date clearfix"><span class="fa fa-clock-o"></span><?php the_time( get_option( 'date_format') ); ?></span>
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            </li>
        <?php 
        if ( $post_index % 2 == 0  && $post_index != $posts->post_count )
            echo '</ul><ul class="mt-20 clearfix">';
        $post_index++;

        endwhile; 

		$blogID   = get_option( 'page_for_posts' );
		$blogLink = get_page_link( $blogID );

        if ( ! empty( $blogLink ) ) :
        ?>
            <a href="<?php echo esc_url( $blogLink ); ?>" class="view-all"><?php esc_html_e( 'View All', 'nictitate-toolkit' ); ?> &raquo;</a>
        <?php 
        endif; 
        ?>

        </ul>
        <?php wp_reset_postdata();

		echo wp_kses_post( $after_widget );
			
	}

}
register_widget( 'Nictitate_Toolkit_Widget_Posts_List' );