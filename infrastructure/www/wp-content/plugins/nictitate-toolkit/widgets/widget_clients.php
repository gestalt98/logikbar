<?php

class Nictitate_Toolkit_Widget_Clients extends Kopa_Widget {

	public function __construct() {

		$this->widget_cssclass    = 'kopa-client-widget';
		$this->widget_description = esc_html__( 'Display a clients widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_clients';
		$this->widget_name        = esc_html__( '[NICTITATE] - Clients', 'nictitate-toolkit' );
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
		$all_cats = get_terms('client_category');
		$categories = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach ( $all_cats as $cat ) {
			$categories[ $cat->slug ] = $cat->name;
		}

		$all_tags = get_terms('client_tag');
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

		$instance['post_type'] = 'clients';
		$instance['cat_name']  = 'client_category';
		$instance['tag_name']  = 'client_tag';

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$clients = nictitate_toolkit_widget_posttype_build_query($instance);
		
		echo wp_kses_post( $before_widget );

        if ( ! empty ( $title ) )
            echo sprintf('%s', $before_title . '<span data-icon="&#xf0ac;"></span>' . $title . $after_title );
        ?>

        <ul class="clearfix">

        <?php 
        $client_index = 1;
        while ( $clients->have_posts() ) : $clients->the_post(); 
			$client_url   = get_post_meta( get_the_ID(), 'client_url', true );
			$thumbnail_id = get_post_thumbnail_id();
			$thumbnail    = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-4' );
        ?>

            <li>
                <div class="auto-margin">
                    <a href="<?php echo esc_url( $client_url ); ?>"><img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt=""></a>
                </div>
            </li>

        <?php 
        if ( $client_index % 5 == 0 && $client_index != $clients->post_count )
            echo '</ul><ul class="clearfix" style="margin-top: 40px">';
        $client_index++;

        endwhile; ?>

        </ul> <!-- .clearfix -->

        <?php wp_reset_postdata();

		echo wp_kses_post( $after_widget );
			
	}

}
register_widget( 'Nictitate_Toolkit_Widget_Clients' );