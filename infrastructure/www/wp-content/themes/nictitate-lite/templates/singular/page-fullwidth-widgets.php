<?php 
    $sb_footer_2  = apply_filters('nictitate_lite_get_sidebar', 'sidebar_2', 'pos_sidebar_2');
    $sb_footer_17 = apply_filters('nictitate_lite_get_sidebar', 'sidebar_17', 'pos_sidebar_17');
?>
<div id="main-content">
                        
    <?php get_template_part( 'templates/content/content', 'page-title' ); ?>

    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">
                <?php if ( is_active_sidebar( $sb_footer_2 ) )
                    dynamic_sidebar( $sb_footer_2 );
                ?>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->

    <div class="wrapper full-width">
        
        <?php if ( is_active_sidebar( $sb_footer_17 ) )
            dynamic_sidebar( $sb_footer_17 );
        ?>
        
    </div><!--wrapper-->

</div><!--main-content-->