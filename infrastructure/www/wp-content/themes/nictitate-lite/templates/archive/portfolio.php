<?php 
$sb_footer_15 = apply_filters('nictitate_lite_get_sidebar', 'sidebar_15', 'pos_sidebar_15');
?>

<div id="main-content">
                
    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12 clearfix">
                <?php if ( is_active_sidebar( $sb_footer_15 ) )
                    dynamic_sidebar( $sb_footer_15 );
                ?>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->
    
    <div class="widget kopa-portfolio-widget">
        <div class="wrapper">
            <ul id="container" class="clearfix da-thumbs">
            
                <?php get_template_part( 'templates/content/contents' ); ?>

            </ul> <!-- #container -->
        </div><!--wrapper-->    
    </div><!--widget-->
    
</div><!--main-content-->