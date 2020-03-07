<?php
/* Template Name: Page - Tree View */ 


function tree_view_page_enqueue_multiple() {
	// wp_enqueue_script('create_qr_code_js', get_stylesheet_directory_uri() .'/assets/js/qrcodejs/qrcode.js', array(), false, false);

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

    $the_action = $work_order_id = $work_order_number = "";
    $the_action = sanitize_text_field($_GET['action']);
    $work_order_id = sanitize_text_field($_GET['woid']);
    $work_order_number = sanitize_text_field($_GET['wonum']);

if($work_order_number == ""){
?>




<div id="loading-modal" class="w3-modal">
    <div id="loading-modal-content" class="w3-modal-content">
        <div class="w3-container">

            <div class="loading-boxes">
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>

            <h3>I'm Loading...</h3>

        </div>
    </div>
</div>


<!-- Tree View Div -->
<div class="tf-tree example">
  <ul>
    <li>
      <span class="tf-nc">1</span>
      <ul>
        <li>
          <span class="tf-nc">2</span>
          <ul>
            <li><span class="tf-nc">4</span></li>
            <li>
              <span class="tf-nc">
                  <p>Fan Assy</p>
                  <p>60031</p>
              </span>
              <ul>
                <li><span class="tf-nc">9</span></li>
                <li><span class="tf-nc">10</span></li>
              </ul>
            </li>
            <li><span class="tf-nc">6</span></li>
          </ul>
        </li>
        <li>
          <span class="tf-nc">3</span>
          <ul>
            <li><span class="tf-nc">7</span></li>
            <li><span class="tf-nc">8</span></li>
          </ul>
        </li>
      </ul>
    </li>
  </ul>
</div>
<!-- End Tree View Div -->




<script>
    jQuery(document).ready(function () {
        // +++++++++++++++++++++

        getCurrentBinLocation();


        // get all work order when page open
        var table = jQuery('#example').DataTable({
            "ajax": "/api/?action=getWorkOrderList",
            "order": [
                [0, "desc"]
            ],
            "pageLength": 25,
            "scrollX": true
        });

        jQuery('#example').on('click', 'td', function () {
            var isUserClockIn = isClockin();

            var column_id = table.column(this).index();
            var cell_data = table.cell(this).data();

            switch (column_id) {
                case 0:
                    // code block

                    break;
                case 1:
                    // work order number
                    workOrderClick(cell_data, isUserClockIn);
                    break;
                case 2:
                    // Assembly id, open Solid Work
                    getAssemblyDrawingFromSolidWork(cell_data);
                    break;
            }

        }); //Datatables cell Click


        jQuery('#clock-in-to-last-work').click(function () {
            jQuery.ajax({
                url: "/api/?action=clockInToLast",
            }).done(function (da) {
                checkIfUserClockIn();
                jQuery('#msg-box').css('display', 'block');
                jQuery('#clockInMessage').text(da);
            });
        });

        jQuery('#clock-in-btn').click(function () {

            var work_id = jQuery('#work-order-input').val();
            var work_type_id = jQuery("input[name='work-type']:checked").val();
            var extra_work_type_id = jQuery("input[name='extra-work-type']:checked").val();

            var apiURL = "/api/?action=userClockInAndOut&wonum=" + work_id + "&wtid=" + work_type_id +
                "&extrawt=" + extra_work_type_id;
            jQuery.ajax({
                url: apiURL,
            }).done(function (da) {
                jQuery('#msg-box').css('display', 'block');
                jQuery('#clockInMessage').text(da);
                displayMenu();
                checkIfUserClockIn();
            });
        });

        // document ready end
    });




    function getCurrentBinLocation() {

        var url = "/api/?action=getCurrentBinLocation&wonum=<?php echo $work_order_number; ?>";
        jQuery.ajax({
            url: "/api/?action=getCurrentBinLocation&wonum=<?php echo $work_order_number; ?>",
        }).done(function (da) {
            var data = JSON.parse(da);

            if (data.total == 0) {
                jQuery('#currentBinLocation').text("No Location. Maybe still in the warehouse.");
            } else {
                jQuery('#currentBinLocation').text(data.location);
            }
        });
    }


    function getAssemblyDrawingFromSolidWork(cell) {
        var url = "https://swpdm001.ad.vanrx.com/SOLIDWORKSPDM/File/Search/Vanrx_Vault/?keyword=" + cell;

        window.open(url);
    }

    function workOrderClick(cell, isClockIn) {

        var isUserClockIn;

        document.getElementById('loading-modal').style.display = 'block';


        if (!isClockIn.isClockIn) {
            // if user clock in

            // empty work type list
            jQuery('#typeOfWorkList').empty();
            jQuery.ajax({
                url: "/api/?action=getWorkTypeList",
            }).done(function (da) {
                var data = JSON.parse(da);

                for (i = 0; i < data.length; i++) {
                    if (i == 0) {
                        var inputOption = '<input class="w3-radio" type="radio" name="work-type" value="' +
                            data[i]
                            .id + '" checked><label class=" w3-margin-right">' + data[i].work_type + '</label>';
                    } else {
                        var inputOption = '<input class="w3-radio" type="radio" name="work-type" value="' +
                            data[i]
                            .id + '"><label class="w3-margin-right">' + data[i].work_type + '</label>';
                    }

                    jQuery('#typeOfWorkList').append(inputOption);

                }
            }); // End ajax
            // +++++++++++++++++++++++++


            // empty work type list
            jQuery('#extraWorkTypeList').empty();
            jQuery.ajax({
                url: "/api/?action=extraWorkTypeList",
            }).done(function (da) {
                var data = JSON.parse(da);
                for (i = 0; i < data.length; i++) {
                    if (i == 0) {
                        var inputOption =
                            '<input class="w3-radio" type="radio" name="extra-work-type" value="' + data[i]
                            .id + '" checked><label class=" w3-margin-right">' + data[i].extra_work_type +
                            '</label>';
                    } else {
                        var inputOption =
                            '<input class="w3-radio" type="radio" name="extra-work-type" value="' + data[i]
                            .id + '"><label class="w3-margin-right">' + data[i].extra_work_type + '</label>';
                    }

                    jQuery('#extraWorkTypeList').append(inputOption);

                }
                document.getElementById('loading-modal').style.display = 'none';
                document.getElementById('work-order-modal').style.display = 'block';
                jQuery('#clock-in-btn').text('Clock In')
            }); // End ajax
        } else {
            // if user NOT clock in
                document.getElementById('loading-modal').style.display = 'none';
                document.getElementById('work-order-modal').style.display = 'block';
                jQuery('#clock-in-btn').text("Clock Out").removeClass('w3-border-green').addClass('w3-border-red');
        }

        // change work order history URL
        var newUrl = '/scan-qr-code-work-order/?wonum=' + cell;
        jQuery('#work-information-link').attr("href", newUrl);
        // change BOM btn URL
        var bomPageUrl = '/work-orders/page-upload-check-bom/?wonum=' + cell;
        jQuery('#BOMPageUrl').attr("href", bomPageUrl);

        // change BOM btn URL
        var fulfillShortagePageUrl = '/work-orders/page-fulfill-shortage/?wonum=' + cell;
        jQuery('#fulfillShortagePageUrl').attr("href", fulfillShortagePageUrl);

        // +++++++++++++++++++++++++


        // Update e-drawing Link

        // Get clock in user list
        var ajaxURL = "/api/?action=getWorkOrderHistory&wonum=" + cell;
        jQuery.ajax({
            url: ajaxURL,
        }).done(function (da) {

            jQuery("#clock-in-users").empty()
            var daJSON = JSON.parse(da);

            var i;
            var daLen = daJSON.length;
            var userLoginGroup = new Array();
            if (daLen > 0) {
                // if user found in clock in history
                for (i = 0; i < daLen; i++) {
                    if (!userLoginGroup.includes(daJSON[i].display_name)) {
                        userLoginGroup.push(daJSON[i].display_name);
                    }
                }
                var x;
                for (x = 0; x < userLoginGroup.length; x++) {
                    var r = jQuery(
                        '<input class="w3-margin-top w3-margin-right  w3-button" type="button" value="' +
                        userLoginGroup[x] + '"/>');
                    jQuery("#clock-in-users").append(r);
                }
            } else {
                var r = jQuery(
                    '<input class="w3-margin-top w3-margin-right w3-button" type="button" value="No Clock In History"/>'
                );
                jQuery("#clock-in-users").append(r);
            }
        }); // End Get clock in user list

        // get bin-location
        var ajaxURL = "/api/?action=getCurrentBinLocation&wonum=" + cell;
        jQuery.ajax({
            url: ajaxURL,
        }).done(function (da) {


            jQuery("#bin-location").empty()
            var daJSON = JSON.parse(da);
            if (daJSON.total == 1) {
                var r = jQuery(
                    '<input class="w3-margin-top w3-margin-right  w3-button" type="button" value="' +
                    daJSON.location + '"/>');
                jQuery("#bin-location").append(r);
            } else {
                var r = jQuery(
                    '<input class="w3-margin-top w3-margin-right w3-button" type="button" value="No Location"/>'
                );
                jQuery("#bin-location").append(r);
            }
        }); // End get bin-location

        jQuery('#work-order-input').val(cell);
        jQuery("#workNumDisplay").text(cell);

    }
    // End workOrderClick

    function displayMenu() {
        document.getElementById('work-order-modal').style.display = 'none';
    }
</script>

<?php
  }
}
get_footer();