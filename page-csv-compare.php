<?php
/* Template Name: Page - CSV Data Compare */ 
function page_csv_compare() {
        wp_enqueue_script('page-csv-compare-js', get_stylesheet_directory_uri() .'/js/page-csv-compare.js', array(), false, false);
        wp_enqueue_script('sortable-prettify-js', get_stylesheet_directory_uri() .'/inc/Sortable-master/Sortable.js', array(), false, false);
        wp_enqueue_style ( 'page-csv-compare-style', get_stylesheet_directory_uri() . '/css/page-csv-compare.css' );
        wp_enqueue_style ( 'animate-css-style', get_stylesheet_directory_uri() . '/assets/css/animate.css' );
}
add_action( 'wp_enqueue_scripts', 'page_csv_compare' );


get_header();
?>



<div id="dataInputDiv">

    <div class="w3-panel w3-pale-red w3-leftbar w3-border w3-border-red" style="display:none;" id="ErrorMsg">
    </div>
    <div class="w3-row">
        <div class="w3-col s6">
            <!-- csv drop zone -->
            <div class="w3-card-4 w3-margin w3-padding">
                <h4>Drag and Drop Your CSV File Here</h4>
                <input class="fileInput w3-margin-bottom" type="file" onchange=read(this)>
                <div class="output"></div>
                <!-- End csv drop zone -->
            </div>
        </div>
        <div class="w3-col s6">
            <div class="w3-card-4 w3-margin w3-padding">
                <h4>Copy&Past From EXCEL</h4>
                <textarea id="csv" rows="3" spellcheck="false"></textarea>
            </div>
        </div>
    </div>
    <!-- select display column -->
    <div class="displayColumnSelection w3-margin">
        <div class="w3-padding w3-card-4">
            <h4>Select Display Column</h4>
            <form id="displayColumnSelectionForm">
            </form>
        </div>
    </div>
    <!-- Select compare column -->
    <div class="compareColumnSelection w3-margin">
        <div class="w3-padding w3-card-4">
            <h4>Select Column To Compare</h4>
            <form id="compareColumnSelection">
            </form>
        </div>
    </div>

    <div class="w3-container btnDiv" style="display: none;">
        <div class="csvInputDiv w3-padding-top">
            <button class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge" id="compareBtn">Compare</button>
        </div>
    </div>

    <!-- Display CSV in Table -->
    <div class="w3-container tableDisplayDiv w3-margin-top" style="overflow-x: scroll;">
        <table class="w3-table-all w3-tiny" id='csvDisplayTable'>

        </table>
    </div>
</div>

<!-- Section 2 -->

<div class="dataCompareDiv" id="dataCompareDiv" style="display: none;">
    <div class="w3-container" id="compareDiv">
        <div class="w3-row">
            <div class="w3-col s12 m4 w3-margin-top">
                <div class="w3-card-4">
                    <table class="w3-table-all w3-tiny" id="leftPanelTable">

                    </table>
                </div>
            </div>

            <div class="w3-col s12 m4 w3-margin-top check-button-div">

                <div class="w3-card-4 w3-margin-left w3-margin-right">
                <div class="w3-padding">
                    <h5>Total Item: <span id="dataLength"></span></h5>
                    <form id="checkData">
                        <input id="compareInputData" type="text">
                        <button class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge"
                            id="compare">Compare</button>
                    </form>
                    </div>
                </div>
            </div>

            <div class="w3-col s12 m4 w3-margin-top">
                <div class="w3-card-4">
                <table class="w3-table-all w3-tiny" id="rightPanelTable">

</table>
                </div>
            </div>
        </div>
    </div>
</div>




<?php

get_footer();