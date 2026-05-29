<?php

class Nictitate_Toolkit_Widget_About extends Kopa_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'kopa-about-widget clearfix';
		$this->widget_description = esc_html__( 'Display a gallery and description.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_about';
		$this->widget_name        = esc_html__( '[NICTITATE] - About', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Title', 'nictitate-toolkit' )
			),
            'image_ids'  => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__( 'Image IDs', 'nictitate-toolkit' ),
                'desc'  => esc_html__( 'Image Attachment IDs, separated by commas.', 'nictitate-toolkit' )
            ),
            'description'  => array(
                'type'  => 'textarea',
                'std'   => '',
                'label' => esc_html__( 'Description', 'nictitate-toolkit' )
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
		
		$image_ids = $instance['image_ids'];
        $image_ids = str_replace( ' ', '', $image_ids );
        $image_ids = explode( ',', $image_ids );

        $description = $instance['description'];

        echo wp_kses_post( $before_widget );

        if ( ! empty( $title ) )
            echo sprintf('%s', $before_title . '<span data-icon="&#xf040;"></span>' . $title . $after_title );
        ?>

            <?php if ( ! empty( $image_ids ) ) : ?>
                <div class="entry-thumb">
                    <div class="flexslider about-slider">
                        <ul class="slides">
                            <?php foreach ( $image_ids as $id ) {
                                if ( wp_attachment_is_image( $id ) ) {
                                    echo '<li>' . wp_get_attachment_image( $id, 'kopa-image-size-11' ) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <div class="entry-content">                             
                <p><?php echo wp_kses_post( $description ); ?></p>
            </div>

        <?php wp_reset_postdata();
        echo wp_kses_post( $after_widget );
	}
}

register_widget( 'Nictitate_Toolkit_Widget_About' );