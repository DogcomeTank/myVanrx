<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'chld_thm_cfg_parent','cocktail-style','font-awesome','cocktail-responsive' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );
// END ENQUEUE PARENT ACTION



function enqueue_multiple() {
	// wp_enqueue_script('create_qr_code_js', get_stylesheet_directory_uri() .'/assets/js/qrcodejs/qrcode.js', array(), false, false);
	wp_enqueue_script('general_js', get_stylesheet_directory_uri() .'/js/general.js', array(), false, false);
    wp_enqueue_script ( 'datatables-script', get_stylesheet_directory_uri() . '/inc/DataTables/datatables.min.js' );
	wp_enqueue_style ( 'datatables-style', get_stylesheet_directory_uri() . '/inc/DataTables/datatables.min.css' );
    wp_enqueue_style ( 'w3-style', get_stylesheet_directory_uri() . '/assets/css/w3.css' );
    wp_enqueue_style ( 'my-style', get_stylesheet_directory_uri() . '/css/my-style.css' );

    //Page - process improvement
    if(is_page('process-improvement')){
        wp_enqueue_script('sortable-prettify-js', get_stylesheet_directory_uri() .'/inc/Sortable-master/Sortable.js', array(), false, false);
        // wp_enqueue_script('sortable-Sortable-js', get_stylesheet_directory_uri() .'/inc/Sortable-master/Sortable.js', array(), false, true);
        wp_enqueue_style ( 'sortable-style', get_stylesheet_directory_uri() . '/inc/Sortable-master/prettify.css' );
    }
    //end process improvement

}
add_action( 'wp_enqueue_scripts', 'enqueue_multiple' );

function TwentySeventeenChild_enqueue_child_styles() {
$parent_style = 'parent-style'; 
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 
		'child-style', 
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version') );
	}
add_action( 'wp_enqueue_scripts', 'TwentySeventeenChild_enqueue_child_styles' );



function getWorkOrderIdByWorkNumber($workNumber){
    global $wpdb;

    $datatables_sql = "
    SELECT * FROM `work_order` WHERE work_order_number =  '".$workNumber."' LIMIT 50
    ";
    $data = $wpdb->get_results ( $datatables_sql );
    return $data[0]->id;
}




function enqueue_fullcalendar() {
    if ( is_front_page() ){
        wp_enqueue_style ( 'fullcalendar-main-style', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/core/main.css' );
        wp_enqueue_style ( 'fullcalendar-daygrid-style', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/daygrid/main.css' );
        wp_enqueue_style ( 'fullcalendar-timegrid-style', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/timegrid/main.css' );
        wp_enqueue_style ( 'fullcalendar-list-style', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/list/main.css' );
    
        wp_enqueue_script ( 'fullcalendar-main-script', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/core/main.js' );
        wp_enqueue_script ( 'fullcalendar-interaction-script', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/interaction/main.js' );
        wp_enqueue_script ( 'fullcalendar-daygrid-script', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/daygrid/main.js' );
        wp_enqueue_script ( 'fullcalendar-timegrid-script', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/timegrid/main.js' );
        wp_enqueue_script ( 'fullcalendar-list-script', get_stylesheet_directory_uri() . '/inc/fullcalendar-4.3.1/packages/list/main.js' );
    }

    if(is_page('process-improvement') or is_page('agile-project-management') ){
        wp_enqueue_script ( 'process-improvement-script', get_stylesheet_directory_uri() . '/js/process-improvement.js' );
        
    }
}

add_action( 'wp_enqueue_scripts', 'enqueue_fullcalendar' );


// SL Custom 

// remove css/js version
    // remove version from head
    remove_action('wp_head', 'wp_generator');

    // remove version from rss
    add_filter('the_generator', '__return_empty_string');

    // remove version from scripts and styles
    function remove_version_scripts_styles($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    add_filter('style_loader_src', 'remove_version_scripts_styles', 9999);
    add_filter('script_loader_src', 'remove_version_scripts_styles', 9999);
// End Remove css/js version
// 
// 
	function assemly_redirect_if_not_login(){
		if(is_singular('assembly') && !is_user_logged_in()){
			wp_redirect( '/');
			exit;
		}
	}
	add_action('wp_head','assemly_redirect_if_not_login');
// 

// Add cron interval
add_filter( 'cron_schedules', 'example_add_cron_interval' );

    function example_add_cron_interval( $schedules ) {
    $schedules['one_minute'] = array(
    'interval' => 60,
    'display' => esc_html__( 'Every One Minute' ),
    );
    return $schedules;
 }

// prepair cron job task
add_action('one_minute_event', 'do_this_every_minute');
 
function do_this_every_minute() {
    // do something every hour
    global $wpdb;
    $wpdb->insert('test', array(
         'time' => date('Y-m-d H:i:s'),
    ));
}

//add cron job by shortcode
function add_test_cron() {
    if (! wp_next_scheduled ( 'one_minute_event' )) {
    	wp_schedule_event(time(), 'one_minute', 'one_minute_event');
    }
}
add_shortcode('test_cron', "add_test_cron");

// End Cron

// show assembly shortcode
function show_assembly_post() {
    
}
add_shortcode('show_assembly_post', "show_assembly_post");
// end show assembly shortcode




