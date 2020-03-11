<?php
/* Template Name: Page - Kanban */ 


// function tree_view_page_enqueue_multiple() {
// 	wp_enqueue_script('tree_view_page_js', get_stylesheet_directory_uri() .'/js/tree-view.js', array(), false, false);
//     wp_enqueue_style ( 'tree-view-style', get_stylesheet_directory_uri() . '/css/tree-view-style.css' );
// }
// add_action( 'wp_enqueue_scripts', 'tree_view_page_enqueue_multiple' );

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

    $title = $description = "";
    $title = test_input($_GET['title']);
    $description = test_input($_GET['description']);

?>

<div id="messageDiv" class="w3-panel w3-pale-green w3-leftbar w3-rightbar w3-border-green" style="display: none;">
    <h6 class="w3-center" id="message"></h6>
</div>

<div class="w3-margin w3-padding-bottom">
    <form class="w3-container w3-light-grey" style="max-width: 600px; margin: auto;">
        <h3 class="w3-margin-top">Email Form</h3>
        <label>Title</label>
        <input id="titleInput" class="w3-input w3-border-0" type="text" required>

        <label>Description</label>
        <input id="descriptionInput" class="w3-input w3-border-0" type="text">

        <label>Email Send To</label>
        <input id="emailInput" class="w3-input w3-border-0" type="email" required>

        <button id="emailFormBtn" class="w3-green w3-round w3-margin-bottom">Email</button>
    </form>
</div>


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
<script>
    var log = console.log;
    jQuery(document).ready(function () {

        jQuery('#titleInput').val("<?php echo $title; ?>");
        jQuery('#descriptionInput').val("<?php echo $description; ?>");
        jQuery('#emailInput').val("sliu@vanrx.com");




        jQuery('#emailFormBtn').click(function (e) {
            e.preventDefault();
            var title = jQuery('#titleInput').val();
            var description = jQuery('#descriptionInput').val();
            var email = jQuery('#emailInput').val();

            var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
            var data = {
                title: title,
                description: description,
                email: email,
                action: "kanban_email",
            };
            jQuery.ajax({
                url: ajax_url,
                type: "POST",
                data: data,
            }).done(function (da) {
                if (da) {
                    jQuery('#message').text('Email Sent.')
                    jQuery('#titleInput').val("");
                    jQuery('#descriptionInput').val("");
                    jQuery('#emailInput').val("");
                    jQuery('#messageDiv').css('display', 'block');
                }
            }).fail(function (e) {
                alert("error: " + e);
            });;
        });
    });
</script>



<?php
}

get_footer();