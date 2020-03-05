<?php
/* Template Name: Page - QR Code Scan - Work Order */ 

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

  if($work_order_number == ""){
    ?>
<div id="general-error-msg" class="w3-panel w3-pale-red w3-border w3-margin w3-padding">
  <h3>Work Order Not Found.</h3>
</div>
<?php }else{ // IF work order found, show all content of the body ?>


<div class="w3-row">
  <div class="w3-center">
    <h2>Work Order: <?php echo $work_order_number; ?></h2>
  </div>
  <div class="w3-col s12 m6">
    <div class="w3-card-4 w3-padding  w3-margin">
      <h3>Clock In</h3>
      <div style="display: none;" id="msg-box" class="w3-panel w3-pale-green w3-bottombar w3-border-green w3-border">
        <p id="clockInMessage"></p>
      </div>

      <div id="typeOfWorkList" class="w3-margin-top w3-margin-bottom"></div>

      <button id="btn-clock-in-loading" style="display: none;"
        class="w3-margin-bottom w3-btn w3-white w3-border w3-border-grey w3-round-xlarge"><i
          class="fa fa-spinner fa-spin"></i>Loading</button>
      <button id="btn-clock-in" class="w3-margin-bottom w3-btn w3-white w3-border w3-round-xlarge">Clock In</button>
      <a href="./work-order"><button 
          class="w3-margin-bottom w3-btn w3-white w3-border w3-border-green w3-round-xlarge">Clock In To Other Work
          Order</button></a>
    </div>

    <div class="w3-card-4 w3-padding  w3-margin">
      <h3>E-Drawing</h3>
      <a id="e-drawing-btn" href="./work-order"><button
          class="w3-margin-bottom w3-btn w3-white w3-border w3-border-green w3-round-xlarge">E-Drawing</button></a>
    </div>
  </div>

  <div class="w3-col s12 m6">
    <div class="w3-card-4 w3-padding  w3-margin">
      <h3>Bin Current Location</h3>
      <div id="">
        <p id="currentBinLocation"></p>
      </div>
      <h3>Change Bin Location</h3>
      <div id="storageLocationList"></div>
      <button id="btn-change-bin-location"
        class="w3-margin-bottom w3-margin-top w3-btn w3-white w3-border w3-round-xlarge w3-border-green">Change
        Location</button>
    </div>

  </div>
</div>



<script>
  jQuery(document).ready(function () {
    getWorkType();
    getCurrentBinLocation('<?php echo $work_order_number; ?>');
    getBinLocationList();
    getEdrawingByWorkNum("<?php echo $work_order_number; ?>");

    
    jQuery('#btn-clock-in').text('Clock In To <?php echo $work_order_number; ?>');

    jQuery('#btn-clock-in').click(function () {
      userClockIn();
    });

    jQuery('#btn-change-bin-location').click(function () {
      var newLocationId = jQuery("input[name='storage_location']:checked").val();
      changBinLocation(newLocationId, '<?php echo $work_order_number; ?>');
    });

  }); //end Doc ready


  function userClockIn() {
    jQuery('#btn-clock-in').css('display', 'none');
    jQuery('#btn-clock-in-loading').css('display', 'block');
    var work_type_id = jQuery("input[name='work-type']:checked").val();

    var apiURL = "/api/?action=userClockInAndOut&wonum=<?php echo $work_order_number; ?>&wtid=" + work_type_id;

    jQuery.ajax({
      url: apiURL,
    }).done(function (da) {
      jQuery('#msg-box').css('display', 'block');
      jQuery('#clockInMessage').text(da);
      jQuery('#btn-clock-in').css('display', 'block');
      jQuery('#btn-clock-in-loading').css('display', 'none');
      checkIfUserClockIn();
    });

  } //end userClockIn()

  function getWorkType() {
    // empty work type list
    jQuery('#typeOfWorkList').empty();

    jQuery.ajax({
      url: "/api/?action=getWorkTypeList",
    }).done(function (da) {
      var data = JSON.parse(da);

      for (i = 0; i < data.length; i++) {
        if (i == 0) {
          var inputOption = '<input class="w3-radio" type="radio" name="work-type" value="' + data[i]
            .id + '" checked><label class=" w3-margin-right">' + data[i].work_type + '</label>';
        } else {
          var inputOption = '<input class="w3-radio" type="radio" name="work-type" value="' + data[i]
            .id + '"><label class="w3-margin-right">' + data[i].work_type + '</label>';
        }

        jQuery('#typeOfWorkList').append(inputOption);

      }
    });
  }
</script>

<?php
  }
}
get_footer();