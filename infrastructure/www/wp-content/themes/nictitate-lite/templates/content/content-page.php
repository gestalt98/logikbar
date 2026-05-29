<?php 
    $nictitate_lite_current_layout = nictitate_lite_get_template_setting();
?>
<div id="page-<?php the_ID(); ?>" <?php post_class('entry-box clearfix') ?>>
    <?php if ( has_post_thumbnail() ) : 
        $thumbnail_id = get_post_thumbnail_id();
        $image_size = ( $nictitate_lite_current_layout['layout_id'] == 'page-fullwidth' ) ? 'kopa-image-size-9' : 'kopa-image-size-0';
        $thumbnail = wp_get_attachment_image_src( $thumbnail_id, $image_size );
    ?>
        <div class="entry-thumb">
            <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title_attribute(); ?>">
        </div>
    <?php endif; ?>

    <div class="elements-box">
        <?php the_content(); ?>

        <div class="page-pagination">

            <?php wp_link_pages(); ?>

        </div>
    </div>

</div><!--entry-box-->