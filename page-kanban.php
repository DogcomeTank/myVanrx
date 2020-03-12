<?php
/* Template Name: Page - Kanban */ 

function enqueue_qr_page() {
	wp_enqueue_script('create_qr_code_js', get_stylesheet_directory_uri() .'/assets/js/qrcodejs/qrcode.js', array(), false, false);
}
add_action( 'wp_enqueue_scripts', 'enqueue_qr_page' );
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

    $kanban_id = $title = $description = "";
    $kanban_id = test_input($_GET['kanbanId']);
    $title = test_input($_GET['title']);
    $description = test_input($_GET['description']);

?>

<div id="messageDiv" class="w3-panel w3-pale-green w3-leftbar w3-rightbar w3-border-green" style="display: none;">
    <h6 class="w3-center" id="message"></h6>
</div>

<div class="w3-margin w3-padding-bottom">
    <form class="w3-container w3-light-grey" style="max-width: 600px; margin: auto;">
        <h3 class="w3-margin-top">Kanban Item Order Form</h3>

        <label>Kanban ID</label>
        <input id="kanbanIdInput" class="w3-input w3-border-0" type="number" required>

        <label>IPN</label>
        <input id="ipnInput" class="w3-input w3-border-0" type="text" required>

        <label>Title</label>
        <input id="titleInput" class="w3-input w3-border-0" type="text" required>

        <label>Description</label>
        <input id="descriptionInput" class="w3-input w3-border-0" type="text">

        <label>Email Send To</label>
        <input id="emailInput" class="w3-input w3-border-0" type="email" required>

        <button id="emailFormBtn" class="w3-green w3-round w3-margin-bottom">Email Warehouse to Order</button>
        <button id="createQRcode" class="w3-green w3-round w3-margin-bottom">Create QR code</button>
    </form>

</div>

<!-- The Modal -->
<div id="id01" class="w3-modal">
  <div class="w3-modal-content">
    <div class="w3-container">
        <span onclick="document.getElementById('id01').style.display='none'"
        class="w3-button w3-display-topright">&times;</span>
        <div id="kanbanQRCodePrint">
            <div style="margin:auto; width:auto;" id="kanbanQRDiv"></div>
            <h5 style="margin: auto;">ID: <span id="qrDisplayIPN"></span></h5>
            <p style="margin: auto;" id="qrDisplayTitle"></p>
        </div>
        <button id="printKanbanQRbtn">Print</button>
    </div>
  </div>
</div>



<div class="" style="margin:auto;">
    <table id="kanban-datatables" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>IPN</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>IPN</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </tfoot>
    </table>
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
    var qrcode = new QRCode(document.getElementById("kanbanQRDiv"), {});
    jQuery(document).ready(function () {

        <?php if($kanban_id !== ""){ ?>
        getKanbanInfoById(<?php echo $kanban_id; ?>);
        <?php } ?>

        jQuery('#titleInput').val("<?php echo $title; ?>");
        jQuery('#descriptionInput').val("<?php echo $description; ?>");
        jQuery('#emailInput').val("sliu@vanrx.com");


        var table = jQuery('#kanban-datatables').DataTable({
            "ajax": "/api/?action=getKanbanList",
            "order": [
                [0, "desc"]
            ],
            "pageLength": 25,
            "scrollX": true
        });

        jQuery('#kanban-datatables').on('click', 'td', function () {
            var isUserClockIn = isClockin();

            var column_id = table.column(this).index();
            var cell_data = table.cell(this).data();

            switch (column_id) {
                case 0:
                    // code block
                    getKanbanInfoById(cell_data);
                    break;
                case 1:
                    // work order number
                    // log(cell_data);
                    break;
                case 2:
                    // Assembly id, open Solid Work
                    // getAssemblyDrawingFromSolidWork(cell_data);
                    break;
            }

        }); //Datatables cell Click

        jQuery('#createQRcode').click(function (e) {
            e.preventDefault();
            var kanbanId = jQuery('#kanbanIdInput').val();
            if(kanbanId !== ''){
                var linkForQR = "<?php echo esc_url( home_url( '/' )); ?>kanban/?kanbanId="+kanbanId;
                var ipn = jQuery('#ipnInput').val();
                var title = jQuery('#titleInput').val();
                document.getElementById('id01').style.display='block';
                qrcode.makeCode(linkForQR)
                jQuery('#qrDisplayIPN').text(ipn);
                jQuery('#qrDisplayTitle').text(title);
            }else{
                alert("please Select Item ID.");
            }
            
        });

        jQuery("#printKanbanQRbtn").click(function(e){
            e.preventDefault();
            printKanbanQRDiv();

        });

        jQuery('#emailFormBtn').click(function (e) {
            e.preventDefault();
            var id = jQuery('#kanbanIdInput').val();
            var title = jQuery('#titleInput').val();
            var ipn = jQuery('#ipnInput').val();
            var description = jQuery('#descriptionInput').val();
            var email = jQuery('#emailInput').val();

            var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
            var data = {
                id: id,
                title: title,
                ipn: ipn,
                description: description,
                email: email,
                action: "kanban_email",
            };
            jQuery.ajax({
                url: ajax_url,
                type: "POST",
                data: data,
            }).done(function (da) {
                log(da);
                if (da) {
                    jQuery('#message').text('Email Sent.')
                    jQuery('#titleInput').val("");
                    jQuery('#descriptionInput').val("");
                    jQuery('#emailInput').val("");
                    jQuery('#messageDiv').css('display', 'block');
                    jQuery('html, body').animate({
                        scrollTop: jQuery("#site-detail").offset().top
                    }, 500);
                }
            }).fail(function (e) {
                alert("error: " + e);
            });
        });
    });

    function getKanbanInfoById(id) {
        jQuery.ajax({
            url: '/api/?action=getKanbanInfoById&kanbanId=' + id,
            // async: true,
        }).done(function (da) {
            var jsonDa = JSON.parse(da);
            jQuery('#kanbanIdInput').val(jsonDa[0].id);
            jQuery('#ipnInput').val(jsonDa[0].ipn);
            jQuery('#titleInput').val(jsonDa[0].title);
            jQuery('#descriptionInput').val(jsonDa[0].description);
            var email = jQuery('#emailInput').val();
        })
    }

    function printKanbanQRDiv() {
        var prtContent = document.getElementById("kanbanQRCodePrint");
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
</script>



<?php
}

get_footer();