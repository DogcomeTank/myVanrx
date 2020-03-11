<?php
/* Template Name: Page - Kamban Qty Low */ 


function tree_view_page_enqueue_multiple() {
	wp_enqueue_script('tree_view_page_js', get_stylesheet_directory_uri() .'/js/tree-view.js', array(), false, false);
    wp_enqueue_style ( 'tree-view-style', get_stylesheet_directory_uri() . '/css/tree-view-style.css' );
}
add_action( 'wp_enqueue_scripts', 'tree_view_page_enqueue_multiple' );

get_header();

if ( !is_user_logged_in()) 
{
    ?>
<div style="width:33px ;margin: auto;"><?php echo do_shortcode( '[TheChamp-Login show_username="ON"]' ); ?></div>
<?php
    // exit;
}else{
    global $current_user;
    $current_user = wp_get_current_user();
    $login_user_id = $current_user->ID;

//     $the_action = $work_order_id = $work_order_number = "";
//     $the_action = sanitize_text_field($_GET['action']);
//     $work_order_id = sanitize_text_field($_GET['woid']);
//     $work_order_number = sanitize_text_field($_GET['wonum']);

?>


<?php

// +++++++++++++++++++++++++++
// +++++++++++++++++++++++++++
// // Email function works
// +++++++++++++++++++++++++++
// +++++++++++++++++++++++++++


// $to = 'dogcometank@gmail.com';
// $subject = 'The subject';
// $body = '<table style="width:100%">
// <tr>
//   <th>Firstname</th>
//   <th>Lastname</th>
//   <th>Age</th>
// </tr>
// <tr>
//   <td>Jill</td>
//   <td>Smith</td>
//   <td>50</td>
// </tr>
// <tr>
//   <td>Eve</td>
//   <td>Jackson</td>
//   <td>94</td>
// </tr>
// </table>';
// $headers = array('Content-Type: text/html; charset=UTF-8');

// $mailResult = wp_mail( $to, $subject, $body, $headers );

// echo $mailResult;


?>




<?php
}
get_footer();