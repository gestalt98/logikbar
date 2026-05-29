<?php

class Nictitate_Toolkit_Widget_Contact_Form extends Kopa_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'kopa-contact-widget';
		$this->widget_description = esc_html__( 'Contact form widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_contact_form';
		$this->widget_name        = esc_html__( '[NICTITATE] - Contact Form', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Title', 'nictitate-toolkit' )
			),
            'description'  => array(
                'type'  => 'textarea',
                'std'   => '',
                'label' => esc_html__( 'Description', 'nictitate-toolkit' )
            ),
            'email'  => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__( 'Email', 'nictitate-toolkit' )
            ),
            'phone'  => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__( 'Phone', 'nictitate-toolkit' )
            ),
            'address'  => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__( 'Address', 'nictitate-toolkit' )
            ),
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

		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		
        $description  = $instance['description'];
        $email        = $instance['email'];
        $phone_number = $instance['phone'];
        $address      = $instance['address'];
        $facebook     = get_theme_mod( 'nictitate_lite_options_social_links_facebook_url' );
        $twitter      = get_theme_mod( 'nictitate_lite_options_social_links_twitter_url' );
        $rss          = get_theme_mod( 'nictitate_lite_options_social_links_rss_url' );
        $flickr       = get_theme_mod( 'nictitate_lite_options_social_links_flickr_url' );
        $pinterest    = get_theme_mod( 'nictitate_lite_options_social_links_pinterest_url' );
        $dribbble     = get_theme_mod( 'nictitate_lite_options_social_links_dribbble_url' );
        $vimeo        = get_theme_mod( 'nictitate_lite_options_social_links_vimeo_url' );
        $youtube      = get_theme_mod( 'nictitate_lite_options_social_links_youtube_url' );
        $instagram    = get_theme_mod( 'nictitate_lite_options_social_links_instagram_url' );

        echo wp_kses_post( $before_widget );
        ?>
        <div class="wrapper">
            <div class="row-fluid">
                <div class="span6">                             
                    <div id="contact-box">
                        <form id="contact-form" class="clearfix" action="<?php echo admin_url('admin-ajax.php') ?>" method="post">
                            <p class="input-block clearfix">
                                <label class="required" for="contact_name"><?php esc_html_e('Name', 'nictitate-toolkit'); ?> <span><?php esc_html_e('(required)', 'nictitate-toolkit'); ?></span>:</label>
                                <input class="valid" type="text" name="name" id="contact_name" value="">
                            </p>
                            <p class="input-block clearfix">
                                <label class="required" for="contact_email"><?php esc_html_e('Email', 'nictitate-toolkit'); ?> <span><?php esc_html_e('(required)', 'nictitate-toolkit'); ?></span>:</label>
                                <input type="email" class="valid" name="email" id="contact_email" value="">
                            </p>
                            <p class="input-block clearfix">
                                <label class="required" for="contact_subject"><?php esc_html_e('Subject:', 'nictitate-toolkit'); ?></label>
                                <input type="text" class="valid" name="subject" id="contact_subject" value="">
                            </p>
                            <p class="textarea-block clearfix">                        
                                <label class="required" for="contact_message"><?php esc_html_e('Message', 'nictitate-toolkit'); ?> <span><?php esc_html_e('(required)', 'nictitate-toolkit'); ?></span>:</label>
                                <textarea rows="6" cols="80" id="contact_message" name="message"></textarea>
                            </p>                            
                            <p class="contact-button clearfix">                    
                                <input type="submit" id="submit-contact" value="<?php esc_html_e( 'Submit', 'nictitate-toolkit' ); ?>">
                            </p>
                            <input type="hidden" name="action" value="kopa_send_contact">
                            <?php wp_nonce_field('kopa_send_contact_nicole_kidman', 'kopa_send_contact_nonce'); ?>
                            <div class="clear"></div>                        
                        </form>
                        <div id="response"></div>
                    </div><!--contact-box-->
                </div><!--span6-->
                
                <div class="span6">
                    <div id="contact-info">
                        <h2 class="contact-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <p><?php echo wp_kses_post( $description ); ?></p>
                        <ul class="contact-social-link clearfix">
                            <?php if ( ! empty( $facebook ) ) : ?> 
                            <li><a href="<?php echo esc_url( $facebook ); ?>" data-icon="&#xf09a;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $twitter ) ) : ?>
                            <li><a href="<?php echo esc_url( $twitter ); ?>" data-icon="&#xf099;"></a></li>
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
                        </ul><!--contact-social-link-->
                        <address>
                            <?php if( $address ) : ?>
                                <p><i class="fa fa-map-marker"></i><span><?php echo wp_kses_post( $address ); ?></span></p>
                            <?php endif; ?>
                            <?php if( $phone_number ) : ?>
                                <p><i class="fa fa-phone"></i><span><?php echo wp_kses_post( $phone_number ); ?></span></p>
                            <?php endif; ?>
                            <?php if( $email ) : ?>
                                <p><i class="fa fa-envelope"></i><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo wp_kses_post( $email ); ?></a></p>
                            <?php endif; ?>
                        </address>
                    </div><!--contact-info-->
                </div><!--span6-->
            </div><!--row-fluid-->
        </div><!--wrapper-->

        <?php
        echo wp_kses_post( $after_widget );
	}
}

register_widget( 'Nictitate_Toolkit_Widget_Contact_Form' );