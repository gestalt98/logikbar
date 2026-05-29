<?php 
    $nictitate_lite_current_layout = nictitate_lite_get_template_setting();
?>
<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article class="entry-item standard-post <?php echo ( $nictitate_lite_current_layout['layout_id'] == 'blog-2-right-sidebar' ) ? 'clearfix' : ''; ?>">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-thumb hover-effect">
            <div class="mask">
                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
            </div>
            <?php the_post_thumbnail( 'kopa-image-size-0' ); ?>
        </div>
        <?php endif; // endif has_post_thumbnail ?>
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