<?php

class Nictitate_Toolkit_Widget_Subscribe extends Kopa_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'kopa-newsletter-widget';
		$this->widget_description = esc_html__( 'Feedburner Email Subscriptions Widget', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_subscribe';
		$this->widget_name        = esc_html__( '[NICTITATE] - Subsribe', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Newsletter', 'nictitate-toolkit' ),
				'label' => esc_html__( 'Title:', 'nictitate-toolkit' )
			),
            'feed_id'  => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__( 'Feedburner id:', 'nictitate-toolkit' )
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

		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		
        extract( $instance );

        echo wp_kses_post( $before_widget );
        if ( ! empty( $title ) )
            echo wp_kses_post( $before_title . $title . $after_title );

        if ( empty( $feed_id ) ) {
            echo wp_kses_post( $after_widget );
            return;
        }
        ?>

        <form class="newsletter-form clearfix" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr( $feed_id ); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
            
            <input type="hidden" value="<?php echo esc_attr( $feed_id ); ?>" name="uri"/>
            
            <p class="input-email clearfix">
                <input type="text" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="email" value="<?php esc_html_e('Subscribe to newsletter', 'nictitate-lite'); ?>" class="email" size="40">
                <input type="submit" value="Subscribe" class="submit">
            </p>

        </form>

        <?php 
        echo wp_kses_post( $after_widget );
	}
}

register_widget( 'Nictitate_Toolkit_Widget_Subscribe' );