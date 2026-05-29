<?php
add_filter('kopa_current_tab_default', 'nictitate_lite_set_default_tab');
add_filter('kopa_settings_theme_options_enable', '__return_false');
add_action('after_setup_theme', 'nictitate_lite_front_after_setup_theme');

function nictitate_lite_front_after_setup_theme() {
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
    add_theme_support('title-tag');
    add_theme_support('post-formats', array('gallery', 'audio', 'video'));
    add_theme_support('post-thumbnails');
    add_theme_support('loop-pagination');
    add_theme_support('automatic-feed-links');

    $cbg_defaults = array(
        'default-color' => '',
        'default-image' => '',
        'wp-head-callback' => 'nictitate_lite_custom_background_cb',
        'admin-head-callback' => '',
        'admin-preview-callback' => ''
    );
    add_theme_support('custom-background', $cbg_defaults);

    global $content_width;
    if (!isset($content_width))
        $content_width = 806;

    register_nav_menus(array(
        'main-nav' => esc_html__('Main Menu', 'nictitate-lite'),
        'bottom-nav' => esc_html__('Bottom Menu', 'nictitate-lite')
    ));

    if (!is_admin()) {
        add_action('wp_enqueue_scripts', 'nictitate_lite_front_enqueue_scripts');
        add_action('wp_head', 'nictitate_lite_head');
        add_filter('post_class', 'nictitate_lite_post_class');
        add_filter('body_class', 'nictitate_lite_body_class');
        add_filter('comment_reply_link', 'nictitate_lite_comment_reply_link');
        add_filter('edit_comment_link', 'nictitate_lite_edit_comment_link');
        add_filter('excerpt_more', 'nictitate_lite_new_excerpt_more');
        add_filter('nictitate_lite_get_sidebar', 'nictitate_lite_set_sidebar', 10, 2);
    } else {
        add_filter('image_size_names_choose', 'nictitate_lite_image_size_names_choose');
    }


    /* Add theme's image sizes */
    $sizes = apply_filters('nictitate_lite_get_image_sizes', array(
        'kopa-image-size-0' => array(806, 393, true, esc_html__('Single Post Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-1' => array(251, 199, true, esc_html__('Thumbnail pm posts list widget (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-2' => array(80, 80, true, esc_html__('Testimonial avatar (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-3' => array(252, 201, true, esc_html__('Post Carousel Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-4' => array(150, 38, true, esc_html__('Client Logo (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-5' => array(118, 118, true, esc_html__('Portfolio Thumbnail 1 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-6' => array(118, 239, true, esc_html__('Portfolio Thumbnail 2 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-7' => array(239, 118, true, esc_html__('Portfolio Thumbnail 3 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-8' => array(239, 239, true, esc_html__('Portfolio Thumbnail 4 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-9' => array(1086, 529, true, esc_html__('Single Post Fullwidth Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-10' => array(104, 84, true, esc_html__('Products Widget Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-11' => array(531, 326, true, esc_html__('About Widget Slider Image (Kopatheme)', 'nictitate-lite')),
    ));
    foreach ($sizes as $slug => $details) {
        add_image_size($slug, $details[0], $details[1], $details[2]);
    }
}

function nictitate_lite_set_default_tab($key) {
 return 'sidebar-manager'; // layout-manager, backup-manager
}

function nictitate_lite_comment_reply_link($link) {
    return str_replace('comment-reply-link', 'comment-reply-link small-button green-button', $link);
}

function nictitate_lite_edit_comment_link($link) {
    return str_replace('comment-edit-link', 'comment-edit-link small-button green-button', $link);
}

function nictitate_lite_post_class($classes) {
    if (is_single()) {
        $classes[] = 'entry-box';
        $classes[] = 'clearfix';
    }
    return $classes;
}

function nictitate_lite_get_template_setting($default = null) {
    if(function_exists('kopa_get_template_setting')){
        return kopa_get_template_setting();
    }    

    return $default;
}

function nictitate_lite_body_class($classes) {
    $template_setting = nictitate_lite_get_template_setting();

    if (is_front_page()) {
        $classes[] = 'home-page';
    } else {
        $classes[] = 'sub-page';
    }

    switch ($template_setting['layout_id']) {
        case 'home-page-1':
            $classes[] = 'kopa-home-2';
            break;
        case 'home-page-2':
            $classes[] = 'kopa-home-3';
            break;
        case 'home-page-3':
            $classes[] = 'kopa-home-4';
            break;
        case 'blog-right-sidebar':
            $classes[] = 'kopa-blog-1';
            break;
        case 'blog-2-right-sidebar':
            $classes[] = 'kopa-blog-2';
            break;
        case 'single-right-sidebar':
            $classes[] = 'kopa-single-standard-1';
            break;
        case 'single-2-right-sidebar':
            $classes[] = 'kopa-single-standard-2';
            break;
        case 'portfolio':
            $classes[] = 'kopa-portfolio-page';
            break;
        case 'page-fullwidth-widgets':
            $classes[] = 'kopa-about-page';
            break;
    }

    return $classes;
}

function nictitate_lite_front_enqueue_scripts() {
    global $wp_styles;

    $dir = get_template_directory_uri();

    /* STYLESHEETs */

    wp_enqueue_style('bootstrap', $dir . '/css/bootstrap.css', array(), NULL, 'screen');
    wp_enqueue_style('font-awesome', $dir . '/css/font-awesome.css', array(), NULL);
    wp_enqueue_style('superfish', $dir . '/css/superfish.css', array(), NULL, 'screen');
    wp_enqueue_style('prettyphoto', $dir . '/css/prettyPhoto.css', array(), NULL, 'screen');
    wp_enqueue_style('flexlisder', $dir . '/css/flexslider.css', array(), NULL, 'screen');
    wp_enqueue_style('kopa-sequence-style', $dir . '/css/sequencejs-theme.modern-slide-in.css', array(), NULL, 'screen');
    wp_enqueue_style('kopa-style', get_stylesheet_uri(), array(), NULL);
    wp_enqueue_style('kopa-bootstrap-responsive', $dir . '/css/bootstrap-responsive.css', array(), NULL);
    wp_enqueue_style('kopa-extra-style', $dir . '/css/extra.css', array(), NULL);
    wp_enqueue_style('kopa-responsive', $dir . '/css/responsive.css', array(), NULL);
    wp_enqueue_style('kopa-font-rokkitt', '//fonts.googleapis.com/css?family=Rokkitt', array(), NULL);
    wp_enqueue_style('kopa-font-open-san', '//fonts.googleapis.com/css?family=Open+Sans', array(), NULL);
    wp_enqueue_style('kopa-font-raleway', '//fonts.googleapis.com/css?family=Raleway:400,600,500', array(), NULL);

    wp_register_style('kopa-ie', $dir . '/css/ie.css', array(), NULL);
    $wp_styles->add_data('kopa-ie', 'conditional', 'lt IE 9');
    wp_enqueue_style('kopa-ie');

    /* JAVASCRIPTs */

    wp_enqueue_script('kopa-modernizr', $dir . '/js/modernizr.custom.js');
    wp_localize_script('jquery', 'kopa_front_variable', nictitate_lite_front_localize_script());

    wp_enqueue_script('superfish', $dir . '/js/superfish.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('kopa-retina', $dir . '/js/retina.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('bootstrap', $dir . '/js/bootstrap.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('kopa-hoverdir', $dir . '/js/jquery-hoverdir.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('caroufredsel', $dir . '/js/jquery-caroufredsel.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('flexlisder', $dir . '/js/jquery-flexslider-min.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('pretty-photo', $dir . '/js/jquery-prettyPhoto.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('jquery-validate', $dir . '/js/jquery-validate-min.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('jquery-form', $dir . '/js/jquery-form.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('kopa-jquery-sequence', $dir . '/js/sequence-jquery-min.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('kopa-classie', $dir . '/js/classie.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('kopa-custom', $dir . '/js/custom.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script( 'html5js', $dir .'/js/html5.js', array('jquery'), NULL, true );
    wp_enqueue_script( 'css3-mediaqueries', $dir .'/js/css3-mediaqueries.js', array('jquery'), NULL, true );
    wp_script_add_data( 'html5js', 'conditional', 'lt IE 9' );
    wp_script_add_data( 'css3-mediaqueries', 'conditional', 'lt IE 9' );
    // send localization to frontend
    wp_localize_script('kopa-custom', 'nictitate_lite_custom_front_localization', nictitate_lite_custom_front_localization());

    if (is_single() || is_page()) {
        wp_enqueue_script('comment-reply');
    }
}

function nictitate_lite_front_localize_script() {
    $kopa_variable = array(
        'ajax' => array(
            'url' => admin_url('admin-ajax.php')
        ),
        'template' => array(
            'post_id' => (is_singular()) ? get_queried_object_id() : 0
        )
    );
    return $kopa_variable;
}

/**
 * Send the translated texts to frontend
 * @package Circle
 * @since Circle 1.12
 */
function nictitate_lite_custom_front_localization() {
    $front_localization = array(
        'validate' => array(
            'form' => array(
                'submit' => esc_html__('Submit', 'nictitate-lite'),
                'sending' => esc_html__('Sending...', 'nictitate-lite')
            ),
            'name' => array(
                'required' => esc_html__('Please enter your name.', 'nictitate-lite'),
                'minlength' => esc_html__('At least {0} characters required.', 'nictitate-lite')
            ),
            'email' => array(
                'required' => esc_html__('Please enter your email.', 'nictitate-lite'),
                'email' => esc_html__('Please enter a valid email.', 'nictitate-lite')
            ),
            'url' => array(
                'required' => esc_html__('Please enter your url.', 'nictitate-lite'),
                'url' => esc_html__('Please enter a valid url.', 'nictitate-lite')
            ),
            'message' => array(
                'required' => esc_html__('Please enter a message.', 'nictitate-lite'),
                'minlength' => esc_html__('At least {0} characters required.', 'nictitate-lite')
            )
        )
    );

    return $front_localization;
}

/* FUNCTION */

function nictitate_lite_image_size_names_choose($sizes) {
    $nictitate_lite_sizes = apply_filters('nictitate_lite_get_image_sizes', array(
        'kopa-image-size-0' => array(806, 393, TRUE, esc_html__('Single Post Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-1' => array(251, 199, TRUE, esc_html__('Thumbnail pm posts list widget (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-2' => array(80, 80, TRUE, esc_html__('Testimonial avatar (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-3' => array(252, 201, TRUE, esc_html__('Post Carousel Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-4' => array(150, 38, TRUE, esc_html__('Client Logo (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-5' => array(118, 118, TRUE, esc_html__('Portfolio Thumbnail 1 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-6' => array(118, 239, TRUE, esc_html__('Portfolio Thumbnail 2 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-7' => array(239, 118, TRUE, esc_html__('Portfolio Thumbnail 3 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-8' => array(239, 239, TRUE, esc_html__('Portfolio Thumbnail 4 (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-9' => array(1086, 529, TRUE, esc_html__('Single Post Fullwidth Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-10' => array(104, 84, TRUE, esc_html__('Products Widget Thumbnail (Kopatheme)', 'nictitate-lite')),
        'kopa-image-size-11' => array(531, 326, TRUE, esc_html__('About Widget Slider Image (Kopatheme)', 'nictitate-lite'))
    ));
    foreach ($nictitate_lite_sizes as $size => $image) {
        $width = ($image[0]) ? $image[0] : esc_html__('auto', 'nictitate-lite');
        $height = ($image[1]) ? $image[1] : esc_html__('auto', 'nictitate-lite');
        $sizes[$size] = $image[3] . " ({$width} x {$height})";
    }
    return $sizes;
}

function nictitate_lite_breadcrumb() {
    if (is_main_query()) {
        global $post, $wp_query;

        $prefix = '';
        $current_class = 'current-page';
        $description = '';
        $breadcrumb_before = '<div id="breadcrumb-wrapper"><div class="wrapper"><div class="row-fluid"><div class="span12"><div class="breadcrumb">';
        $breadcrumb_after = '</div></div></div></div></div>';
        $breadcrumb_home = '<a href="' . esc_url( home_url( '/' ) ) . '">' . __('Home', 'nictitate-lite') . '</a>';
        $breadcrumb = '';
        ?>

        <?php
        if (is_home()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Blog', 'nictitate-lite'));
        } else if (is_post_type_archive('product') && jigoshop_get_page_id('shop')) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title(jigoshop_get_page_id('shop')));
        } else if (is_tag()) {
            $breadcrumb.= $breadcrumb_home;

            $term = get_term(get_queried_object_id(), 'post_tag');
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $term->name);
        } else if (is_category()) {
            $breadcrumb.= $breadcrumb_home;

            $category_id = get_queried_object_id();
            $terms_link = explode(',', substr(get_category_parents(get_queried_object_id(), TRUE, ','), 0, (strlen(',') * -1)));
            $n = count($terms_link);
            if ($n > 1) {
                for ($i = 0; $i < ($n - 1); $i++) {
                    $breadcrumb.= $prefix . $terms_link[$i];
                }
            }
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_category_by_ID(get_queried_object_id()));
        } else if (is_tax('product_cat')) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= '<a href="' . esc_url(get_page_link(jigoshop_get_page_id('shop'))). '">' . get_the_title(jigoshop_get_page_id('shop')) . '</a>';
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

            $parents = array();
            $parent = $term->parent;
            while ($parent):
                $parents[] = $parent;
                $new_parent = get_term_by('id', $parent, get_query_var('taxonomy'));
                $parent = $new_parent->parent;
            endwhile;
            if (!empty($parents)):
                $parents = array_reverse($parents);
                foreach ($parents as $parent):
                    $item = get_term_by('id', $parent, get_query_var('taxonomy'));
                    $breadcrumb .= '<a href="' . esc_url( get_term_link($item->slug, 'product_cat') ). '">' . $item->name . '</a>';
                endforeach;
            endif;

            $queried_object = get_queried_object();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $queried_object->name);
        } else if (is_tax('product_tag')) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= '<a href="' . esc_url ( get_page_link(jigoshop_get_page_id('shop')) ) . '">' . get_the_title(jigoshop_get_page_id('shop')) . '</a>';
            $queried_object = get_queried_object();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $queried_object->name);
        } else if (is_single()) {
            $breadcrumb.= $breadcrumb_home;

            if (get_post_type() === 'product') :

                $breadcrumb .= '<a href="' . esc_url ( get_page_link(jigoshop_get_page_id('shop')) ). '">' . get_the_title(jigoshop_get_page_id('shop')) . '</a>';

                if ($terms = get_the_terms($post->ID, 'product_cat')) :
                    $term = apply_filters('jigoshop_product_cat_breadcrumb_terms', current($terms), $terms);
                    $parents = array();
                    $parent = $term->parent;
                    while ($parent):
                        $parents[] = $parent;
                        $new_parent = get_term_by('id', $parent, 'product_cat');
                        $parent = $new_parent->parent;
                    endwhile;
                    if (!empty($parents)):
                        $parents = array_reverse($parents);
                        foreach ($parents as $parent):
                            $item = get_term_by('id', $parent, 'product_cat');
                            $breadcrumb .= '<a href="' . esc_url( get_term_link($item->slug, 'product_cat') ) . '">' . $item->name . '</a>';
                        endforeach;
                    endif;
                    $breadcrumb .= '<a href="' . esc_url( get_term_link($term->slug, 'product_cat') ). '">' . $term->name . '</a>';
                endif;

                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title());

            else :

                $categories = get_the_category(get_queried_object_id());
                if ($categories) {
                    foreach ($categories as $category) {
                        $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', esc_url( get_category_link($category->term_id) ), $category->name);
                    }
                }

                $post_id = get_queried_object_id();
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title($post_id));

            endif;
        } else if (is_page()) {
            if (!is_front_page()) {
                $post_id = get_queried_object_id();
                $breadcrumb.= $breadcrumb_home;
                $post_ancestors = get_post_ancestors($post);
                if ($post_ancestors) {
                    $post_ancestors = array_reverse($post_ancestors);
                    foreach ($post_ancestors as $crumb)
                        $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', esc_url(get_permalink($crumb)), get_the_title($crumb));
                }
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title(get_queried_object_id()));
            }
        } else if (is_year() || is_month() || is_day()) {
            $breadcrumb.= $breadcrumb_home;

            $date = array('y' => NULL, 'm' => NULL, 'd' => NULL);

            $date['y'] = get_the_time('Y');
            $date['m'] = get_the_time('m');
            $date['d'] = get_the_time('j');

            if (is_year()) {
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['y']);
            }

            if (is_month()) {
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', esc_url( get_year_link($date['y']) ), $date['y']);
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, date('F', mktime(0, 0, 0, $date['m'])));
            }

            if (is_day()) {
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', esc_url( get_year_link($date['y']) ), $date['y']);
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', esc_url( get_month_link($date['y'] ), $date['m']), date('F', mktime(0, 0, 0, $date['m'])));
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['d']);
            }
        } else if (is_search()) {
            $breadcrumb.= $breadcrumb_home;

            $s = get_search_query();
            $c = $wp_query->found_posts;

            $description = sprintf(__('<span class="%1$s">Your search for "%2$s"', 'nictitate-lite'), $current_class, $s);
            $breadcrumb .= $prefix . $description;
        } else if (is_author()) {
            $breadcrumb.= $breadcrumb_home;
            $author_id = get_queried_object_id();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</a>', $current_class, sprintf(__('Posts created by %1$s', 'nictitate-lite'), get_the_author_meta('display_name', $author_id)));
        } else if (is_404()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Page not found', 'nictitate-lite'));
        }

        if ($breadcrumb)
            echo apply_filters('nictitate_lite_breadcrumb', $breadcrumb_before . $breadcrumb . $breadcrumb_after);
    }
}

function nictitate_lite_get_related_articles() {
    if (is_single()) {
        $get_by = get_theme_mod('nictitate_lite_options_post_related_get_by');
        if ('hide' != $get_by) {
            $limit = (int) get_theme_mod('nictitate_lite_options_post_related_limit');
            if ($limit > 0) {
                global $post;
                $taxs = array();
                if ('category' == $get_by) {
                    $cats = get_the_category(($post->ID));
                    if ($cats) {
                        $ids = array();
                        foreach ($cats as $cat) {
                            $ids[] = $cat->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                } else {
                    $tags = get_the_tags($post->ID);
                    if ($tags) {
                        $ids = array();
                        foreach ($tags as $tag) {
                            $ids[] = $tag->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'post_tag',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                }

                if ($taxs) {
                    $related_args = array(
                        'tax_query' => $taxs,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $limit
                    );
                    $related_posts = new WP_Query($related_args);
                    $carousel_id = ($related_posts->post_count > 3) ? 'related-widget' : 'related-widget-no-carousel';
                    if ($related_posts->have_posts()):
                        ?>
                        <div class="kopa-related-post">
                            <h3><span data-icon="&#xf040;"></span><?php esc_html_e('Related Posts', 'nictitate-lite'); ?></h3>
                            <div class="list-carousel responsive">
                                <ul class="kopa-related-post-carousel" id="<?php echo esc_attr( $carousel_id ); ?>">
                                    <?php
                                    while ($related_posts->have_posts()):
                                        $related_posts->the_post();
                                        $post_url = get_permalink();
                                        $post_title = get_the_title();
                                        ?>       
                                        <li style="width: 390px;">
                                            <article class="entry-item clearfix">
                                                <div class="entry-thumb hover-effect">
                                                    <?php
                                                    switch (get_post_format()) :

                                                        // video post format
                                                        case 'video':
                                                            $video = nictitate_lite_content_get_video(get_the_content());

                                                            if (!empty($video)) :
                                                                $video = $video[0];
                                                                ?>
                                                                <div class="mask">
                                                                    <a class="link-detail" rel="prettyPhoto" data-icon="&#xf04b;" href="<?php echo esc_url( $video['url'] ); ?>"></a>
                                                                </div>
                                                                <?php
                                                                if (has_post_thumbnail())
                                                                    the_post_thumbnail('kopa-image-size-1');
                                                                else
                                                                    echo '<img src="' . esc_url(kopa_get_video_thumbnails_url($video['type'], $video['url'])) . '">';

                                                            endif; // endif ! empty( $video )

                                                            break;

                                                        // gallery post format
                                                        case 'gallery':
                                                            $gallery = nictitate_lite_content_get_gallery(get_the_content());

                                                            if (!empty($gallery)) :

                                                                $shortcode = $gallery[0]['shortcode'];

                                                                // get gallery string ids
                                                                preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                                                                $gallery_string_ids = $gallery_string_ids[0][0];

                                                                // get array of image id
                                                                preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                                                                $gallery_ids = $gallery_ids[0];

                                                                $first_image_id = array_shift($gallery_ids);
                                                                $first_image_src = wp_get_attachment_image_src($first_image_id, 'kopa-image-size-1');
                                                                $first_full_image_src = wp_get_attachment_image_src($first_image_id, 'full');

                                                                $slug = 'gallery-' . get_the_ID();
                                                                ?>
                                                                <div class="mask">
                                                                    <a class="link-detail" rel="prettyPhoto[<?php echo esc_attr( $slug ); ?>]" data-icon="&#xf03e;" href="<?php echo esc_url( $first_full_image_src[0] ); ?>"></a>
                                                                </div>
                                                                <?php
                                                                foreach ($gallery_ids as $gallery_id) :
                                                                    $image_src = wp_get_attachment_image_src($gallery_id, 'full');
                                                                    ?>
                                                                    <a style="display: none" href="<?php echo esc_url( $image_src[0] ); ?>" rel="prettyPhoto[<?php echo esc_url( $slug ); ?>]"></a>
                                                                    <?php
                                                                endforeach;

                                                                if (has_post_thumbnail())
                                                                    the_post_thumbnail('kopa-image-size-1');
                                                                else
                                                                    echo '<img src="' . esc_url( $first_image_src[0] ) . '">';

                                                            endif; // endif ! empty ( $gallery )

                                                            break;

                                                        // default post format
                                                        default:
                                                            if (get_post_format() == 'quote')
                                                                $data_icon = '&#xf10d;';
                                                            elseif (get_post_format() == 'audio')
                                                                $data_icon = '&#xf001;';
                                                            else
                                                                $data_icon = '&#xf0c1;';
                                                            ?>
                                                            <div class="mask">
                                                                <a class="link-detail" data-icon="<?php echo esc_attr( $data_icon ); ?>" href="<?php the_permalink(); ?>"></a>
                                                            </div>
                                                            <?php
                                                            if (has_post_thumbnail())
                                                                the_post_thumbnail('kopa-image-size-1');
                                                            break;
                                                    endswitch;
                                                    ?>
                                                </div>
                                                <div class="entry-content">
                                                    <h6 class="entry-title"><a href="<?php echo esc_url( $post_url ); ?>"><?php echo wp_kses_post( $post_title ); ?></a><span></span></h6>
                                                    <span class="entry-date clearfix"><span class="fa fa-clock-o"></span><span><?php echo get_the_date(); ?></span></span>
                                                    <?php the_excerpt(); ?>
                                                </div><!--entry-content-->
                                            </article><!--entry-item-->
                                        </li>
                                        <?php
                                    endwhile;
                                    ?>
                                </ul>
                                <div class="clearfix"></div>
                                <?php if ($related_posts->post_count > 3): ?>
                                    <div class="carousel-nav clearfix">
                                        <a id="prev-4" class="carousel-prev" href="#">&lt;</a>
                                        <a id="next-4" class="carousel-next" href="#">&gt;</a>
                                    </div><!--end:carousel-nav-->
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endif;
                    wp_reset_postdata();
                }
            }
        }
    }
}

function nictitate_lite_get_related_portfolio() {
    if (is_singular('portfolio')) {
        $get_by = get_theme_mod('nictitate_lite_options_portfolio_related_get_by');
        if ('hide' != $get_by) {
            $limit = (int) get_theme_mod('nictitate_lite_options_portfolio_related_limit');
            if ($limit > 0) {
                global $post;
                $taxs = array();

                $terms = wp_get_post_terms($post->ID, $get_by);
                if ($terms) {
                    $ids = array();
                    foreach ($terms as $term) {
                        $ids[] = $term->term_id;
                    }
                    $taxs [] = array(
                        'taxonomy' => $get_by,
                        'field' => 'id',
                        'terms' => $ids
                    );
                }

                if ($taxs) {
                    $related_args = array(
                        'post_type' => 'portfolio',
                        'tax_query' => $taxs,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $limit
                    );
                    $related_portfolios = new WP_Query($related_args);
                    $carousel_id = ($related_portfolios->post_count > 3) ? 'related-widget' : 'related-widget-no-carousel';
                    if ($related_portfolios->have_posts()):
                        $index = 1;
                        ?>
                        <div class="kopa-related-post">
                            <h3><span data-icon="&#xf040;"></span><?php esc_html_e('Related Portfolios', 'nictitate-lite'); ?></h3>
                            <div class="list-carousel responsive">
                                <ul class="kopa-related-post-carousel" id="<?php echo esc_attr( $carousel_id ); ?>">
                                    <?php
                                    while ($related_portfolios->have_posts()):
                                        $related_portfolios->the_post();
                                        $post_url = get_permalink();
                                        $post_title = get_the_title();
                                        ?>       
                                        <li style="width: 390px;">
                                            <article class="entry-item clearfix">
                                                <div class="entry-thumb hover-effect">
                                                    <div class="mask">
                                                        <a class="link-detail" data-icon="&#xf0c1;" href="<?php the_permalink(); ?>"></a>
                                                    </div>
                                                    <?php
                                                    if (has_post_thumbnail())
                                                        the_post_thumbnail('kopa-image-size-1');
                                                    ?>
                                                </div>
                                                <div class="entry-content">
                                                    <h6 class="entry-title"><a href="<?php echo esc_url( $post_url ); ?>"><?php echo wp_kses_post( $post_title ); ?></a><span></span></h6>
                                                    <span class="entry-date clearfix"><span class="fa fa-clock-o"></span><span><?php echo get_the_date(); ?></span></span>
                                                    <?php the_excerpt(); ?>
                                                </div><!--entry-content-->
                                            </article><!--entry-item-->
                                        </li>
                                        <?php
                                    endwhile;
                                    ?>
                                </ul>
                                <div class="clearfix"></div>
                                <?php if ($related_portfolios->post_count > 3): ?>
                                    <div class="carousel-nav clearfix">
                                        <a id="prev-4" class="carousel-prev" href="#">&lt;</a>
                                        <a id="next-4" class="carousel-next" href="#">&gt;</a>
                                    </div><!--end:carousel-nav-->
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endif;
                    wp_reset_postdata();
                }
            }
        }
    }
}

function nictitate_lite_content_get_gallery($content, $enable_multi = false) {
    return nictitate_lite_content_get_media($content, $enable_multi, array('gallery'));
}

function nictitate_lite_content_get_video($content, $enable_multi = false) {
    return nictitate_lite_content_get_media($content, $enable_multi, array('vimeo', 'youtube'));
}

function nictitate_lite_content_get_audio($content, $enable_multi = false) {
    return nictitate_lite_content_get_media($content, $enable_multi, array('audio', 'soundcloud'));
}

function nictitate_lite_content_get_media($content, $enable_multi = false, $media_types = array()) {
    $media = array();
    $regex_matches = '';
    $regex_pattern = get_shortcode_regex();
    preg_match_all('/' . $regex_pattern . '/s', $content, $regex_matches);
    foreach ($regex_matches[0] as $shortcode) {
        $regex_matches_new = '';
        preg_match('/' . $regex_pattern . '/s', $shortcode, $regex_matches_new);

        if (in_array($regex_matches_new[2], $media_types)) :
            $media[] = array(
                'shortcode' => $regex_matches_new[0],
                'type' => $regex_matches_new[2],
                'url' => $regex_matches_new[5]
            );
            if (false == $enable_multi) {
                break;
            }
        endif;
    }

    return $media;
}

function nictitate_lite_head() {
    $logo_margin_top    = (int) get_theme_mod('nictitate_lite_options_logo_margin_top');
    $logo_margin_left   = (int) get_theme_mod('nictitate_lite_options_logo_margin_left');
    $logo_margin_right  = (int) get_theme_mod('nictitate_lite_options_logo_margin_right');
    $logo_margin_bottom = (int) get_theme_mod('nictitate_lite_options_logo_margin_bottom');
    if($logo_margin_bottom || $logo_margin_top || $logo_margin_left || $logo_margin_right){
        echo "<style>
            #logo-image{
                margin-top:{$logo_margin_top}px;
                margin-left:{$logo_margin_left}px;
                margin-right:{$logo_margin_right}px;
                margin-bottom:{$logo_margin_bottom}px;
            } 
        </style>";
    }

    /* ==================================================================================================
     * Custom CSS
     * ================================================================================================= */
    $kopa_theme_options_custom_css = wp_filter_nohtml_kses( get_theme_mod( 'nictitate_lite_options_custom_css' ) );

    if ($kopa_theme_options_custom_css)
        echo "<style>{$kopa_theme_options_custom_css}</style>";

    /* ==================================================================================================
     * IE8 Fix CSS3
     * ================================================================================================= */
    echo "<style>
        .kopa-button,
        .sequence-wrapper .next,
        .sequence-wrapper .prev,
        .kopa-intro-widget ul li .entry-title span,
        #main-content .widget .widget-title span,
        #main-content .widget .widget-title,
        .kopa-featured-product-widget .entry-item .entry-thumb .add-to-cart,
        .hover-effect .mask a.link-gallery,
        .hover-effect .mask a.link-detail,
        .kopa-testimonial-widget .testimonial-detail .avatar,
        .kopa-testimonial-widget .testimonial-detail .avatar img,
        .list-container-2 ul li span,
        .kopa-testimonial-slider .avatar,
        .kopa-testimonial-slider .avatar img,
        .about-author .avatar-thumb,
        .about-author .avatar-thumb img,
        #comments h3, .kopa-related-post h3, #respond h3,
        #comments h3 span, .kopa-related-post h3 span, #respond h3 span,
        #comments .comment-avatar,
        #comments .comment-avatar img,
        .kopa-our-team-widget ul li .our-team-social-link li a,
        .kp-dropcap.color {
            behavior: url(" . get_template_directory_uri() . "/js/PIE.htc);
        }
    </style>";
}


/* ==============================================================================
 * Mobile Menu
  ============================================================================= */

class Nictitate_Lite_Mobile_Menu extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

        if ($depth == 0)
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' clearfix"' : 'class="clearfix"';
        else
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : 'class=""';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . esc_url($item->url) . '"' : '';
        if ($depth == 0) {
            $item_output = $args->before;
            $item_output .= '<h3><a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a></h3>';
            $item_output .= $args->after;
        } else {
            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
        }
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0) {
            $output .= "\n$indent<span>+</span><div class='clear'></div><div class='menu-panel clearfix'><ul>";
        } else {
            $output .= '<ul>'; // indent for level 2, 3 ...
        }
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0) {
            $output .= "$indent</ul></div>\n";
        } else {
            $output .= '</ul>';
        }
    }

}

// end mobile menu walker class

function nictitate_lite_new_excerpt_more($more) {
    return '...';
}

/**
 * Custom background callback funtion for core custom background feature
 */
function nictitate_lite_custom_background_cb() {
    // $background is the saved custom image, or the default image.
    $background = set_url_scheme(get_background_image());

    // $color is the saved custom color.
    // A default has to be specified in style.css. It will not be printed here.
    $color = get_theme_mod('background_color');

    if (!$background && !$color)
        return;

    $style = $color ? "background-color: #$color;" : '';

    if ($background) {
        $image = " background-image: url('$background');";

        $repeat = get_theme_mod('background_repeat', get_theme_support('custom-background', 'default-repeat'));
        if (!in_array($repeat, array('no-repeat', 'repeat-x', 'repeat-y', 'repeat')))
            $repeat = 'repeat';
        $repeat = " background-repeat: $repeat;";

        $position = get_theme_mod('background_position_x', get_theme_support('custom-background', 'default-position-x'));
        if (!in_array($position, array('center', 'right', 'left')))
            $position = 'left';
        $position = " background-position: top $position;";

        $attachment = get_theme_mod('background_attachment', get_theme_support('custom-background', 'default-attachment'));
        if (!in_array($attachment, array('fixed', 'scroll')))
            $attachment = 'scroll';
        $attachment = " background-attachment: $attachment;";

        $style .= $image . $repeat . $position . $attachment;
    }
    ?>
    <style type="text/css" id="custom-background-css">
        body.kopa-boxed { <?php echo trim($style); ?> }
    </style>
    <?php
}

add_action( 'widgets_init', 'nictitate_lite_widgets_init' );
function nictitate_lite_widgets_init() {
    /**
    * Creates a sidebar
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Sidebar Extra', 'nictitate-lite' ),
        'id'            => 'sidebar-extra',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title"><span></span>',
        'after_title'   => '</h2>'
    );

    register_sidebar( $args );
}

add_action( 'nictitate_lite_after_post_content', 'nictitate_lite_print_post_navigation');