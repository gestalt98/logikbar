<?php

class Nictitate_Toolkit_Widget_Portfolios extends Kopa_Widget {

	public function __construct() {

		$this->widget_cssclass    = 'kopa-portfolio-widget';
		$this->widget_description = esc_html__( 'Display a portfolios widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_portfolios';
		$this->widget_name        = esc_html__( '[NICTITATE] - Portfolios', 'nictitate-toolkit' );
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
		$all_cats = get_terms('portfolio_project');
		$categories = array('' => esc_html__('-- None --', 'nictitate-toolkit'));
		foreach ( $all_cats as $cat ) {
			$categories[ $cat->slug ] = $cat->name;
		}

		$all_tags = get_terms('portfolio_tag');
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

		$this->settings['title_position'] = array(
			'type'    => 'number',
			'std'     => '5',
			'label'   => esc_html__( 'Title position:', 'nictitate-toolkit' ),
			'min'     => '1',
			'desc'   => esc_html__( 'Ex: Enter 5 if you want title will be displayed as the 5th item in the portfolio list items.', 'nictitate-toolkit' ),
		);
	}

	public function widget( $args, $instance ) {	

		extract( $args );
		
		extract( $instance );

		$instance['post_type'] = 'portfolio';
		$instance['cat_name']  = 'portfolio_project';
		$instance['tag_name']  = 'portfolio_tag';

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$portfolios = nictitate_toolkit_widget_posttype_build_query($instance);
		
		echo wp_kses_post( $before_widget );

        $title_position = $instance['title_position'];

        ?>
        <div class="wrapper">
            <ul id="container" class="clearfix da-thumbs">

        <?php 
        $portfolio_index = 1;
        while ( $portfolios->have_posts() ) : $portfolios->the_post(); 
            $portfolio_thumbnail_size = get_post_meta( get_the_ID(), 'portfolio_thumbnail_size', true );
            $item_image_size = '';
            $item_class = '';

            if ($portfolio_thumbnail_size == '118x118') {
                $item_image_size = 'kopa-image-size-5';
            }
            elseif ($portfolio_thumbnail_size == '118x239') {
                $item_image_size = 'kopa-image-size-6';
                $item_class = 'height2';
            }
            elseif ($portfolio_thumbnail_size == '239x118') {
                $item_image_size = 'kopa-image-size-7';
                $item_class = 'width2';
            }
            else {
                $item_image_size = 'kopa-image-size-8';
                $item_class = 'width2 height2';
            }

			$thumbnail_id   = get_post_thumbnail_id();
			$thumbnail      = wp_get_attachment_image_src( $thumbnail_id, $item_image_size );
			$full_thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'full' );

            if ($portfolio_index == $title_position) {
                echo '<li class="element width2 isotope-item">
                        <h2>'.wp_kses_post( $title ).'</h2>
                    </li>';
            }

            if ( has_post_thumbnail() ) :
        ?>
            <li class="element <?php echo esc_attr( $item_class ); ?>">
              <div class="da-thumbs-hover">
                <img src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php the_title(); ?>">
                <p>
                    <a class="link-gallery" href="<?php echo esc_url( $full_thumbnail[0] ); ?>" rel="prettyPhoto[<?php echo esc_attr( $this->get_field_id( 'gallery' ) ); ?>]"><?php the_title(); ?></a>
                    <a class="link-detail" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </p>
              </div>
            </li>
        <?php 
            endif;

            $portfolio_index++;
        endwhile; 

        // title in the last position of portfolio list items
        if ( $title_position > $portfolios->post_count ) {
            echo '<li class="element width2 isotope-item">
                    <h2>'.$title.'</h2>
                </li>';
        }
        ?>

            </ul> <!-- #container -->
        </div><!--wrapper-->

        <?php wp_reset_postdata();

		echo wp_kses_post( $after_widget );
			
	}

}
register_widget( 'Nictitate_Toolkit_Widget_Portfolios' );