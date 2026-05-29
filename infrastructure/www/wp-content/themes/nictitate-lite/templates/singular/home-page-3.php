<?php 
    $sb_sidebar_1  = apply_filters('nictitate_lite_get_sidebar', 'sidebar_1', 'pos_sidebar_1');
    $sb_sidebar_2  = apply_filters('nictitate_lite_get_sidebar', 'sidebar_2', 'pos_sidebar_2');
    $sb_sidebar_5  = apply_filters('nictitate_lite_get_sidebar', 'sidebar_5', 'pos_sidebar_5');
    $sb_sidebar_16 = apply_filters('nictitate_lite_get_sidebar', 'sidebar_16', 'pos_sidebar_16');
?>

<div id="main-content">
    <div class="widget">
        <?php if ( is_active_sidebar( $sb_sidebar_1 ) ) 
            dynamic_sidebar( $sb_sidebar_1 );
        ?>
    </div>

    <div class="wrapper">

        <div class="row-fluid">
        
            <div class="span12 clearfix">

                <?php // print content of front page 
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();

                        if ( get_the_content() ) {
                            get_template_part( 'templates/content/content', 'page' );
                        }
                    }
                } ?>
            
                <?php if ( is_active_sidebar( $sb_sidebar_2 ) ) 
                    dynamic_sidebar( $sb_sidebar_2 );
                ?>
            
            </div><!--span12-->
            
        </div><!--row-fluid-->

    </div><!--wrapper-->

    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">
                
                <div id="main-col">

                    <?php if ( is_active_sidebar( $sb_sidebar_5 ) ) 
                        dynamic_sidebar( $sb_sidebar_5 );
                    ?>

                </div>
                
                <div class="sidebar">
                    <?php if ( is_active_sidebar( $sb_sidebar_16 ) ) 
                        dynamic_sidebar( $sb_sidebar_16 );
                    ?>
                </div><!--sidebar-->
                
                <div class="clear"></div>
                
            </div><!--span12-->
        </div><!--row-fluid-->                
    </div><!--wrapper-->

</div>