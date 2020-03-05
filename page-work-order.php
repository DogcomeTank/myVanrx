<?php
/* Template Name: Work Order */ 
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


<div style="display: none;" id="msg-box" class="w3-panel w3-pale-green w3-bottombar w3-border-green w3-border">
    <p id="clockInMessage"></p>
</div>
<div class="w3-container clock-in-to-last-work-order-btn w3-margin-bottom"><button
        class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge" id="clock-in-to-last-work">Clock In To Last
        Work Order</button></div>

<div class="" style="margin:auto;">
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Work Order</th>
                <th>Product</th>
                <th>Name</th>
                <th>SYS Number</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Work Order</th>
                <th>Product</th>
                <th>Name</th>
                <th>SYS Number</th>
            </tr>
        </tfoot>
    </table>
</div>


<!-- The Modal -->


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



<!-- when work order click -->
<div id="work-order-modal" class="w3-modal">
    <div class="w3-modal-content">
        <div class="w3-container w3-padding">
            <span onclick="displayMenu()" class="w3-button w3-display-topright">&times;</span>

            <!-- form -->
            <h2 class="w3-padding">Work Order: <span id="workNumDisplay"></span></h2>
            <input style="display: none;" class="w3-input" id="work-order-input" name="work-order-input" type="text">

            <div class="w3-card-4 w3-margin-bottom w3-padding">
                <h6>Type Of Work</h6>
                <div id="typeOfWorkList" class="w3-margin-top w3-margin-bottom"></div>
                <h6>Exta Work Type</h6>
                <div id="extraWorkTypeList" class="w3-margin-top w3-margin-bottom"></div>
                <button id="clock-in-btn"
                    class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge w3-margin-bottom w3-margin-right">Clock
                    In</button>
            </div>

            <div class="w3-card-4 w3-margin-bottom w3-padding">
                <h6>Useful Links</h6>

                <a id="work-information-link" href="/scan-qr-code-work-order/"><button id="work-order-history-btn"
                        class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge w3-margin-bottom w3-margin-right">Work
                        Order Details</button></a>
                <a id="BOMPageUrl" href="/work-orders/page-upload-check-bom/"><button id="shortage-list-btn"
                        class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge w3-margin-bottom w3-margin-right">BOM</button></a>
                <a id="fulfillShortagePageUrl" href="/work-orders/page-fulfill-shortage/"><button id="shortage-list-btn"
                        class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge w3-margin-bottom w3-margin-right">Fulfill
                        Shortage</button></a>
                <button id="task-btn"
                    class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge w3-margin-bottom w3-margin-right">Tasks</button>
            </div>

            <div class="w3-row">
                <div class="w3-col s12 m6">
                    <div class="w3-card-4 w3-padding w3-margin-bottom">
                        <h3 class="w3-margin-top w3-margin-bottom">Clock in Users</h3>
                        <div id="clock-in-users" class="w3-margin-top w3-margin-bottom">
                        </div>
                    </div>
                </div>
                <div class="w3-col s12 m6">
                    <div class="w3-card-4 w3-padding w3-margin-bottom">
                        <h3 class="w3-margin-top w3-margin-bottom">Bin Location</h3>
                        <div id="bin-location" class="w3-margin-top w3-margin-bottom">
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<!-- end when work order click -->




<script>
    jQuery(document).ready(function () {
        // +++++++++++++++++++++
        console.log(isClockin());
        var isUserClockIn = isClockin();
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


        if (isClockIn.isClockIn) {
            // if user NOT clock in

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
                jQuery('#clock-in-btn').text('Clock Out')
            }); // End ajax
        } else {
            // if user clock in
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