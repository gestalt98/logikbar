<?php 
$gallery = nictitate_lite_content_get_gallery( get_the_content() );
$nictitate_lite_current_layout = nictitate_lite_get_template_setting();
?>
<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article class="entry-item gallery-post <?php echo esc_attr( $nictitate_lite_current_layout['layout_id'] == 'blog-2-right-sidebar' ? 'clearfix' : '' ); ?>">
        <?php if ( ! empty( $gallery ) ) : ?> 
            <div class="entry-thumb">
                <?php
                if ( isset( $gallery[0] ) ) {
                    $gallery = $gallery[0];
                    if ( isset( $gallery['shortcode'] ) ) {
                        $shortcode = $gallery['shortcode'];

                        // get gallery string ids
                        preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                        if ( isset( $gallery_string_ids[0][0] ) ) {
                            $gallery_string_ids = $gallery_string_ids[0][0];

                            // get array of image id
                            preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                            if ( isset( $gallery_ids[0] ) ) {
                                $gallery_ids = $gallery_ids[0];
                            }
                        }
                    }
                }
                ?>
                
                <div class="flexslider blogpost-slider">
                    <ul class="slides">

                        <?php if ( isset( $gallery_ids ) && is_array( $gallery_ids ) ) :
                            foreach ( $gallery_ids as $id ) : 
                                $thumbnail = wp_get_attachment_image_src( $id, 'kopa-image-size-0' );
                            ?>
                            <li class="hover-effect">
                                <div class="mask">
                                    <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                </div>
                                <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title_attribute(); ?>">
                            </li>
                        <?php endforeach; 
                        endif; ?>

                    </ul>
                </div>
                
            </div>
        <?php endif; ?>                                     
        <div class="entry-content">
            <header>
                <h6 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span></span></h6>
                <span class="entry-date"><span class="fa fa-clock-o"></span><?php the_time( get_option( 'date_format' ) ); ?></span>
                <span class="entry-comments"><span class="fa fa-comment"></span><?php comments_popup_link(); ?></span>
            </header>
            <?php the_excerpt(); ?>
            <a class="more-link clearfix" href="<?php the_permalink(); ?>"><?php _e( 'Read more', 'nictitate-lite' ); ?> <span class="fa fa-forward"></span></a>
        </div>
    </article><!--entry-item-->
</li>