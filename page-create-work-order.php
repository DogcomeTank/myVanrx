<?php
/* Template Name: Create Work Order */ 

get_header();

if ( !is_user_logged_in()) 
{
    ?>
    <div style="width:33px ;margin: auto; margin-bottom: 38px;"><?php echo do_shortcode( '[TheChamp-Login show_username="ON"]' ); ?></div>
    <?php
}else{
    global $current_user;
    $current_user = wp_get_current_user();
    $login_user_id = $current_user->ID;

$the_action = $work_order_id = $work_order_number = "";

$the_action = sanitize_text_field($_GET['action']);
$work_order_id = sanitize_text_field($_GET['woid']);
$work_order_number = sanitize_text_field($_GET['wonum']);

function enqueue_this_page_style() {
	wp_enqueue_script( 'create-work-order', get_stylesheet_directory_uri()  . '/js/create-work-order.js',array(), '1.0.0', 'true' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_this_page_style', 10 );



?>
<div id="general-error-msg" style="display:none;" class="w3-panel w3-pale-red w3-border">
  <h3>Error!</h3>
  <p>Message: <span id="general-error-msg-detail">N/A</span></p>
</div>
<div id="work-order-upload-fail" style="display:none;" class="w3-panel w3-pale-red w3-border">
  <h3>Error!</h3>
  <p>Work Order Upload Failed: <span id="work-order-upload-fail-message"></span></p>
</div>
<div id="work-order-upload-success" style="display:none;" class="w3-panel w3-green">
  <h3>Success!</h3>
  <p>Total Work Order Uploaded: <span id="total-work-order-upload-success"></span></p>
</div> 

<div class="w3-row-padding w3-section">
  <div class="w3-col s12 m4 w3-margin-bottom">
    <a href="https://www.csvjson.com/csv2json" target="_blank"><button
        class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge">Convert CSV to Json Online</button></a></br>


    <div class="w3-card w3-padding w3-margin-top">
      <label>Work Order Input</label>
      <input id="work-order-list-input" class="w3-input" type="text">
      <button id="work-order-list-btn"
        class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge">Convert</button>
    </div>

  </div>
  <div id="converted-section" class="w3-col s12 m8 w3-padding-right">
    <button id="work-order-upload"
      class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge w3-margin">Upload</button>
    <div id='work-order-converted'></div>
  </div>
</div>

<script>
  jQuery(document).ready(function ($) {

    // 2
    jQuery("#work-order-converted").empty();
    jQuery("#work-order-upload").css("display", "none");
    jQuery("#work-order-list-input").change(function () {

      jQuery("#work-order-converted").empty();
      jQuery("#work-order-upload").css("display", "block");

      var textIn = jQuery('#work-order-list-input').val();
      try {
        var workOrderObj = JSON.parse(textIn);
      } catch (e) {
        jQuery("#work-order-upload").css("display", "none");
        jQuery("#general-error-msg").css("display", "block");
        jQuery("#general-error-msg-detail").html(e);
      }

      try {
        var totalWorkImport = workOrderObj.length;
        var table = jQuery('<table>').addClass('w3-margin');

        if(totalWorkImport > 0 ){
          for (x = 0; x < totalWorkImport; x++) {
            jQuery("<tr><td>" + workOrderObj[x].work_order_number + "</td><td>" + workOrderObj[x].name +
              "</td><td>" + workOrderObj[x].assembly_id+ "</td><td>" + workOrderObj[x].sys_number+ "</td></tr>").appendTo(table);
          }
          jQuery("<h3 class='w3-margin'>Totoal Items: " + totalWorkImport + "</h3>").appendTo(
            "#work-order-converted");
          table.appendTo("#work-order-converted");
        }else{
          jQuery("#work-order-upload").css("display", "none");
          jQuery("#general-error-msg").css("display", "block");
          jQuery("#general-error-msg-detail").html("No work order found in the input.");
        }
      } catch (e) {
        jQuery("#work-order-upload").css("display", "none");
        jQuery("#general-error-msg").css("display", "block");
        jQuery("#general-error-msg-detail").html(e);
      }
      


      

    });
    // 2

    jQuery("#work-order-upload").click(function(){
      var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
      var textIn = jQuery('#work-order-list-input').val();
      var workOrderObj = JSON.parse(textIn);

      var data = {
        action: 'work_order_upload',
        uploadWorkOrder: workOrderObj
      };

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: data,
        dataType: 'json'
      }).done(function (da) {
        jQuery("#work-order-upload").css("display", "none");
        jQuery("#work-order-upload-success").css("display", "block");
        jQuery("#total-work-order-upload-success").html(da);

      }).fail(function (e) {
        jQuery("#work-order-upload-fail").css("display", "block");
        jQuery("#work-order-upload-fail-message").html(da);
      });;


    });
  




  });

  function trimArrayAndRemoveSpace(item, index, arr) {
    arr[index] = item.trim();
    if (arr[index] == "" || arr[index] == "↵") {
      arr.splice(index, 1);
    }
  }
</script>


<?php
}

get_footer();