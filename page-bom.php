<?php
/* Template Name: Page - Upload/Check BOM*/ 



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



<div id="bom-list-div" style="display: none;" class="w3-row w3-section w3-card-4 w3-margin w3-center w3-padding">

  <table id="part-found-table"></table>
  <h3>Total Items' Checked: <span id="totalCheckNumber">0</span></h3>

  <input id="dataStorage" type="text" style="display:none">

<form id="ipn-check-form">
    <label for="form-ipn-input-value">IPN:</label><br>
    <input type="text" id="form-ipn-input-value" name="form-ipn"><br>
    <input type="submit" value="Check">
</form> 




  <div id="bom-list" class="w3-col s12">
    <table id="bom-list" class="w3-table-all w3-small">

    </table>

  </div>

</div>





<div id="uploadBOMDiv" class="w3-row-padding w3-section w3-card-4 w3-margin w3-center">
  <h3 class="w3-margin">Upload BOM</h3>
  <div class="w3-col s12 m4 w3-margin-bottom">

    <a href="https://www.csvjson.com/csv2json" target="_blank"><button
        class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge">Convert CSV to Json Online</button></a></br>

    <div class="w3-card-4 w3-padding w3-margin-top">
      <h3>Work Order Number: <?php echo $work_order_number; ?></h3>

      <label>Shortage Input</label>
      <input id="shortage-list-input" class="w3-input" type="text">
      <button id="shortage-list-btn" class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge">Convert</button>
    </div>

  </div>
  <div id="converted-section" class="w3-col s12 m8 w3-padding-right">
    <button id="shortage-upload" class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge w3-margin">Upload
      Shortage List to <?php echo $work_order_number; ?></button>
    <div id='shortage-converted'></div>
  </div>
</div>

<script>
  jQuery(document).ready(function () {


    jQuery("#ipn-check-form").submit(function(e){
      e.preventDefault();
      var ipnToCheck = jQuery('#form-ipn-input-value').val();
      var trimIPN = jQuery.trim(ipnToCheck);
      checkDataInList(trimIPN, 'form-ipn-input-value')
    });

    // Display BOM if exist
    getBOMIfExist();


    // 2
    jQuery("#shortage-converted").empty();
    jQuery("#shortage-upload").css("display", "none");

    jQuery("#shortage-list-input").change(function () {

      jQuery("#shortage-converted").empty();
      jQuery("#shortage-upload").css("display", "block");

      var textIn = jQuery('#shortage-list-input').val();
      try {
        var shortageObj = JSON.parse(textIn);
      } catch (e) {
        jQuery("#shortage-upload").css("display", "none");
      }
      var totalShortageImport = shortageObj.length;


      var table = jQuery('<table>').addClass('w3-margin');

      for (x = 0; x < totalShortageImport; x++) {
        jQuery("<tr><td>" + shortageObj[x].component + "</td><td>" + shortageObj[x].title +
          "</td><td>" + shortageObj[x].status + "</td><td>" + shortageObj[x].qty + "</td></tr>").appendTo(
          table);
      }
      jQuery("<h3 class='w3-margin'>Totoal Items: " + totalShortageImport + "</h3>").appendTo(
        "#shortage-converted");
      table.appendTo("#shortage-converted");

    });
    // 2

    jQuery("#shortage-upload").click(function () {
      var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
      var textIn = jQuery('#shortage-list-input').val();
      var shortageObj = JSON.parse(textIn);

      // functions.php
      var data = {
        action: 'shortage_upload',
        uploadshortageOrder: shortageObj,
        wonum: "<?php echo $work_order_number; ?>"
      };


      jQuery.ajax({
        url: ajax_url,
        type: 'post',
        data: data,
        dataType: 'json'
      }).done(function (da) {
        alert(da);
      }).fail(function (e) {
        alert("error: " + e);
      });;


    });

  }); //End Doc ready


  function trimArrayAndRemoveSpace(item, index, arr) {
    arr[index] = item.trim();
    if (arr[index] == "" || arr[index] == "↵") {
      arr.splice(index, 1);
    }
  }

  function getBOMIfExist() {
    var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
    // functions.php
    var data = {
      action: 'get_shortage_list',
      wonum: "<?php echo $work_order_number; ?>"
    };

    jQuery.ajax({
      url: ajax_url,
      type: 'post',
      data: data,
      dataType: 'json'
    }).done(function (da) {
      if (da.length > 0) {
        jQuery("#bom-list-div").css("display", "block");
      }
      displayDataInList(da);
      jQuery('#dataStorage').val(JSON.stringify(da));
    }).fail(function (e) {
      console.log("error: " + e);
    });;

  }

  function displayDataInList(divID) {

    var table = jQuery('<table>').addClass('w3-table-all w3-small');

    for (x = 0; x < divID.length; x++) {
      jQuery("<tr class='w3-pale-blue' id='bom-list-tr-" + divID[x].component + x + "'><td id='component-id-" + divID[x]
        .component + x + "'>" + divID[x].component + "</td><td>" + divID[x].title +
        "</td><td>" + divID[x].status + "</td><td>" + divID[x].qty + "</td></tr>").appendTo(
        table);
    }
    jQuery("<h3 class='w3-margin'>Totoal Items: " + divID.length + "</h3>").appendTo(
      "#bom-list");
    table.appendTo("#bom-list");

  }
  // End displayDataInList()



  function checkDataInList(val,hl) {
    var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
    // functions.php
    var data = {
      action: 'get_shortage_list',
      wonum: "<?php echo $work_order_number; ?>"
    };

    jQuery.ajax({
      url: ajax_url,
      type: 'post',
      data: data,
      dataType: 'json'
    }).done(function (OriginalData) {

      // each arr in obj
      var valueToCheck = val;
      // var valueToCheck = jQuery('#bom-ipn').val();
      var dataStorageVal = jQuery('#dataStorage').val();
      var dataStorageValobj = JSON.parse(dataStorageVal);
      var check_result_fail = false;

      for (i = 0; i < OriginalData.length; i++) {

        if (valueToCheck == dataStorageValobj[i].component && dataStorageValobj[i].component != "done") {
          var bomListId = OriginalData[i].component + i;
          dataStorageValobj[i].component = "done";
          jQuery('#dataStorage').val(JSON.stringify(dataStorageValobj));
          jQuery("#bom-list-tr-" + bomListId).removeClass("w3-pale-blue");
          jQuery('<tr><td>' + OriginalData[i].component + '</td><td>' + OriginalData[i].title + '</td><td>' +
            OriginalData[i].status + '</td><td>' + OriginalData[i].qty + '</td></tr>').appendTo(
            "#part-found-table");
          var totalCheck = Number(jQuery('#totalCheckNumber').text());
          var totalCheck = totalCheck + 1;
          jQuery('#totalCheckNumber').text(totalCheck);
          jQuery('#'+hl+'').select();
          break;
        }
      }

    }).fail(function (e) {
      alert("error: " + e);
    });;
  } // End function


</script>


<?php
  }
}
get_footer();