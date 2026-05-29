<?php
#API
require get_template_directory() . '/api/TGMPluginActivation.class.php';

#FUNCTION
require get_template_directory() . '/inc/util.php';
require get_template_directory() . '/inc/front.php';

#FEATURED
require get_template_directory() . '/inc/layout.php';
require get_template_directory() . '/inc/sidebar.php';

/*
 * Icon selection field for main menu item
 */
require get_template_directory() . '/inc/custom-menu/custom_menu.php';

/*
 * Implement Custom Header features.
 */
require get_template_directory() . '/inc/options/custom-header.php';

#PLUGINS
require get_template_directory() . '/inc/plugin.php';

#CUSTOMIZE
require get_template_directory() . '/inc/lib/kopa-customization.php';
require get_template_directory() . '/inc/customize.php';
