<?php
/* Template Name: Page - Fulfill Shortage */ 



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


get_header();

if($work_order_number == ""){
  ?>
<div id="general-error-msg" class="w3-panel w3-pale-red w3-border w3-margin w3-padding">
    <h3>Work Order Not Found.</h3>
</div>
<?php
}else{
  // IF work order found, show all
?>

<div id="shortageUpdateSuccess" style="display:none;"
    class="w3-panel w3-pale-green w3-bottombar w3-border-green w3-borde w3-margin w3-card-4">
    </br>
    <p class="w3-padding-top" id="shortageUpdateSuccessMSG"></p>
    <p class="w3-padding-top" id="emailSendSuccessMSG"></p>

</div>

<div class="w3-card-4 w3-margin w3-padding-bottom">

    <div class="w3-container w3-green">
        <h2 class="w3-margin-top">Shortage</h2>
    </div>

    <form class="w3-container" id="shortageForm"></br>

    </form>
    <button id="btn-form-loading" style="display: none;"
        class="w3-margin w3-btn w3-white w3-border w3-border-grey w3-round-xlarge">
        <i class="fa fa-spinner fa-spin"></i>Loading
    </button>
    <button id="btn-form"
        class="w3-margin w3-btn w3-white w3-border w3-border-green w3-round-xlarge">Update</button></br>


</div>



<script>
    jQuery(document).ready(function ($) {
        displayShortage();
        jQuery('#btn-form').click(function () {
            changeBOMStatus();
        });
    }); //End Doc ready


    // ++++++++++++ Testing
    // ++++++++++++ Testing
    // ++++++++++++ Testing
    // ++++++++++++ Testing

    // Example POST method implementation:
    async function postData(url = '', data = {}) {
        // Default options are marked with *
        const response = await fetch(url, {
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            headers: {
                'Content-Type': 'application/json'
                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer', // no-referrer, *client
            body: JSON.stringify(data) // body data type must match "Content-Type" header
        });
        return await response.json(); // parses JSON response into native JavaScript objects
    }

    postData('https://example.com/answer', {
            answer: 42
        })
        .then((data) => {
            console.log(data); // JSON data parsed by `response.json()` call
        });
    // +++++++++++++++ End testing
    // +++++++++++++++ End testing
    // +++++++++++++++ End testing

    function changeBOMStatus() {
        // Fulfill, add location, and email to technician
        jQuery('#btn-form-loading').css('display', 'block');
        jQuery('#btn-form').css('display', 'none');
        var data = jQuery('#shortageForm').serialize();

        var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
        // functions.php
        var data = {
            action: 'update_shortage_list_to_complete',
            workOrderBOMid: data,
            workID: <?php echo $getWorkID; ?>
        };

        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json'
        }).done(function (da) {
            jQuery('#shortageForm').empty();
            jQuery('#btn-form-loading').css('display', 'none');
            jQuery('#btn-form').css('display', 'block');
            jQuery('#shortageUpdateSuccess').css('display', 'block');
            jQuery('#shortageUpdateSuccessMSG').text("Items Updated: " + da.fulfillItem.length);
            jQuery('#emailSendSuccessMSG').text("Email Send to: " + da.TechList);
            displayShortage();
        }).fail(function (e) {
            alert("error: " + e);
        });;
    }

    function displayShortage() {
        jQuery.ajax({
            url: "/api/?action=getShortageList&wonum=<?php echo $work_order_number ?>",
        }).done(function (da) {
            var daJSON = JSON.parse(da);
            if (daJSON.length > 0) {
                for (i = 0; i < daJSON.length; i++) {
                    jQuery(
                        '<input name="' + daJSON[i].id + '" value="' + daJSON[i].id +
                        '" class="w3-check w3-margin-right" type="checkbox"><label>' + daJSON[i]
                        .component + ' - ' + daJSON[i].status + ' - ' + daJSON[i]
                        .work_order_number + ' - ' + daJSON[i].title + ' - Quantity: ' + daJSON[i]
                        .qty + '</label><br>').appendTo('#shortageForm');
                }
            }
        });
    }
</script>


<?php
  }
}
get_footer();