<?php
add_filter('kopa_admin_meta_box_field_icon', 'nictitate_toolkit_metabox_field_icon', 10, 5);

function nictitate_toolkit_metabox_field_icon($html, $wrap_start, $wrap_end, $value, $option_value){
    $kopa_icon = unserialize(NICTITATE_LITE_ICON);
    ob_start();
    echo wp_kses_post( $wrap_start );
    ?>
    <ul class="select-icon clearfix">
        <?php
        foreach ($kopa_icon as $key => $val) {
            echo '<li';
            if ($key == $option_value) {
                echo ' class="selected"';
            }
            echo '><span lang="' . $key . '" onclick="on_change_icon(jQuery(this));" class="icon-sample" data-icon="' . $val . '"></span></li>';
        }
        ?>
    </ul>
    <input type="hidden" autocomplete="off" name="<?php echo esc_attr($value['id']);?>" class="icon_class" value="<?php echo esc_attr($option_value); ?>">

    <?php
    echo wp_kses_post( $wrap_end );

    $html = ob_get_clean();
    return $html;
}