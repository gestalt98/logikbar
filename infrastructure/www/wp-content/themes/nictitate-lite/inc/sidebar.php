<?php

add_filter( 'kopa_sidebar_default', 'nictitate_lite_set_sidebar_default' );

function nictitate_lite_set_sidebar_default( $options ) {	
    $options['sidebar_1']      = array(
        'name' => esc_html__( 'Sidebar 1', 'nictitate-lite'),
    );
    $options['sidebar_2']      = array(
        'name' => esc_html__( 'Sidebar 2', 'nictitate-lite'),
    );
    $options['sidebar_3']      = array(
        'name' => esc_html__( 'Sidebar 3', 'nictitate-lite'),
    );
    $options['sidebar_4']      = array(
        'name' => esc_html__( 'Sidebar 4', 'nictitate-lite'),
    );
    $options['sidebar_5']      = array(
        'name' => esc_html__( 'Sidebar 5', 'nictitate-lite'),
    );
    $options['sidebar_5']      = array(
        'name' => esc_html__( 'Sidebar 5', 'nictitate-lite'),
    );
    $options['sidebar_6']      = array(
        'name' => esc_html__( 'Sidebar 6', 'nictitate-lite'),
    );
    $options['sidebar_7']      = array(
        'name' => esc_html__( 'Sidebar 7', 'nictitate-lite'),
    );
    $options['sidebar_8']      = array(
        'name' => esc_html__( 'Sidebar 8', 'nictitate-lite'),
    );
    $options['sidebar_9']      = array(
        'name' => esc_html__( 'Sidebar 9', 'nictitate-lite'),
    );
    $options['sidebar_10']      = array(
        'name' => esc_html__( 'Sidebar 10', 'nictitate-lite'),
    );
    $options['sidebar_11']      = array(
        'name' => esc_html__( 'Sidebar 11', 'nictitate-lite'),
    );
    $options['sidebar_12']      = array(
        'name' => esc_html__( 'Sidebar 12', 'nictitate-lite'),
    );
    $options['sidebar_13']      = array(
        'name' => esc_html__( 'Sidebar 13', 'nictitate-lite'),
    );
    $options['sidebar_14']      = array(
        'name' => esc_html__( 'Sidebar 14', 'nictitate-lite'),
    );
    $options['sidebar_15']      = array(
        'name' => esc_html__( 'Sidebar 15', 'nictitate-lite'),
    );
    $options['sidebar_16']      = array(
        'name' => esc_html__( 'Sidebar 16', 'nictitate-lite'),
    );
    $options['sidebar_17']      = array(
        'name' => esc_html__( 'Sidebar 17', 'nictitate-lite'),
    );
	
	return  apply_filters( 'friday_set_sidebar_default', $options );
}

add_filter( 'kopa_sidebar_default_attributes', 'nictitate_lite_set_sidebar_default_attributes' );

function nictitate_lite_set_sidebar_default_attributes($wrap) {
	$wrap['before_widget'] = '<div id="%1$s" class="widget %2$s">';
	$wrap['after_widget']  = '</div>';
	$wrap['before_title']  = '<h2 class="widget-title"><span></span>';
	$wrap['after_title']   = '</h2>';

	return $wrap;
}

function nictitate_lite_set_sidebar($sidebar, $position){
    global $nictitate_lite_current_layout;
    
    if(!isset($nictitate_lite_current_layout) || empty($nictitate_lite_current_layout)){
        $nictitate_lite_current_layout = nictitate_lite_get_template_setting();
    }

    if(isset($nictitate_lite_current_layout['sidebars'][$position])){
        $sidebar = $nictitate_lite_current_layout['sidebars'][$position];
    }    

    return $sidebar;    
}