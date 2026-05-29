<?php

class Nictitate_Toolkit_Widget_Staffs extends Kopa_Widget {

	public function __construct() {

		$this->widget_cssclass    = 'kopa-our-team-widget';
		$this->widget_description = esc_html__( 'Display a staffs widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_staffs';
		$this->widget_name        = esc_html__( '[NICTITATE] - Staffs', 'nictitate-toolkit' );
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
		$all_cats = get_terms('staff_category');
		$categories = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach ( $all_cats as $cat ) {
			$categories[ $cat->slug ] = $cat->name;
		}

		$all_tags = get_terms('staff_tag');
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

		$instance['post_type'] = 'staffs';
		$instance['cat_name']  = 'staff_category';
		$instance['tag_name']  = 'staff_tag';

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$staffs = nictitate_toolkit_widget_posttype_build_query($instance);
		
		echo wp_kses_post( $before_widget );

        if ( ! empty( $title ) )
            echo sprintf( '%s', $before_title . '<span data-icon="&#xf0c0;"></span>' . $title . $after_title );
        ?>

        <ul class="clearfix">
            <?php 
            $staff_index = 1;

            while ( $staffs->have_posts() ) : $staffs->the_post(); 
				$thumbnail_id   = get_post_thumbnail_id();
				$thumbnail      = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-3' );
				$staff_position = get_post_meta( get_the_ID(), 'position', true );
				$staff_facebook = get_post_meta( get_the_ID(), 'facebook', true );
				$staff_twitter  = get_post_meta( get_the_ID(), 'twitter', true );
				$staff_gplus    = get_post_meta( get_the_ID(), 'gplus', true );
            ?>
            <li>
                <article class="entry-item clearfix">
                    <div class="entry-thumb">
                        <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title(); ?>"></a>
                    </div>
                    <div class="entry-content">
                        <header>
                            <h6 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span></span></h6>
                            <span><?php echo wp_kses_post( $staff_position ); ?></span>
                        </header>
                        <?php the_excerpt(); ?>
                        <ul class="our-team-social-link clearfix">
                            <?php if ( ! empty( $staff_facebook ) ) : ?>
                            <li><a href="<?php echo esc_url( $staff_facebook ); ?>" data-icon="&#xf09a;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $staff_twitter ) ) : ?>
                            <li><a href="<?php echo esc_url( $staff_twitter ); ?>" data-icon="&#xf099;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $staff_gplus ) ) : ?>
                            <li><a href="<?php echo esc_url( $staff_gplus ); ?>" data-icon="&#xf0d5;"></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </article>
            </li>
            <?php 
            if ( $staff_index % 4 == 0 && $staff_index != $staffs->post_count )
                echo '</ul><ul class="clearfix mt-20">';
            
            $staff_index++;

            endwhile; ?>
        </ul>

        <?php wp_reset_postdata();

		echo wp_kses_post( $after_widget );
			
	}

}
register_widget( 'Nictitate_Toolkit_Widget_Staffs' );