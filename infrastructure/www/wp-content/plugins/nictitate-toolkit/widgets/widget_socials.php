<?php

class Nictitate_Toolkit_Widget_Socials extends Kopa_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'kopa-social-widget';
		$this->widget_description = esc_html__( 'Socials Widget', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_socials';
		$this->widget_name        = esc_html__( '[NICTITATE] - Socials', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Title', 'nictitate-toolkit' )
			)
		);
		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget( $args, $instance ) {

		extract( $args );

		$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$facebook  = get_theme_mod( 'nictitate_lite_options_social_links_facebook_url' );
		$twitter   = get_theme_mod( 'nictitate_lite_options_social_links_twitter_url' );
		$rss       = get_theme_mod( 'nictitate_lite_options_social_links_rss_url' );
		$flickr    = get_theme_mod( 'nictitate_lite_options_social_links_flickr_url' );
		$pinterest = get_theme_mod( 'nictitate_lite_options_social_links_pinterest_url' );
		$dribbble  = get_theme_mod( 'nictitate_lite_options_social_links_dribbble_url' );
		$vimeo     = get_theme_mod( 'nictitate_lite_options_social_links_vimeo_url' );
		$youtube   = get_theme_mod( 'nictitate_lite_options_social_links_youtube_url' );
		$instagram = get_theme_mod( 'nictitate_lite_options_social_links_instagram_url' );

        echo wp_kses_post( $before_widget );

        if ( ! empty( $title ) )
            echo wp_kses_post( $before_title . $title . $after_title );

        if ( empty( $facebook ) &&
             empty( $twitter ) && 
             $rss == 'HIDE' &&
             empty( $flickr ) &&
             empty( $pinterest ) &&
             empty( $dribbble ) &&
             empty( $vimeo ) &&
             empty( $youtube ) && 
             empty( $instagram ) ) {
            
            echo wp_kses_post( $after_widget );
            return;
        }
        ?>

        <ul class="clearfix">
            <?php if ( ! empty( $twitter ) ) : ?>
                <li><a href="<?php echo esc_url( $twitter ); ?>" data-icon="&#xf099;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $facebook ) ) : ?>
                <li><a href="<?php echo esc_url( $facebook ); ?>" data-icon="&#xf09a;"></a></li>
            <?php endif; ?>

            <?php if ( $rss != 'HIDE' && $rss == '' ) : ?>
                <li><a href="<?php bloginfo( 'rss2_url' ); ?>" data-icon="&#xf09e;"></a></li>
            <?php elseif ( $rss != 'HIDE' ) : ?>
                <li><a href="<?php echo esc_url( $rss ); ?>" data-icon="&#xf09e;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $flickr ) ) : ?>
                <li><a href="<?php echo esc_url( $flickr ); ?>" data-icon="&#xf16e;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $pinterest ) ) : ?>
                <li><a href="<?php echo esc_url( $pinterest ); ?>" data-icon="&#xf0d2;"></a></li>
            <?php endif; ?>
            
            <?php if ( ! empty( $dribbble ) ) : ?>
                <li><a href="<?php echo esc_url( $dribbble ); ?>" data-icon="&#xf17d;"></a></li>
            <?php endif; ?>
            
            <?php if ( ! empty( $vimeo ) ) : ?>
                <li><a href="<?php echo esc_url( $vimeo ); ?>" data-icon="&#xf194;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $youtube ) ) : ?>
                <li><a href="<?php echo esc_url( $youtube ); ?>" data-icon="&#xf167;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $instagram ) ) : ?>
                <li><a href="<?php echo esc_url( $instagram ); ?>" data-icon="&#xf16d;"></a></li>
            <?php endif; ?>
        </ul>

        <?php 
        echo wp_kses_post( $after_widget );
	}
}

register_widget( 'Nictitate_Toolkit_Widget_Socials' );