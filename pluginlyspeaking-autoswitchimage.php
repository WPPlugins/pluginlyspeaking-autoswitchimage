<?php 
/**
 * Plugin Name: PluginlySpeaking AutoSwitchImage
 * Plugin URI: http://pluginlyspeaking.com/plugins/autoswitch-image/
 * Description: Easily switch between a set of pics.
 * Author: PluginlySpeaking
 * Version: 1.2.1.1
 * Author URI: http://www.pluginlyspeaking.com
 * License: GPL2
 */


if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

add_action( 'wp_enqueue_scripts', 'add_autoswitch_image_script' );

function add_autoswitch_image_script() {
	wp_enqueue_script("jquery");
}

// Enqueue admin styles
add_action( 'admin_enqueue_scripts', 'add_autoswitch_image_admin_style' );
function add_autoswitch_image_admin_style() { wp_enqueue_style( 'adm_autoswitch_image', plugins_url('css/ps_admin_de_style.css', __FILE__)); }


// Create post type
function create_autoswitch_image_type() {
  register_post_type( 'auto_s_image_ps',
    array(
      'labels' => array(
        'name' => 'Autoswitches Image',
        'singular_name' => 'Autoswitch Image'
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => false,
      'supports'           => array( 'title' ),
      'menu_icon'    => 'dashicons-plus',
    )
  );
}

add_action( 'init', 'create_autoswitch_image_type' );


function autoswitch_image_admin_css() {
    global $post_type;
    $post_types = array( 
                        'auto_s_image_ps',
                  );
    if(in_array($post_type, $post_types))
    echo '<style type="text/css">#edit-slug-box, #post-preview, #view-post-btn{display: none;} div[class*="-disabled-"] .cmb2-metabox-description {color: #00b783;background-image: url(\''.plugins_url('img/disabled.png', __FILE__).'\');background-repeat: no-repeat;padding-left: 30px;display: block;}</style>';
}

function remove_view_link_autoswitch_image( $action ) {

    unset ($action['view']);
    return $action;
}

add_filter( 'post_row_actions', 'remove_view_link_autoswitch_image' );
add_action( 'admin_head-post-new.php', 'autoswitch_image_admin_css' );
add_action( 'admin_head-post.php', 'autoswitch_image_admin_css' );


function autoswitch_image_metabox() {
	$prefix = '_autoswitch_image_';
	
	$cmb_group = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Autoswitch Image', 'auto_s_image_ps' ),
		'object_types' => array( 'auto_s_image_ps' ),
	) );
	
	$cmb_group->add_field( array(
		'name' => __( 'Class name', 'auto_s_image_ps' ),
		'id'   => $prefix . 'class_name',
		'type' => 'text',
		'desc' => 'Set a class name, to easily apply your stylesheet (CSS).',
	) );
	
	$cmb_group->add_field( array(
		'name' => __( 'First Image', 'auto_s_image_ps' ),
		'id'   => $prefix . 'first_image',
		'type' => 'file',
		'desc' => 'Keep the same size between both images.',
		'options' => array(
			'url' => false
		)
	) );
	
	$cmb_group->add_field( array(
		'name' => __( 'Second Image', 'auto_s_image_ps' ),
		'id'   => $prefix . 'second_image',
		'type' => 'file',
		'desc' => 'Add as many image as you want in the PRO Version.',
		'options' => array(
			'url' => false
		)
	) );
	
	$cmb_group->add_field( array(
		'name' => __( 'Duration', 'auto_s_image_ps_pro' ),
		'id'   => 'disabled_1',
		'default' => '2000',
		'type' => 'text',
		'desc' => 'Available in the PRO Version.',
		'attributes'  => array(
			'readonly' => 'readonly',
			'disabled' => 'disabled',
		),
	) );
	
	$cmb_group->add_field( array(
		'name'             => 'Effects Speed',
		'desc'             => 'Available in the PRO Version.',
		'id'               => 'disabled_2',
		'type'             => 'select',
		'show_option_none' => false,
		'default'          => 'fast',
		'options'          => array(
			'fast' => __( 'Fast', 'cmb2' ),
		),
		'attributes'  => array(
			'readonly' => 'readonly',
			'disabled' => 'disabled',
		),
	) );
	
	// PRO version
    $pro_group = new_cmb2_box( array(
        'id' => $prefix . 'pro_mb',
        'title' => '<span style="font-weight:400;">Upgrade to <strong>PRO version</strong></span>',
        'object_types' => array( 'auto_s_image_ps' ),
       'context' => 'side',
        'priority' => 'low',
        'row_classes' => 'de_hundred de_heading',
    ));
	
	$pro_group->add_field( array(
		'name' => '',
			'desc' => '<div><span class="dashicons dashicons-yes"></span> Unlimited Images<br/><span class="dashicons dashicons-yes"></span> Animation speed<br/><span class="dashicons dashicons-yes"></span> Time on image<br/><span class="dashicons dashicons-arrow-right"></span> And more...<br/><br/><a style="display:inline-block; background:#33b690; padding:8px 25px 8px; border-bottom:3px solid #33a583; border-radius:3px; color:white;" target="_blank" href="http://pluginlyspeaking.com/plugins/autoswitch-image/">See all PRO features</a><br/><span style="display:block;margin-top:14px; font-size:13px; color:#0073AA; line-height:20px;"><span class="dashicons dashicons-tickets"></span> Code <strong>ASI10OFF</strong> (10% OFF)</span></div>',
			'id'   => $prefix . 'pro_desc',
			'type' => 'title',
			'row_classes' => 'de_hundred de_info de_info_side',
	));
}

add_action( 'cmb2_init', 'autoswitch_image_metabox' );

add_action( 'manage_auto_s_image_ps_posts_custom_column' , 'autoswitch_image_custom_columns', 10, 2 );

function autoswitch_image_custom_columns( $column, $post_id ) {
    switch ( $column ) {
	case 'shortcode' :
		global $post;
		$pre_slug = '' ;
		$pre_slug = $post->post_title;
		$slug = sanitize_title($pre_slug);
    	$shortcode = '<span style="border: solid 3px lightgray; background:white; padding:7px; font-size:17px; line-height:40px;">[autoswitch_image_ps name="'.$slug.'"]</strong>';
	    echo $shortcode; 
	    break;
    }
}

function add_autoswitch_image_columns($columns) {
    return array_merge($columns, 
              array('shortcode' => __('Shortcode'),
                    ));
}
add_filter('manage_auto_s_image_ps_posts_columns' , 'add_autoswitch_image_columns');


function autoswitch_image_shortcode($atts) {
	extract(shortcode_atts(array(
		"name" => ''
	), $atts));
		
	global $post;
    $args = array('post_type' => 'auto_s_image_ps', 'numberposts'=>-1);
    $custom_posts = get_posts($args);
	$output = '';
	foreach($custom_posts as $post) : setup_postdata($post);
	$sanitize_title = sanitize_title($post->post_title);
	if ($sanitize_title == $name)
	{
	$prefix = '_autoswitch_image_';
    $first_image = get_post_meta( get_the_id(), $prefix . 'first_image', true );
	$second_image = get_post_meta( get_the_id(), $prefix . 'second_image', true );
	$class_name = get_post_meta( get_the_id(), $prefix . 'class_name', true );
	
	$postid = get_the_ID();
	
	$output = '';
	$output .= '<img id="autoswitch_image_ps_'.$postid.'" class="'.esc_attr( $class_name).'" src="' . esc_attr( $first_image ) . '"/>';
	$output .= '<script type="text/javascript">';
	$output .= '$j=jQuery.noConflict();';	
	$output .= 'var index'.$postid.' = 1;';
	
	$output .= ' var images'.$postid.' = new Array();';
	$output .= 'function preload'.$postid.'() {';
	$output .= '	for (i = 0; i < preload'.$postid.'.arguments.length; i++) {';
	$output .= '		images'.$postid.'[i] = new Image();';
	$output .= '		images'.$postid.'[i].src = preload'.$postid.'.arguments[i];';
	$output .= '	}';
	$output .= '};';
	$output .= 'preload'.$postid.'("' . esc_attr( $first_image ) . '", "' . esc_attr( $second_image ) . '");';
	
	$output .= 'function rotateImage'.$postid.'()';
	$output .= '{';
	$output .= '  $j("#autoswitch_image_ps_'.$postid.'").fadeOut("fast", function() ';
	$output .= '  {';
	$output .= '	$j(this).attr("src", images'.$postid.'[index'.$postid.'].src);';
	$output .= '	';
	$output .= '	$j(this).fadeIn("fast", function() ';
	$output .= '	{';
	$output .= '	  if (index'.$postid.' == images'.$postid.'.length-1) { index'.$postid.' = 0; } else { index'.$postid.'++; }';
	$output .= '	});';
	$output .= '  });';
	$output .= '};';
	$output .= ' ';
	$output .= '$j(document).ready(function()';
	$output .= '{';
	$output .= '  setInterval (rotateImage'.$postid.', 2000);';
	$output .= '});';
	$output .= '</script>';
	}
	endforeach; wp_reset_query();
	return $output;
}
add_shortcode( 'autoswitch_image_ps', 'autoswitch_image_shortcode' );


	
?>