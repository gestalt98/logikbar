<?php 
$video = nictitate_lite_content_get_video( get_the_content() );
$nictitate_lite_current_layout = nictitate_lite_get_template_setting();
?>
<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article class="entry-item video-post <?php echo esc_attr( $nictitate_lite_current_layout['layout_id'] == 'blog-2-right-sidebar' ? 'clearfix' : '' ); ?>">
        <?php if ( ! empty( $video ) ) : ?> 
            <div class="entry-thumb">
                <div class="video-wrapper">
                    <?php 
                    $video = $video[0];
                    echo do_shortcode( $video['shortcode'] );
                    ?>
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