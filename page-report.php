<?php
/* Template Name:Page - Report*/ 


function report_page_scripts() {

    wp_enqueue_script ( 'chartjs-script', get_stylesheet_directory_uri() . '/inc/Chart.js/chart.js' );
    wp_enqueue_style ( 'chartjs-style', get_stylesheet_directory_uri() . '/inc/Chart.js/chart.css' );

}
add_action( 'wp_enqueue_scripts', 'report_page_scripts' );

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

  // IF work order found, show all
?>
<div style="width: 100%;">
    <h1 style="text-align: center;">Reports</h1>
</div>


<div class="w3-row">

    <div class="w3-col s12">
        <div class="w3-margin w3-card-4 w3-padding">
            <h4 class="w3-center">System List</h4>
            <p class="w3-center">Select System to See Report</p>

            <div id="sysListDiv">
                <label for="opt1" class="w3-margin-right">
                    <input type="radio" name="sysNumList" id="opt1" class=" w3-marin-right" />Adobe
                </label>
            </div>
            <button id="sysSelectBtn"
                class="w3-margin w3-btn w3-white w3-border w3-border-green w3-round-xlarge">Select</button>



        </div>
    </div> <!-- x -->

    <div class="w3-col s12 m6 l4">
        <div class="w3-margin w3-card-4 w3-padding">
            <h4 class="w3-center">Direct VS Not Direct</h4>
            <div class="w3-margin" style="max-width: 600px;">
                <canvas id="dir-vs-not-dir" width="400" height="400"></canvas>
            </div>
        </div>
    </div> <!-- x -->

    <div class="w3-col s12 m6 l4">
        <div class="w3-margin w3-card-4">
            <h4 class="w3-center">Work Type</h4>
            <div class="w3-margin" style="max-width: 600px;">
            <canvas id="workTypePieChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div><!-- x -->

    <div class="w3-col s12 m6 l4">
        <div class="w3-margin w3-card-4 w3-padding">
            <h4 class="w3-center">Progress Of the Build</h4>
            <div class="w3-margin" style="max-width: 600px;">
                <canvas id="build-progress" width="400" height="400"></canvas>
            </div>
        </div>
    </div> <!-- x -->



</div>


<script>
    var log = console.log;
    var colorArray = ['#3fc1c9', '#f38181', '#a8e6cf', '#ed6663', '#4f3961', '#beebe9', '#df7861',
                '#50bda1',
            '#ecce6d', '#364f6b'
        ];

    getSystemList();

    jQuery(document).ready(function () {

        //line chart
        jQuery('#dir-vs-not-dir').empty();
        var chartSubVSFinalAssembly = document.getElementById("dir-vs-not-dir").getContext('2d');
        var subVFinalChart = new Chart(chartSubVSFinalAssembly, {
            type: 'doughnut',
            data: {
                labels: ['Direct', 'Not Direct', ],
                datasets: [{
                    data: [10, 10],
                    backgroundColor: colorArray
                }]
            },
            options: {
                title: {
                    display: true,
                    text: '(sub-assembly+final assembly) vs (CO + NCR)'
                }
            }
        });
        // End First Line chart

        // Second chart
        var secondPieChart = document.getElementById("workTypePieChart")
        var workTypeChart = new Chart(secondPieChart, {
            type: 'pie',
            data: {
            labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
            datasets: [{
                label: "Population (millions)",
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                data: [2478,5267,734,784,433]
            }]
            },
            options: {
            title: {
                display: true,
                text: 'Predicted world population (millions) in 2050'
            }
            }
        });//End Second Chart


        showWorkProgress();

        jQuery('#sysSelectBtn').click(function () {

            var selectedSystem = jQuery('input[name="sysNumList"]:checked').val();

            // 1, 2 == direct
            var dirWorktp = [1,2];
            var InDirWorkTypeArr = [3,4];
            var dirWorktpJson = JSON.stringify(dirWorktp)
            var InDirWorkTypeArrJson = JSON.stringify(InDirWorkTypeArr)

            systemSelect(selectedSystem, dirWorktpJson, InDirWorkTypeArrJson, subVFinalChart);

        });

    }); //Doc Ready End

    function DirVSNonDivWorkUpdate(chart, newData){
            var dirH = newData.dir / 3600 ;
            var dirHtoFix = dirH.toFixed(2);
            var indirH = newData.indir / 3600 ;
            var indirHFix = indirH.toFixed(2);
            var newDataArr = [dirHtoFix,indirHFix];

            chart.data.datasets.forEach((dataset) => {
                dataset.data = newDataArr;
            });

            chart.update();
    }




    function systemSelect(sysNum, DirWorkTypeArr, InDirWorkTypeArrJson, chart) {
        var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";

        var data = {
            sysNum: sysNum,
            DirWorkTypeArr: DirWorkTypeArr,
            InDirWorkTypeArrJson: InDirWorkTypeArrJson,
            action: "work_order_time_sum",
        };
        jQuery.ajax({
            url: ajax_url,
            type: "POST",
            data:data
        }).done(function (da) {
            var data = JSON.parse(da);
            DirVSNonDivWorkUpdate(chart, data);
        }).fail(function (e) {
            alert("error: " + e);
        });;

    } // End systemSelect()



    function showDirVSNonDivWork(data) {

        

    }//showDirVSNonDivWork()


    function showWorkProgress() {
        new Chart(document.getElementById("build-progress"), {
            type: 'line',
            data: {
                labels: ["02-1", "02-7", "02-15", "02-23", "02-30", "03-4", "03-13", "03-20", "03-27", "04-1",
                    "04-8"
                ],
                datasets: [{
                        data: [0, 3, 15, 38, 42, 44, 59, 68, 72, 73],
                        label: "Percentage Completion",
                        borderColor: "#3e95cd",
                        fill: false
                    },
                    {
                        data: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                        label: "Expectation",
                        borderColor: "#a8e6cf",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Data base on Technician estimate'
                }
            }
        });

    } // End showWorkProgress


    function getSystemList() {
        jQuery.ajax({
            url: '/api/?action=getSystemList'
        }).done(function (da) {
            var data = JSON.parse(da);
            jQuery('#sysListDiv').empty();
            for (i = 0; i < data.length; i++) {
                jQuery('<input class="w3-radio" type="radio" name="sysNumList" value="' + data[i].sys_number +
                    '"><label class="w3-margin-right">' + data[i].sys_number + '</label>').appendTo(
                    '#sysListDiv');
            }

        }).fail(function (e) {
            alert("error: " + e);
        });;
    }

    function getRandomColorArray() {
        var colorArray = ['#3fc1c9', '#df7861', '#a8e6cf', '#ed6663', '#4f3961', '#beebe9', '#364f6b',
            '#50bda1',
            '#ecce6d', '#ffd082'
        ];
        var randomColorArray = [];
        for (i = 0; i <= colorArray.length; i++) {
            randomColorArray.push(colorArray[Math.floor(Math.random() * colorArray.length)]);
        }
        return randomColorArray;
    }
</script>

<?php
  
}
get_footer();