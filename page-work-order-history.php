<?php
/* Template Name: Work Order History */ 



if ( !is_user_logged_in()) 
{
    wp_redirect( esc_url( home_url( '/' )."wp-login.php" ) );
    
    exit;
}else{
    global $current_user;
    $current_user = wp_get_current_user();
    $login_user_id = $current_user->ID;
}

$the_action = $work_order_id = $work_order_number = "";

$the_action = sanitize_text_field($_GET['action']);
$work_order_id = sanitize_text_field($_GET['woid']);
$work_order_number = sanitize_text_field($_GET['wonum']);

function enqueue_this_page_style() {
	wp_enqueue_script( 'work_order_history', get_stylesheet_directory_uri()  . '/js/work-order-history.js',array(), '1.0.0', 'true' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_this_page_style', 10 );

get_header();

?>



<div class="w3-row">
  <div class="w3-col s12">
    <form class="w3-container">

      <h2>Work Order Number</h2>
      <input class="w3-input" id="work-number" type="text" value="<?php echo $work_order_number; ?>">
      <div id="livesearch"></div>

    </form>

  </div>

  <div class="w3-center">
    <div class="w3-col s12 m6 l4 w3-sand">
      <h3>History</h3>
      <div id="wo-history">

        
      </div>
    </div>

    <div class="w3-col s12 m6 l4">
      <h3>To-dos</h3>
      <div id="wo-notes"></div>
    </div>

    <div class="w3-col s12">
      <h3>Shortage</h3>
      <div id="wo-shortage"></div>
    </div>

  </div>

</div>

<?php

get_footer();