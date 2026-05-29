<?php

class Nictitate_Toolkit_Widget_Categories extends Kopa_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'kopa-categories-widget';
		$this->widget_description = esc_html__( 'Categories widget.', 'nictitate-toolkit' );
		$this->widget_id          = 'kopa_widget_categories';
		$this->widget_name        = esc_html__( '[NICTITATE] - Categories', 'nictitate-toolkit' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Categories', 'nictitate-toolkit' ),
				'label' => esc_html__( 'Title', 'nictitate-toolkit' )
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
		
		echo wp_kses_post( $before_widget ); 

        $categories        = get_terms('category');
        $parent_categories = array();

        foreach ( $categories as $category ) {
            if ( $category->parent != 0 && 
                 ! in_array($category->parent, $parent_categories)) {
                array_push($parent_categories, $category->parent);
            }
        }
        ?>

        <?php if ( ! empty( $title ) ) 
            echo wp_kses_post( $before_title . $title . '<span></span>' . $after_title );
        ?>

        <div class="acc-wrapper">
            <div class="accordion-title">
                <h3><a href="#"><?php esc_html_e('All Categories', 'nictitate-toolkit'); ?></a></h3>
                <span>+</span>
            </div>
            <div class="accordion-container">
                <ul>
                    <?php foreach ($categories as $category) : ?>
                            
                        <li><a href="<?php echo get_category_link($category->term_id); ?>">
                            <?php echo "{$category->name} ({$category->count})"; ?>
                        </a></li>

                    <?php endforeach; ?>
                </ul>
            </div>
            <?php foreach ($parent_categories as $parent_category) : 
                $parent_category_object = get_category( $parent_category );
                $parent_category_name   = $parent_category_object->name;
            ?>
                <div class="accordion-title">
                  <h3><a href="#"><?php echo wp_kses_post( $parent_category_name ); ?></a></h3>
                  <span>+</span>
                </div>
                <div class="accordion-container">
                    <ul>

                        <?php foreach ($categories as $category) :
                            if ($category->parent == $parent_category) : ?>
                                
                                <li><a href="<?php echo get_category_link($category->term_id); ?>">
                                    <?php echo "{$category->name} ({$category->count})"; ?>
                                </a></li>

                        <?php endif; 
                        endforeach; ?>
                        
                    </ul>
                </div>
            <?php endforeach; ?>
        </div><!--acc-wrapper-->
        <?php
        echo wp_kses_post( $after_widget );
	}
}

register_widget( 'Nictitate_Toolkit_Widget_Categories' );