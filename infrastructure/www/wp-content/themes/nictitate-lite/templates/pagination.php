<?php
echo '<div class="pagination clearfix">';
the_posts_pagination( array(
    'mid_size' => 2,
    'prev_text' => esc_html__( 'Previous', 'nictitate-lite' ),
    'next_text' => esc_html__( 'Next', 'nictitate-lite' ),
) );
echo '</div>';