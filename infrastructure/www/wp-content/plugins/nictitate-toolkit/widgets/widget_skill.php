<?php

class Nictitate_Toolkit_Widget_Skill extends Kopa_Widget {

	public function __construct() {

		$this->widget_cssclass    = 'kopa-skill-widget';
		$this->widget_description = esc_html__( 'Display a services expertise widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_skill';
		$this->widget_name        = esc_html__( '[NICTITATE] - Services Expertise', 'nictitate-toolkit' );
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
		$all_cats = get_terms('service_category');
		$categories = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach ( $all_cats as $cat ) {
			$categories[ $cat->slug ] = $cat->name;
		}

		$all_tags = get_terms('service_tag');
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
	}

	public function widget( $args, $instance ) {	

		extract( $args );
		
		extract( $instance );

		$instance['post_type'] = 'services';
        $instance['cat_name'] = 'service_category';
        $instance['tag_name'] = 'service_tag';

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$services = nictitate_toolkit_widget_posttype_build_query($instance);
		
		echo wp_kses_post( $before_widget );

        if ( ! empty ( $title ) )
            echo sprintf( '%s', $before_title . '<span data-icon="&#xf0c0;"></span>' . $title . $after_title ); 
        ?>

        <div class="kopa-skill-wrapper clearfix">
        
        <?php while ($services->have_posts()):
            $services->the_post();
            // initialize & reset for each loop
            $icon_class = '';
			$icon_class        = get_post_meta(get_the_ID(), 'icon_class', true);
			$service_expertise = get_post_meta( get_the_ID(), 'service_percentage', true );
        ?>
            <div class="kopa-skill clearfix"><p class="kopa-skill-title"><?php the_title(); ?></p>
                <div class="progress-bar green animate">
                    <span class="progress-<?php echo esc_attr( $service_expertise ); ?>" style="width: <?php echo esc_attr( $service_expertise ); ?>%">
                        <span></span>
                    </span>  
                </div>
            </div><!--kopa-skill-->
        <?php 
        endwhile; ?>

        </div> <!-- .kopa-skill-wrapper -->

        <?php wp_reset_postdata();

		echo wp_kses_post( $after_widget );
			
	}

}
register_widget( 'Nictitate_Toolkit_Widget_Skill' );