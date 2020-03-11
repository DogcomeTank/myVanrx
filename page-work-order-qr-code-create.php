<?php
/* Template Name: Page - Create QR Code */ 
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


$the_action = $_GET['action'];
$work_order_id = $_GET['woid'];
?>


<div class="w3-row w3-card-4 w3-margin">
    <h3 class="w3-center w3-padding-top">Work Order QR Code</h3>

    <div class="w3-third">
        <form class="w3-container w3-margin w3-padding qr-form w3-card-4">

            <label>Work Order ID</label>
            <input id="work-id" class="w3-input" type="text" required>

            <label>Work Order Name</label>
            <input id="work-label" class="w3-input" type="text" required>

            <button class="submit-btn w3-btn w3-padding w3-margin-top w3-green">Print QR Code</button>
        </form>

    </div>



    <div class="w3-container w3-twothird w3-padding">
        <div id="qr-code-div" style="padding: 18px;">

            <div style="float: left; clear: both;">
                <div id="qrcode"></div>
            </div>

            <div style="width: 40%; float: left; padding: 28px;">
                <h6>Work Order ID</h6>
                <h4 id="displayID">1</h4>
                <h6>Name:</h6>
                <h4 id="displayName" class="text-center">NA</h4>
            </div>
        </br>
        </div>
    </div>
</div>

<div class="w3-row w3-card-4 w3-margin">
<h3 class="w3-center w3-padding-top">Kanban QR Code</h3>

    <div class="w3-third">
        <form class="qr-kanban-form w3-container w3-margin w3-padding w3-card-4">

            <label>Title</label>
            <input id="work-id" class="w3-input" type="text" value="Kanban-50011" required>

            <label>Description</label>
            <input id="work-label" class="w3-input" type="text" required>

            <button class="submit-btn w3-btn w3-padding w3-margin-top w3-green">Print QR Code</button>
        </form>
    </div>
    <div class="w3-container w3-twothird w3-padding">
        <div id="qr-kanban-div" style="padding: 18px;">

            <div style="float: left; clear: both;">
                <div id="qrcode-kanban"></div>
            </div>

            <div style="width: 40%; float: left; padding: 28px;">
                <h6>Item IPN</h6>
                <h4 id="displayID">1</h4>
                <h6>Description:</h6>
                <h4 id="displayName" class="text-center">NA</h4>
            </div>
        </br>
        </div>
    </div>

</div>

<!-- Information storage -->
<input id="text" type="text" value="<?php echo esc_url( home_url( '/' ).'time-clock/?action=login&woid=2' ); ?>"
    style="width:80%; display:none;" disabled><br>




<script>
    jQuery(document).ready(function () {
        makeCode();

        jQuery("#text").on("blur", function () {
            makeCode();
        }).on("keydown", function (e) {
            if (e.keyCode == 13) {
                makeCode();
            }
        }); //#text on end

        jQuery(".qr-form").submit(function (e) {
            e.preventDefault();

            //Generate QR code
            printQRButton();

        }); //.qr-form end

        // input on change
        jQuery("#work-id").keyup(function (elem) {
            var text = jQuery(this).val().trim().split(" ").join("");

            changeDisplayText("displayID", text);
            printQR();
        });
        jQuery("#work-label").keyup(function (elem) {
            var text = jQuery(this).val();
            changeDisplayText("displayName", text);
            printQR();
        })

    }); //Doc ready end

    function printQR() {

        var QrWorkId = document.getElementById("work-id");
        var QrWorkName = document.getElementById("work-label");
        var linkForQR = "<?php echo esc_url( home_url( '/' )); ?>scan-qr-code-work-order/?wonum=";

        if (!QrWorkId.value) {
            QrWorkId.focus();
            return;
        }
        var workID = jQuery("#work-id").val().trim().split(" ").join("");
        linkForQR = linkForQR + workID;

        qrcode.makeCode(linkForQR);
    }

    function printQRButton() {
        printSelectedDiv();
    }

    function changeDisplayText(d, t) {
        document.getElementById(d).innerHTML = t;
    }

    var qrcode = new QRCode(document.getElementById("qrcode"), {

    });

    function makeCode() {
        var elText = document.getElementById("text");

        if (!elText.value) {
            alert("Input a text");
            elText.focus();
            return;
        }
        qrcode.makeCode(elText.value);
    }



    function printSelectedDiv() {
        var prtContent = document.getElementById("qr-code-div");
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