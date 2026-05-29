<?php

class Nictitate_Toolkit_Widget_Testimonials extends Kopa_Widget {

	public function __construct() {

		$this->widget_cssclass    = 'kopa-testimonial-widget';
		$this->widget_description = esc_html__( 'Display a testimonials widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_testimonials';
		$this->widget_name        = esc_html__( '[NICTITATE] - Testimonials', 'nictitate-toolkit' );
		$this->settings = array(
			'title' => array(
				'type' => 'text',
				'std' => '',
				'label' => esc_html__( 'Title:', 'nictitate-toolkit' ),
				)
			);
		parent::__construct();
	}

	function form($instance) {
		$this->edit_settings();
		parent::form($instance);
	}

	function update($new_instance, $old_instance) {
		$this->edit_settings();
		return parent::update($new_instance, $old_instance);
	}

	function edit_settings(){
		$all_cats = get_terms('testimonial_category');
		$categories = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach ( $all_cats as $cat ) {
			$categories[ $cat->slug ] = $cat->name;
		}

		$all_tags = get_terms('testimonial_tag');
		$tags = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach( $all_tags as $tag ) {
			$tags[ $tag->slug ] = $tag->name;
		}

		$this->settings['categories'] = array(
			'type'    => 'multiselect',
			'std'     => '',
			'label'   => esc_html__( 'Categories:', 'nictitate-toolkit' ),
			'options' => $categories,
			'size'    => '5',
		);

		$this->settings['relation']    = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Relation:', 'nictitate-toolkit' ),
			'std'     => 'OR',
			'options' => array(
				'AND' => esc_html__( 'AND', 'nictitate-toolkit' ),
				'OR'  => esc_html__( 'OR', 'nictitate-toolkit' ),
			),
		);

		$this->settings['tags'] = array(
			'type'    => 'multiselect',
			'std'     => '',
			'label'   => esc_html__( 'Tags:', 'nictitate-toolkit' ),
			'options' => $tags,
			'size'    => '5',
		);

		$this->settings['orderby'] = array(
			'type'  => 'select',
			'std'   => 'date',
			'label' => esc_html__( 'Orderby:', 'nictitate-toolkit' ),
			'options' => array(
				'date'         => esc_html__( 'Date', 'nictitate-toolkit' ),
				'random'       => esc_html__( 'Random', 'nictitate-toolkit' ),
			),
		);

		$this->settings['posts_per_page'] = array(
			'type'    => 'number',
			'std'     => '5',
			'label'   => esc_html__( 'Number of posts:', 'nictitate-toolkit' ),
			'min'     => '1',
		);

		$this->settings['style'] = array(
			'type'    => 'select',
			'std'     => 'two_columns',
			'label'   => esc_html__( 'Style:', 'nictitate-toolkit' ),
			'options' => array(
				'one_column'  => __('Slider', 'nictitate-toolkit'),
				'two_columns' => __('Carousel', 'nictitate-toolkit')
			)
		);
	}

	public function widget( $args, $instance ) {	

		extract( $args );
		
		extract( $instance );

		$instance['post_type'] = 'testimonials';
		$instance['cat_name']  = 'testimonial_category';
		$instance['tag_name']  = 'testimonial_tag';

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$testimonials = nictitate_toolkit_widget_posttype_build_query($instance);
		
		echo wp_kses_post( $before_widget );

        if ( ! empty ( $title ) )
            echo sprintf( '%s', $before_title . '<span data-icon="&#xf0c0;"></span>' . $title . $after_title );
            
        if ( $style == 'two_columns' ) :
        ?>
        
            <div class="list-carousel responsive" >
                <ul class="kopa-testimonial-carousel" data-prev-id="#<?php echo esc_attr( $this->get_field_id('prev-2') ); ?>" data-next-id="#<?php echo esc_attr( $this->get_field_id('next-2') ); ?>">

            <?php while ($testimonials->have_posts()):
                $testimonials->the_post();
				$thumbnail_id = get_post_thumbnail_id();
				$thumbnail    = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-2' );
            ?>
                <li style="width: 530px;">
                    <article class="testimonial-detail clearfix">
                        <div class="avatar">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title(); ?> <?php esc_html_e( 'avatar', 'nictitate-toolkit' ); ?>">
                            <?php endif; ?>  
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-content-inside">
                                <?php the_content(); ?>
                            </div>
                        </div><!--testimonial-content-->
                    </article><!--testimonial-detail-->
                </li>
            <?php 
            endwhile; ?>

                </ul><!--kopa-latest-work-carousel-->
                <div class="clearfix"></div>
                <div class="carousel-nav clearfix">
                    <a id="<?php echo esc_attr( $this->get_field_id('prev-2') ); ?>" class="carousel-prev" href="#">&lt;</a>
                    <a id="<?php echo esc_attr( $this->get_field_id('next-2') ); ?>" class="carousel-next" href="#">&gt;</a>
                </div>
            </div><!--list-carousel-->

        <?php else : ?>

            <div class="flexslider kopa-testimonial-slider">
                <ul class="slides">
                    <?php while ($testimonials->have_posts()):
                        $testimonials->the_post();
						$thumbnail_id = get_post_thumbnail_id();
						$thumbnail    = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-2' );
                    ?>
                        <li class="clearfix">
                            <div class="avatar">
                                <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-content-inside">
                                    <?php the_content(); ?>
                                </div>
                                <span><?php the_title(); ?></span>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul><!--slides-->
            </div><!--kopa-testimonial-slider-->

        <?php endif; // endif $style == 'two_columns' ?>

        <?php wp_reset_postdata();

		echo wp_kses_post( $after_widget );
			
	}

}
register_widget( 'Nictitate_Toolkit_Widget_Testimonials' );