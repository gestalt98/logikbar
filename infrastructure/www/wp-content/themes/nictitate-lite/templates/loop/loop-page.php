<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    
    <?php get_template_part( 'templates/content/content', 'page' ); ?>
  
    <?php comments_template(); ?>

<?php endwhile; else : ?>

    <?php get_template_part( 'templates/content/content', 'notfound' ); ?>

<?php endif; ?>