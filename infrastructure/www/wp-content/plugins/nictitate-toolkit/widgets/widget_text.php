<?php 
add_action('widgets_init', 'nictitate_toolkit_widgets_init');

function nictitate_toolkit_widgets_init() {
    register_widget('Nictitate_Toolkit_Widget_Text');
}
class Nictitate_Toolkit_Widget_Text extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_text', 'description' => esc_html__('Arbitrary text, HTML or shortcodes', 'nictitate-toolkit'));
        $control_ops = array('width' => 600, 'height' => 400);
        parent::__construct('kopa_widget_text', esc_html__('[NICTITATE] - Text', 'nictitate-toolkit'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text  = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance);

        echo wp_kses_post( $before_widget );
        if (!empty($title)) {
            echo sprintf( '%s', $before_title . '<span data-icon="&#xf040;"></span>' . $title . $after_title );
        }
        ?>
        <?php echo!empty($instance['filter']) ? wpautop($text) : $text; ?>
        <?php
        echo wp_kses_post( $after_widget );
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if (current_user_can('unfiltered_html'))
            $instance['text'] = $new_instance['text'];
        else
            $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text'])));
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'text' => ''));
        $title    = strip_tags($instance['title']);
        $text     = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:', 'nictitate-toolkit'); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>        
        <ul class="kopa_shortcode_icons">
            <?php
            $shortcodes = array(
                'one_half'     => 'One Half Column',
                'one_third'    => 'One Thirtd Column',
                'two_third'    => 'Two Third Column',
                'one_fourth'   => 'One Fourth Column',
                'three_fourth' => 'Three Fourth Column',
                'dropcaps'     => 'Add Dropcaps Text',
                'button'       => 'Add A Button',
                'alert'        => 'Add A Alert Box',
                'tabs'         => 'Add A Tabs Content',
                'accordions'   => 'Add A Accordions Content',
                'toggle'       => 'Add A Toggle Content',
                'contact_form' => 'Add A Contact Form',
                'youtube'      => 'Add A Yoube Video Box',
                'vimeo'        => 'Add A Vimeo Video Box'
            );
            foreach ($shortcodes as $rel => $title):
                ?>
                <li>
                    <a onclick="return kopa_shortcode_icon_click('<?php echo esc_attr( $rel ); ?>', jQuery('#<?php echo esc_attr( $this->get_field_id('text') ); ?>'));" href="#" class="<?php echo "kopa-icon-{$rel}"; ?>" rel="<?php echo esc_attr( $rel ); ?>" title="<?php echo esc_attr( $title ); ?>"></a>
                </li>
            <?php endforeach; ?>
        </ul>        
        <textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id('text') ); ?>" name="<?php echo esc_attr( $this->get_field_name('text') ); ?>"><?php echo esc_attr( $text ); ?></textarea>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id('filter') ); ?>" name="<?php echo esc_attr( $this->get_field_name('filter') ); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr( $this->get_field_id('filter') ); ?>"><?php esc_html_e('Automatically add paragraphs', 'nictitate-toolkit'); ?></label>
        </p>
        <?php
    }

}