<?php

$header_affix = apply_filters('nictitate_lite_get_header', '');
$footer_affix = apply_filters('nictitate_lite_get_footer', '');

$is_override_default_template = apply_filters('nictitate_lite_is_override_default_template', false);

get_header($header_affix);

do_action('nictitate_lite_load_template');

if(!$is_override_default_template){
	if($nictitate_lite_current_layout = nictitate_lite_get_template_setting()){
		get_template_part("templates/{$nictitate_lite_current_layout['layout_id']}");
	}else{
		get_template_part('template/error-404');
	}
}

get_footer($footer_affix);