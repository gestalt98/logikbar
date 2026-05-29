<?php

class Nictitate_Toolkit_Widget_Sequence_Slider extends Kopa_Widget {

	public function __construct() {

		$all_cats = get_categories();
		$categories = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach ( $all_cats as $cat ) {
			$categories[ $cat->slug ] = $cat->name;
		}

		$all_tags = get_tags();
		$tags = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach( $all_tags as $tag ) {
			$tags[ $tag->slug ] = $tag->name;
		}

		$this->widget_cssclass    = 'sequence-wrapper';
		$this->widget_description = esc_html__( 'Display a posts slider.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_sequence_slider';
		$this->widget_name        = esc_html__( '[NICTITATE] - Sequence Slider', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Title:', 'nictitate-toolkit' ),
			),
			'categories' => array(
				'type'    => 'multiselect',
				'std'     => '',
				'label'   => esc_html__( 'Categories:', 'nictitate-toolkit' ),
				'options' => $categories,
				'size'    => '5',
			),
			'relation'    => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Relation:', 'nictitate-toolkit' ),
				'std'     => 'OR',
				'options' => array(
					'AND' => esc_html__( 'AND', 'nictitate-toolkit' ),
					'OR'  => esc_html__( 'OR', 'nictitate-toolkit' ),
				),
			),
			'tags' => array(
				'type'    => 'multiselect',
				'std'     => '',
				'label'   => esc_html__( 'Tags:', 'nictitate-toolkit' ),
				'options' => $tags,
				'size'    => '5',
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'date',
				'label' => esc_html__( 'Orderby:', 'nictitate-toolkit' ),
				'options' => array(
					'date'         => esc_html__( 'Date', 'nictitate-toolkit' ),
					'random'       => esc_html__( 'Random', 'nictitate-toolkit' ),
				),
			),
			'posts_per_page' => array(
				'type'    => 'number',
				'std'     => '5',
				'label'   => esc_html__( 'Number of posts:', 'nictitate-toolkit' ),
				'min'     => '1',
			)
		);
		parent::__construct();
	}

	public function widget( $args, $instance ) {	

		extract( $args );
		
		extract( $instance );

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$posts = nictitate_toolkit_widget_posttype_build_query($instance);
		
		echo wp_kses_post( $before_widget );

		if ( ! empty ( $title ) ) {
            echo wp_kses_post( $before_title . '<span data-icon="&#xf03e;"></span>' . $title . $after_title ); 
        }
        ?>

            <a class="prev" href="#"></a>
            <a class="next" href="#"></a>
            
            <div class="sequence-slider">
            
                <div id="sequence" class="kopa-sequence-slider">
                    <ul>
                    <?php if ( $posts->have_posts() ) : while ( $posts->have_posts() ) : $posts->the_post();

                        $slider_background_image = '';

                        if ( get_post_meta( get_the_ID(), 'slider_background_image', true ) ) {
                            $slider_background_image = get_post_meta( get_the_ID(), 'slider_background_image', true );
                        } 
                    ?>
                        <li id="<?php echo esc_attr( $this->get_field_id( 'sequence_slider-item' ) . '-' . get_the_ID() ); ?>" style="background: url(<?php echo esc_url( $slider_background_image ); ?>);">
                            <div class="title-2"><h2><?php the_title(); ?></h2></div>
                            <div class="subtitle-2 animate-in">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="model-2-1">
                                <div class="video-wrapper">
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'kopa-image-size-11' ); ?></a>
                                </div>
                            </div>
                        </li>
                    <?php endwhile; endif; ?>
                    </ul>
                </div><!--sequence-->
                                
            </div><!--sequence-slider-->
        
        <?php wp_reset_postdata();

		echo wp_kses_post( $after_widget );
			
	}

}
register_widget( 'Nictitate_Toolkit_Widget_Sequence_Slider' );