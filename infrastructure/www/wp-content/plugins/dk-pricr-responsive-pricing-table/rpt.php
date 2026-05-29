<?php
/**
 * Plugin Name: Responsive Pricing Table
 * Plugin URI: http://wpdarko.com/items/responsive-pricing-table-pro/
 * Description: A responsive, easy and elegant way to present your offer to your visitors. Just create a new pricing table (custom type) and copy-paste the shortcode into your posts/pages. Find help and information on our <a href="http://wpdarko.com/support/">support site</a>. This free version is NOT limited and does not contain any ad. Check out the <a href='http://wpdarko.com/items/responsive-pricing-table-pro/'>PRO version</a> for more great features.
 * Version: 4.3
 * Author: WP Darko
 * Author URI: http://wpdarko.com
 * Text Domain: dk-pricr-responsive-pricing-table
 * Domain Path: /lang/
 * License: GPL2
 */


// Loading text domain
add_action( 'plugins_loaded', 'rpt_load_plugin_textdomain' );
function rpt_load_plugin_textdomain() {
  load_plugin_textdomain( 'dk-pricr-responsive-pricing-table', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
}


// Check for the PRO version
add_action( 'admin_init', 'rpt_free_pro_check' );
function rpt_free_pro_check() {
    if (is_plugin_active('responsive-pricing-table-pro/rpt_pro.php')) {

        function my_admin_notice(){
        echo '<div class="updated">
                <p><strong>PRO</strong> version is activated.</p>
              </div>';
        }
        add_action('admin_notices', 'my_admin_notice');

        deactivate_plugins(__FILE__);
    }
}


/* Enqueue styles */
add_action( 'wp_enqueue_scripts', 'add_rpt_scripts', 99 );
function add_rpt_scripts() {
	wp_enqueue_style( 'rpt', plugins_url('css/rpt_style.min.css', __FILE__));
}


/* Enqueue admin styles */
add_action( 'admin_enqueue_scripts', 'add_admin_rpt_style' );
function add_admin_rpt_style() {
    global $post_type;
    if( 'rpt_pricing_table' == $post_type ) {
	     wp_enqueue_style( 'rpt', plugins_url('css/admin_de_style.min.css', __FILE__));
       wp_enqueue_script( 'rpt', plugins_url('js/rpt_admin.min.js', __FILE__), array( 'jquery' ));
    }
}


// Register Pricing Table post type
add_action( 'init', 'register_rpt_type' );
function register_rpt_type() {
	$labels = array(
		'name'               => __( 'Pricing Tables', 'dk-pricr-responsive-pricing-table' ),
		'singular_name'      => __( 'Pricing Table', 'dk-pricr-responsive-pricing-table' ),
		'menu_name'          => __( 'Pricing Tables', 'dk-pricr-responsive-pricing-table' ),
		'name_admin_bar'     => __( 'Pricing Table', 'dk-pricr-responsive-pricing-table' ),
		'add_new'            => __( 'Add New', 'dk-pricr-responsive-pricing-table' ),
		'add_new_item'       => __( 'Add New Pricing Table', 'dk-pricr-responsive-pricing-table' ),
		'new_item'           => __( 'New Pricing Table', 'dk-pricr-responsive-pricing-table' ),
		'edit_item'          => __( 'Edit Pricing Table', 'dk-pricr-responsive-pricing-table' ),
		'view_item'          => __( 'View Pricing Table', 'dk-pricr-responsive-pricing-table' ),
		'all_items'          => __( 'All Pricing Tables', 'dk-pricr-responsive-pricing-table' ),
		'search_items'       => __( 'Search Pricing Tables', 'dk-pricr-responsive-pricing-table' ),
		'not_found'          => __( 'No Pricing Tables found.', 'dk-pricr-responsive-pricing-table' ),
		'not_found_in_trash' => __( 'No Pricing Tables found in Trash.', 'dk-pricr-responsive-pricing-table' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
        'show_in_admin_bar'  => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title' ),
        'menu_icon'          => 'dashicons-plus'
	);
	register_post_type( 'rpt_pricing_table', $args );
}


// Customize update messages
add_filter( 'post_updated_messages', 'rpt_updated_messages' );
function rpt_updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );
	$messages['rpt_pricing_table'] = array(
		1  => __( 'Pricing Table updated.', 'dk-pricr-responsive-pricing-table' ),
		4  => __( 'Pricing Table updated.', 'dk-pricr-responsive-pricing-table' ),
		6  => __( 'Pricing Table published.', 'dk-pricr-responsive-pricing-table' ),
		7  => __( 'Pricing Table saved.', 'dk-pricr-responsive-pricing-table' ),
		10 => __( 'Pricing Table draft updated.', 'dk-pricr-responsive-pricing-table' )
	);

	if ( $post_type_object->publicly_queryable ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( '', '', '' );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( '', '', '' );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}
	return $messages;
}


// Add the amazing metabox class (CMB2)
if ( file_exists( dirname( __FILE__ ) . '/inc/cmb2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/inc/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/inc/CMB2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/inc/CMB2/init.php';
}


// Registering Pricing Table metaboxes
add_action( 'cmb2_init', 'rpt_register_group_metabox' );
require_once('inc/rpt-metaboxes.php');


// Shortcode column
add_action( 'manage_rpt_pricing_table_posts_custom_column' , 'rpt_custom_columns', 10, 2 );
add_filter('manage_rpt_pricing_table_posts_columns' , 'add_rpt_pricing_table_columns');
function rpt_custom_columns( $column, $post_id ) {
    switch ( $column ) {
	case 'dk_shortcode' :
		global $post;
		$slug = '' ;
		$slug = $post->post_name;
        $shortcode = '<span style="display:inline-block;border:solid 2px lightgray; background:white; padding:0 8px; font-size:13px; line-height:25px; vertical-align:middle;">[rpt name="'.$slug.'"]</span>';
	    echo $shortcode;
	    break;
    }
}
function add_rpt_pricing_table_columns($columns) { return array_merge($columns, array('dk_shortcode' => 'Shortcode'));}


// Display Shortcode
add_shortcode("rpt", "rpt_sc");
function rpt_sc($atts) {
	extract(shortcode_atts(array(
		"name" => ''
	), $atts));
	$output2 = '';

global $post;

$args = array('post_type' => 'rpt_pricing_table', 'name' => $name);
$custom_posts = get_posts($args);
foreach($custom_posts as $post) : setup_postdata($post);

	$entries = get_post_meta( $post->ID, '_rpt_plan_group', true );

	$nb_entries = count($entries);;

    // Forcing original fonts?
    $original_font = get_post_meta( $post->ID, '_rpt_original_font', true );
    if ($original_font == true){
        $ori_f = 'rpt_plan_ori';
    } else {
        $ori_f = '';
    }

	// Get font sizes
	$title_fontsize = get_post_meta( $post->ID, '_rpt_title_fontsize', true );
	if ($title_fontsize == 'small') {
		$title_fs_class = ' rpt_sm_title';
	} else if ($title_fontsize == 'tiny') {
		$title_fs_class = ' rpt_xsm_title';
	} else {
		$title_fs_class = '';
	}

	$subtitle_fontsize = get_post_meta( $post->ID, '_rpt_subtitle_fontsize', true );
	if ($subtitle_fontsize == 'small') {
		$subtitle_fs_class = ' rpt_sm_subtitle';
	} else if ($subtitle_fontsize == 'tiny') {
		$subtitle_fs_class = ' rpt_xsm_subtitle';
	} else {
		$subtitle_fs_class = '';
	}

	$description_fontsize = get_post_meta( $post->ID, '_rpt_description_fontsize', true );
	if ($description_fontsize == 'small') {
		$description_fs_class = ' rpt_sm_description';
	} else {
		$description_fs_class = '';
	}

	$price_fontsize = get_post_meta( $post->ID, '_rpt_price_fontsize', true );
	if ($price_fontsize == 'small') {
		$price_fs_class = ' rpt_sm_price';
	} else if ($price_fontsize == 'tiny') {
		$price_fs_class = ' rpt_xsm_price';
    } else if ($price_fontsize == 'supertiny') {
		$price_fs_class = ' rpt_xxsm_price';
	} else {
		$price_fs_class = '';
	}

	$recurrence_fontsize = get_post_meta( $post->ID, '_rpt_recurrence_fontsize', true );
	if ($recurrence_fontsize == 'small') {
		$recurrence_fs_class = ' rpt_sm_recurrence';
	} else {
		$recurrence_fs_class = '';
	}

	$features_fontsize = get_post_meta( $post->ID, '_rpt_features_fontsize', true );
	if ($features_fontsize == 'small') {
		$features_fs_class = ' rpt_sm_features';
	} else {
		$features_fs_class = '';
	}

	$button_fontsize = get_post_meta( $post->ID, '_rpt_button_fontsize', true );
	if ($button_fontsize == 'small') {
		$button_fs_class = ' rpt_sm_button';
	} else {
		$button_fs_class = '';
	}

	// Opening rpt_pricr container
	$output2 .= '<div id="rpt_pricr" class="rpt_plans rpt_'.$nb_entries .'_plans rpt_style_basic">';

	// Opening rpt_pricr inner
	$output2 .= '<div class="'. $title_fs_class . $subtitle_fs_class . $description_fs_class . $price_fs_class . $recurrence_fs_class . $features_fs_class. $button_fs_class .'">';

    if (is_array($entries) || is_object($entries))
    	foreach ($entries as $key => $plans) {

    	if (!empty($plans['_rpt_recommended'])){
    		$is_reco = $plans['_rpt_recommended'];

    		//Opening plan
    		if ($is_reco == true ){
    		    $reco = '<img class="rpt_recommended" src="' . plugins_url('img/rpt_recommended.png', __FILE__) . '"/>';
    		    $reco_class = 'rpt_recommended_plan';
    		} else if ($is_reco == false ) {
    		    $reco = '';
    		    $reco_class = '';
    		}
    	} else {
    		$reco = '';
    		$reco_class = '';
    	}

      if (empty($plans['_rpt_custom_classes'])){
        $plans['_rpt_custom_classes'] = '';
      }

    	$output2 .= '<div class="rpt_plan  '.$ori_f.' rpt_plan_' . $key . ' ' . $reco_class . ' ' . $plans['_rpt_custom_classes'] . '">';

    		// Title
    		if (!empty($plans['_rpt_title'])){
    			$output2 .= '<div class="rpt_title rpt_title_' . $key . '">';

    			if (!empty($plans['_rpt_icon'])){
    				$output2 .= '<img height=30px width=30px src="' . $plans['_rpt_icon'] . '" class="rpt_icon rpt_icon_' . $key . '"/> ';
    			}

    			$output2 .= $plans['_rpt_title'];
    			$output2 .= $reco . '</div>';
    		}

    		// Head
    		$output2 .= '<div class="rpt_head rpt_head_' . $key . '">';

    			// Recurrence
    			if (!empty($plans['_rpt_recurrence'])){
    			    	$output2 .= '<div class="rpt_recurrence rpt_recurrence_' . $key . '">' . $plans['_rpt_recurrence'] . '</div>';
    			}

    			// Price
    			if (!empty($plans['_rpt_price'])){

    			    $output2 .= '<div class="rpt_price rpt_price_' . $key . '">';

    			    if (!empty($plans['_rpt_free'])){
    			    	if ($plans['_rpt_free'] == true ){
    			    		$output2 .= $plans['_rpt_price'];
    			    	} else {
    				    	$output2 .= '<span class="rpt_currency"></span>' . $plans['_rpt_price'];
    			    	}
    			    } else {

    			    	$currency = get_post_meta( $post->ID, '_rpt_currency', true );

    			    	if (!empty($currency)){
    			    		$output2 .= '<span class="rpt_currency">';
    			    		$output2 .= $currency;
    						$output2 .= '</span>';
    					}

    			    	$output2 .= $plans['_rpt_price'];

    			    }

    			    $output2 .= '</div>';
    			}

    			// Subtitle
    			if (!empty($plans['_rpt_subtitle'])){
    			    	$output2 .= '<div style="color:' . $plans['_rpt_color'] . ';" class="rpt_subtitle rpt_subtitle_' . $key . '">' . $plans['_rpt_subtitle'] . '</div>';
    			    }

    			// Description
    			if (!empty($plans['_rpt_description'])){
    			    $output2 .= '<div class="rpt_description rpt_description_' . $key . '">' . $plans['_rpt_description'] . '</div>';
    			}

    		// Closing plan head
    		$output2 .= '</div>';


    		if (!empty($plans['_rpt_features'])){


                $output2 .= '<div class="rpt_features rpt_features_' . $key . '">';


    			$string = $plans['_rpt_features'];
    			$stringAr = explode("\n", $string);
    			$stringAr = array_filter($stringAr, 'trim');

    			$features = '';

    			foreach ($stringAr as $feature) {
    				$features[] .= strip_tags($feature,'<strong></strong><br><br/></br><img><a>');
    			}

    			foreach ($features as $small_key => $feature){
    				if (!empty($feature)){

    					$check = substr($feature, 0, 2);
    					if ($check == '-n') {
    						$feature = substr($feature, 2);
    						$check_color = '#bbbbbb';
    					} else {
    						$check_color = 'black';
    					}

    					$output2 .= '<div style="color:' . $check_color . ';" class="rpt_feature rpt_feature_' . $key . '-' . $small_key . '">';
    					$output2 .= $feature;
    					$output2 .= '</div>';

    				}
    			}

    			$output2 .= '</div>';
    		}

    		if (!empty($plans['_rpt_btn_text'])){
    			$btn_text =	$plans['_rpt_btn_text'];
    			if (!empty($plans['_rpt_btn_link'])){
    				$btn_link =	$plans['_rpt_btn_link'];
    			} else { $btn_link = '#'; }
    		} else {
    			$btn_text =	'';
    			$btn_link = '#';
    		}

    		// Link option
    		$newcurrentwindow = get_post_meta( $post->ID, '_rpt_open_newwindow', true );
    		if ($newcurrentwindow == 'newwindow'){
    			$link_behavior = 'target="_blank"';
    		} else {
    			$link_behavior = 'target="_self"';
    		}

            // Check for custom button
            if (!empty($plans['_rpt_btn_custom_btn'])){
                $output2 .= '<div class="rpt_custom_btn" style="border-bottom-left-radius:5px; border-bottom-right-radius:5px; text-align:center; padding:16px 20px; background-color:'.$plans['_rpt_color'].'">';
                    $output2 .= do_shortcode($plans['_rpt_btn_custom_btn']);
                $output2 .= '</div>';
            } else {
    		  // Default footer
                if (!empty($plans['_rpt_btn_text'])){
    		    $output2 .= '<a '. $link_behavior .' href="' . do_shortcode($btn_link) . '" style="background:' . $plans['_rpt_color'] . '" class="rpt_foot rpt_foot_' . $key . '">';
                } else {
                  $output2 .= '<a '. $link_behavior .' style="background:' . $plans['_rpt_color'] . '" class="rpt_foot rpt_foot_' . $key . '">';
                }

                $output2 .= do_shortcode($btn_text);

    		  // Closing default footer
    		  $output2 .= '</a>';
            }

        $output2 .= '</div>';

    	}

    	// Closing rpt_inner
    	$output2 .= '</div>';

    	// Closing rpt_container
    	$output2 .= '</div>';

    	$output2 .= '<div style="clear:both;"></div>';

        endforeach; wp_reset_postdata();
  return $output2;

}
?>
