<?php 
$sb_footer_10       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_10', 'pos_sidebar_10');
$sb_footer_11       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_11', 'pos_sidebar_11');
$sb_footer_12       = apply_filters('nictitate_lite_get_sidebar', 'sidebar_12', 'pos_sidebar_12');

if ( is_active_sidebar( $sb_footer_10 ) || is_active_sidebar( $sb_footer_11 ) || is_active_sidebar( $sb_footer_12 ) ):
?>
    <div id="bottom-sidebar">
        <div class="wrapper">
            <div class="row-fluid">

                <div class="span4 widget-area-3">
                    <?php
                    if ( is_active_sidebar( $sb_footer_10 ) )
                        dynamic_sidebar( $sb_footer_10 );
                    ?>
                </div><!--span4-->

                <div class="span4 widget-area-4">
                    <?php
                    if (is_active_sidebar($sb_footer_11))
                        dynamic_sidebar($sb_footer_11);
                    ?>
                </div><!--span4-->

                <div class="span4 widget-area-5">
                    <?php
                    if (is_active_sidebar($sb_footer_12))
                        dynamic_sidebar($sb_footer_12);
                    ?>
                </div><!--span4-->

            </div><!--row-fluid-->
        </div><!--wrapper-->
    </div><!--bottom-sidebar-->
<?php endif; ?>


<footer id="page-footer">
    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">
                <p id="copyright"><?php echo stripslashes(get_theme_mod('nictitate_lite_options_copyright', 'Copyrights. &copy; 2014')); ?></p>
                <?php
                if (has_nav_menu('bottom-nav')) {
                    wp_nav_menu(array(
                        'theme_location' => 'bottom-nav',
                        'container' => '',
                        'items_wrap' => '<ul id="footer-menu" class="clearfix">%3$s</ul>',
                        'depth' => -1
                    ));
                }
                ?>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->
</footer><!--page-footer-->

<p id="back-top" style="display: block;">
    <a href="#top"><?php esc_html_e('Back to Top', 'nictitate-lite'); ?></a>
</p>

</div><!--kopa-wrapper-->

<?php wp_footer(); ?>
</body>
</html>