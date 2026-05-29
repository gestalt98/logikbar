<?php
/**
 * Abstract Widget Class
 *
 * @author 		Kopatheme
 * @category 	Widgets
 * @package 	KopaFramework/Abstracts
 * @since       1.0.0
 * @extends 	WP_Widget
 * @folked      WC_Widget from Woocommerce
 */
abstract class Kopa_Widget extends WP_Widget {

	/**
	 * @access public
	 * @var string widget properties
	 */
	public $widget_cssclass;
	public $widget_description;
	public $widget_id;
	public $widget_name;
	public $widget_width;
	public $widget_height;

	/**
	 * @access public
	 * @var array form field arguments
	 */
	public $settings;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => $this->widget_cssclass,
			'description' => $this->widget_description
		);

		$control_ops = array(
			'width'  => $this->widget_width,
			'height' => $this->widget_height,
		);

		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops, $control_ops );

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	/**
	 * get_cached_widget function.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function get_cached_widget( $args ) {
		$cache = wp_cache_get( $this->widget_id, 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return true;
		}

		return false;
	}

	/**
	 * Cache the widget
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function cache_widget( $args, $content ) {
		$cache[ $args['widget_id'] ] = $content;

		wp_cache_set( $this->widget_id, $cache, 'widget' );
	}

	/**
	 * Flush the cache
	 * @return [type]
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_id, 'widget' );
	}

	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		if ( ! $this->settings )
			return $instance;

		foreach ( $this->settings as $key => $setting ) {
			$output = null;
			if ( isset( $new_instance[ $key ] ) ) {
				if ( is_array( $new_instance[ $key ] ) ) {
					$output = array_map( 'sanitize_text_field', (array) $new_instance[ $key ] );
				} elseif ( 'textarea' === $setting['type'] ) {
					// $instance[ $key ] = wp_kses_post( trim( $new_instance[ $key ] ) );

					/**
					 * @see update() of WP_Widget_Text
					 */
					if ( current_user_can('unfiltered_html') ) {
						$output =  $new_instance[ $key ];
					} else {
						$output = stripslashes( wp_filter_post_kses( addslashes($new_instance[ $key ]) ) ); // wp_filter_post_kses() expects slashed
					}
				} elseif ( 'datetime' === $setting['type'] ) {
            $output = strtotime( $new_instance[ $key ] );
        }
        else {
					$output = sanitize_text_field( $new_instance[ $key ] );
				}
			} elseif ( 'checkbox' === $setting['type'] ) {
				$output = 0;
			} elseif ( 'multiselect' === $setting['type'] ) {
				$output = array();
			}

			$instance[ $key ] = $output;
		}


		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @param array $instance
	 * @return void
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function form( $instance ) {

		if ( ! $this->settings )
			return;

		$wrap_start = apply_filters('kopa_set_widget_form_wrap_start','<div class="kopa-widget-block">');
		$wrap_end = apply_filters('kopa_set_widget_form_wrap_end', '</div>');

		foreach ( $this->settings as $key => $setting ) {

			// sanitize setting arguments
			$setting = wp_parse_args( $setting, 
				apply_filters('kopa_widget_form_parse_args', array(
				// common
				'type'            => '',
				'std'             => '',
				'label'           => '',
				'desc'            => '',
				// number
				'step'            => '',
				'min'             => '',
				'max'             => '',
				// select
				'options'         => '',
				'size'            => '',
				// textarea
				'rows'            => '',
				// upload
				'mimes'           => '',
				'css'             => '',
				// datetime
				'format'          => 'Y/m/d H:i',
				'datepicker'      => true,
				'timepicker'      => true,
				// taxonomy
				'taxonomy'        => '',
				// singular
				'post_type'       => ''				
				)));

			$value   = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];

			$setting['id'] =  $this->get_field_id( $key );
			$setting['name'] =  $this->get_field_name( $key );

			ob_start();

			$setting['desc'] =  !empty($setting['desc']) ? '<small class="kopa-widget-desc">'. $setting['desc'] .'</small>' : false;

			switch ( $setting['type'] ) {
				case "text" :
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
						<?php echo wp_kses_post($setting['desc']); ?>
					</p>
					<?php
					break;
				case "number" :
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number" step="<?php echo esc_attr( $setting['step'] ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
						<?php echo wp_kses_post($setting['desc']); ?>
					</p>
					<?php
					break;
				case "select" :
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>">
							<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo wp_kses_post($setting['desc']); ?>
					</p>
					<?php
					break;
				case "multiselect" :
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<select class="widefat" size="<?php echo esc_attr( $setting['size'] ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>[]" multiple="multiple">
							<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( in_array( $option_key, (array) $value ), true ); ?>><?php echo esc_html( $option_value ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo wp_kses_post($setting['desc']); ?>
					</p>
					<?php
					break;
				case "checkbox" :
					?>
					<p>
						<input id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<?php echo wp_kses_post($setting['desc']); ?>
					</p>
					<?php
					break;
				case "textarea" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" rows="<?php echo esc_attr( $setting['rows'] ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
						<?php echo wp_kses_post($setting['desc']); ?>
					</p>
					<?php
					break;
				case "upload" :
					if ( empty( $setting['css'] ) ) {
						$setting['css'] = 'width: 88%';
					}
					if ( ! function_exists( 'wp_enqueue_media' ) ) {
						$setting['css'] = '';
					}
					?>
					<div class="kopa_section">
						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
							<br>
							<input class="widefat kopa_upload" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" style="<?php echo esc_attr( $setting['css'] ); ?>" data-type="<?php echo esc_attr( $setting['mimes'] ); ?>" />
							<?php if ( function_exists( 'wp_enqueue_media' ) ) { ?>
								<?php if ( $value ) { ?>
									<a href="#" class="button kopa_remove_file">&ndash;</a>
								<?php } else { ?>
									<a href="#" class="button kopa_upload_button">+</a>
								<?php } ?>
							<?php } else { ?>
								<small><?php esc_html_e( 'Upgrade your version of WordPress for full media support.', 'kopa-framework' ); ?></small>
							<?php } // end check media support ?>
						</p>
						<?php if ( function_exists( 'wp_enqueue_media' ) ) { ?>
						<p class="kopa_screenshot">
							<?php $image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
							if ( $image ) { ?>
								<img src="<?php echo esc_attr( $value ); ?>" style="max-width: 100%">
								<br>
								<a href="#" class="button kopa_remove_image">&ndash;</a>
							<?php } ?>
						</p>
						<?php } // end check media support ?>

						<?php echo wp_kses_post($setting['desc']); ?>
					</div>
					<?php
					break;

                case "datetime":
                    $date_value_time = '';
                    if ( isset($setting['format']) && ! empty($value) ) {
                        $date_value_time = date($setting['format'],$value);
                    }
                	?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
                        <input
                            class="widefat kopa-framework-datetime"
                            type="text"
                            name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
                            id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
                            value="<?php echo esc_attr($date_value_time);?>"
                            data-timepicker="<?php echo esc_attr($setting['timepicker']); ?>"
                            data-datepicker="<?php echo esc_attr($setting['datepicker']); ?>"
                            data-format="<?php echo wp_kses_post($setting['format']); ?>"
                            autocomplete="off">
                        <?php echo wp_kses_post($setting['desc']); ?>
                    </p>
                    <?php break;

                case 'gallery': ?>
                    <div class="kopa-framework-gallery-box">
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label><br/>
                        <input
                            class="widefat kopa-framework-gallery"
                            type="text"
                            name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
                            id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
                            value="<?php echo esc_attr($value);?>"
                            autocomplete="off">
                        <?php echo wp_kses_post($setting['desc']); ?>
                        <a href="#" class="kopa-framework-gallery-config button button-secondary">
                            <?php echo esc_html__('Config', 'kopa-framework'); ?>
                        </a>
                    </div>
                    <?php 
                    break;

                case 'icon': ?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
                        <div class="kopa-icon-picker-wrap clearfix">	                        
	                        <input type="hidden"
	                               name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
	                               id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
	                               value="<?php echo esc_attr($value);?>"
	                               autocomplete="off"
	                               class="widefat kopa-icon-picker-value"/>
	                        <span class="kopa-icon-picker-preview"><i class="<?php echo esc_attr($value);?>"></i></span>
	                        <a class="kopa-icon-picker dashicons dashicons-arrow-down" href="#"></a>
	                      </div>
                    </p>
                    <?php 
                    break;
                case 'taxonomy':
                	$setting['options'] = array( '' => esc_html__( '-- Select --', 'kopa-framework' ) );

                	if ( isset( $setting['taxonomy'] ) && !empty( $setting['taxonomy'] ) ) {
						$taxonomy = esc_html( $setting['taxonomy'] );
						$terms    = get_terms( $taxonomy );
						if( $terms ){
							foreach( $terms as $term ){
								$setting['options'][$term->term_id] = $term->name;
							}
						}
					}									
	                
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>">
							<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo wp_kses_post($setting['desc']); ?>
					</p>					
					<?php

                	break;

                case 'caption':
                	?>
                	<div>
                		<p class="kopa_ui_caption"><?php echo esc_html( $setting['label'] ); ?></p>
                		<?php echo wp_kses_post($setting['desc']); ?>
                	</div>
                	<?php
                	break;
                case 'singular':
                	$post_type = ( isset( $setting['post_type'] ) && !empty( $setting['post_type'] ) ) ? esc_html( $setting['post_type'] ) : 'page';					          
	                ?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<?php
						if( 'page' === $post_type ){
							wp_dropdown_pages( array(
								'name'              => esc_attr( $this->get_field_name( $key ) ),
								'id'                => esc_attr( $this->get_field_id( $key ) ),
								'class'             => 'widefat',
								'show_option_none'  => esc_html__( '-- Select --', 'kopa-framework' ),						
								'option_none_value' => '',
								'selected'          => $value
							) );
						}else{	
							$setting['options'] = array( '' => esc_html__( '-- Select --', 'kopa-framework' ) );												
							$query = array(
								'post_type'           => $post_type,
								'posts_per_page'      => -1,
								'ignore_sticky_posts' => true
							);						

							$records = new WP_Query( $query );
							if ( $records->have_posts() ) {
								while ( $records->have_posts() ) {
									$records->the_post();
									$_id                      = get_the_ID();							
									$setting['options'][$_id] = get_the_title();			
								}
							}						
							unset( $records );
							wp_reset_postdata();				
							?>
							<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>">
								<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
									<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
								<?php endforeach; ?>
							</select>							
						<?php } ?>
						
						<?php echo wp_kses_post($setting['desc']); ?>

						<?php if( $value ): ?>
							<p>
								<label><?php esc_html_e( 'Activated Page:', 'kopa-framework' ); ?> <i><?php esc_html_e( 'click to edit on new tab.', 'kopa-framework' ); ?></i></label><br/>
								<a href="<?php echo esc_url( get_edit_post_link( $value ) ); ?>" target="_blank"><?php echo esc_html( get_the_title( $value ) ); ?></a>
							</p>
						<?php endif; ?>
					</p>	                
	                <?php
	                break;
			}

			$html = ob_get_clean();

			echo apply_filters( sprintf('kopa_widget_form_field_%s', $setting['type']), $html, $wrap_start, $wrap_end, $setting, $value, $key );
		}
	}

	/**
	 * get default value
	 *	 	 
	 * @return array $default
	 *
	 * @since 1.0.8
	 * @access public
	 */	
	function get_default_instance(){
		$default = array();
		
		if($this->settings){
			foreach ($this->settings  as $key => $value) {
				$default[$key] = $value['std'];
			}
		}
		return $default;
	}
}