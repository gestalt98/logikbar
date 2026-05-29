<?php

class Nictitate_Toolkit_Widget_Tagline extends Kopa_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'kopa-tagline-widget clearfix';
		$this->widget_description = esc_html__( 'Display a tagline widget', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_tagline';
		$this->widget_name        = esc_html__( '[NICTITATE] - Tagline', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Title', 'nictitate-toolkit' )
			),
            'description'  => array(
                'type'  => 'textarea',
                'std'   => '',
                'label' => esc_html__( 'Description:', 'nictitate-toolkit' ),
                'rows' => 5,
            ),
            'button_text'  => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__( 'Button Text:', 'nictitate-toolkit' )
            ),
            'button_link'  => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__( 'Button Link:', 'nictitate-toolkit' )
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

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		
        extract( $instance );

        echo wp_kses_post( $before_widget );
        ?>

        <div class="kopa-tagline-description">
            <h4><?php echo wp_kses_post( $title ); ?></h4>
            <p><?php echo wp_kses_post( $description ); ?></p>
        </div>
        <a href="<?php echo esc_url( $button_link ); ?>" class="kopa-button blue-button"><?php echo wp_kses_post( $button_text ); ?></a>

        <?php 
        echo wp_kses_post( $after_widget );
	}
}

register_widget( 'Nictitate_Toolkit_Widget_Tagline' );