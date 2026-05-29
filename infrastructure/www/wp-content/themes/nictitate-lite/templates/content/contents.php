<?php
if ( is_home() ) {
    get_template_part( 'templates/loop/loop', 'blog' );
} elseif ( is_single() ) {
    get_template_part( 'templates/loop/loop', 'single' );
} elseif ( is_page() ) {
    get_template_part( 'templates/loop/loop', 'page' );
} elseif ( is_post_type_archive('portfolio') || is_tax('portfolio_project') || is_tax('portfolio_tag') ) {
    get_template_part( 'templates/loop/loop', 'portfolio' );
} else {
    get_template_part( 'templates/loop/loop', 'blog' );
}