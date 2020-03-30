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
    <!-- csv drop zone -->
    <div style="display:none;" id="ErrorMsg"></div>
    <div class="csvFileInput w3-margin w3-container">
        <h4>Drag and Drop Your CSV File Here</h4>
        <input class="fileInput" type="file" onchange=read(this)>
        <div class="output"></div>
    </div>
    <!-- End csv drop zone -->

    <div class="w3-container">
        <h4 class="w3-padding">Past your data on the input area</h4>
        <div class="csvInputDiv w3-padding">
            <textarea id="csv" rows="18" spellcheck="false"></textarea>
            <!-- select display column -->
            <div class="displayColumnSelection">
                <form id="displayColumnSelectionForm">
                    <input id="milk1" class="w3-check" value="milk" type="checkbox" name="displayColumnCheckbox1">
                    <label>Male</label></br>
                    <input id="milk2" class="w3-check" value="milk2" type="checkbox" name="displayColumnCheckbox2">
                    <label>Male</label></br>
                    <input id="milk3" class="w3-check" value="milk3" type="checkbox" name="displayColumnCheckbox3">
                    <label>Male</label></br>
                </form>
                
            </div>
            <button class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge" id="convert">Convert</button>
        </div>
    </div>
</div>

<div class="dataCompareDiv" id="dataCompareDiv" style="display: none;">
    <div class="w3-container" id="compareDiv">
        <div class="w3-row">
            <div class="w3-col s12 m4 w3-margin-top">
                <div class="w3-card-4">
                    <table class="w3-table-all w3-tiny">
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Points</th>
                        </tr>
                        <tr>
                            <td>Jill</td>
                            <td>Smith</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>Eve</td>
                            <td>Jackson</td>
                            <td>94</td>
                        </tr>
                        <tr>
                            <td>Adam</td>
                            <td>Johnson</td>
                            <td>67</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="w3-col s12 m4 w3-padding check-button-div">

                <div class="w3-card-4 w3-padding">
                    <h5 class="w3-padding">Total Item: <span id="dataLength"></span></h5>
                    <form id="checkData">
                        <input type="text">
                        <button class="w3-btn w3-white w3-border w3-border-green w3-round-xlarge"
                            id="compare">Compare</button>
                    </form>
                </div>
            </div>

            <div class="w3-col s12 m4 w3-margin-top">
                <div class="w3-card-4">Output Data</div>
            </div>
        </div>
    </div>
</div>






<?php

get_footer();