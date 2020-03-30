<?php
/* Template Name: Time Clock History */ 


function enqueue_clockin_page() {
    wp_enqueue_style ( 'w3-style', get_stylesheet_directory_uri() . '/css/my-style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_clockin_page' );


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



    $the_action = $work_order_id = $work_order_number = "";

    $the_action = sanitize_text_field($_GET['action']);
    $work_order_id = sanitize_text_field($_GET['woid']);
    $work_order_number = sanitize_text_field($_GET['wonum']);

    $getWorkID = getWorkOrderIdByWorkNumber($work_order_number);

  // IF work order found, show all
?>

<div class="w3-container clock-in-to-last-work-order-btn"><button class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge" id="clock-in-to-last-work">Clock In To Last Work Order</button>
</div>


<div class="w3-container w3-margin-bottom">

    <div id="clockin-form" class="w3-modal">
        <div class="w3-modal-content">
            <div class="w3-container w3-padding">
                <span onclick="document.getElementById('clockin-form').style.display='none'" class="w3-button w3-display-topright">×</span>

                    <!-- form section -->
                <div class="w3-card-4">

                    <div class="w3-container w3-green">
                        <h2>Clock In/Out</h2>
                    </div>

                    <form class="w3-container">
                        <label>Work Order Number</label>
                        <input class="w3-input wo-input" id="wo" onkeyup="userClockin()" type="text">
                        <div id="livesearch"></div>
                    </form>

                </div>
                <!-- End form section -->
            </div>
        </div>
    </div>
</div>

<div class=""  style="margin: auto;">
    <table id="example" class="display" style="width: 100%;">
            <thead>
                <tr>
                    <th>Work Order</th>
                    <th>Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Time</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Work Order</th>
                    <th>Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Time</th>
                </tr>
            </tfoot>
    </table>

</div>



<script>

jQuery(document).ready(function() {
    jQuery('#example').DataTable( {
        "ajax": "/api/?action=getLoginHistory",
         "order": [[ 2, "desc" ]],
         "scrollX": true
    } );
    jQuery('#clock-in-to-last-work').click(function(){
        jQuery.ajax({
            url: "/api/?action=clockInToLast",
        }).done(function(da){
            checkIfUserClockIn();
            jQuery('#clockInMessage').text(da);
        });
    });
} );// document ready end

</script>


<?php 
}
get_footer();
