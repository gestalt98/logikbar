<?php 
    $sb_sidebar_1       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_1', 'pos_sidebar_1');
    $sb_sidebar_2       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_2', 'pos_sidebar_2');
    $sb_sidebar_3       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_3', 'pos_sidebar_3');
    $sb_sidebar_4       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_4', 'pos_sidebar_4');
    $sb_sidebar_5       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_5', 'pos_sidebar_5');
    $sb_sidebar_6       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_6', 'pos_sidebar_6');
    $sb_sidebar_7       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_7', 'pos_sidebar_7');
    $sb_sidebar_8       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_8', 'pos_sidebar_8');
    $sb_sidebar_9       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_9', 'pos_sidebar_9');
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
            <div class="widget-area-1 span6">

                <?php if ( is_active_sidebar( $sb_sidebar_3 ) ) 
                    dynamic_sidebar( $sb_sidebar_3 );
                ?>
                
            </div><!--widget-area-1-->
            
            <div class="widget-area-2 span6">

                <?php if ( is_active_sidebar( $sb_sidebar_4 ) ) 
                    dynamic_sidebar( $sb_sidebar_4 );
                ?>
                
            </div><!--widget-area-2-->
        </div><!--row-fluid-->
    </div><!--wrapper-->

    <div class="wrapper">

        <div class="row-fluid">
        
            <div class="span12 clearfix">
            
                <?php if ( is_active_sidebar( $sb_sidebar_5 ) ) 
                    dynamic_sidebar( $sb_sidebar_5 );
                ?>
            
            </div><!--span12-->
            
        </div><!--row-fluid-->

    </div><!--wrapper-->

</div> <!-- #main-menu -->


<?php if ( is_active_sidebar( $sb_sidebar_6 ) || is_active_sidebar( $sb_sidebar_7 ) || is_active_sidebar( $sb_sidebar_8 ) || is_active_sidebar( $sb_sidebar_9 ) ) { ?>

    <div id="page-bottom">
        <div class="wrapper">
            <div class="row-fluid">
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sb_sidebar_6 ) ) 
                        dynamic_sidebar( $sb_sidebar_6 );
                    ?>
                    
                </div><!--span3-->
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sb_sidebar_7 ) ) 
                        dynamic_sidebar( $sb_sidebar_7 );
                    ?>
                    
                </div><!--span3-->
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sb_sidebar_8 ) ) 
                        dynamic_sidebar( $sb_sidebar_8 );
                    ?>
                    
                </div><!--span3-->
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sb_sidebar_9 ) ) 
                        dynamic_sidebar( $sb_sidebar_9 );
                    ?>
                    
                </div><!--span3-->
                
            </div><!--row-fluid-->
        </div><!--wrapper-->
    </div><!--page-bottom-->

<?php } ?>
