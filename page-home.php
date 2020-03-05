<?php
/* Template Name: Page - Home */ 
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

    $last_month = date('Y-m-d H:m:s', strtotime("-1 months", strtotime("NOW"))); 

?>



<div class="w3-container w3-sand w3-padding w3-margin-bottom" id="home-useful-link">
    <h2>Useful Link</h2>
    <a style="color:white;" href="/work-orders/page-create-work-order/"><button class="w3-btn w3-round-xxlarge w3-green">Upload Work Orders</button></a>
    <a style="color:white;" href="/add-task/"><button class="w3-btn w3-round-xxlarge w3-green">Tasks</button></a>
    <a style="color:white;" href="/qr-code"><button class="w3-btn w3-round-xxlarge w3-green">Create QR Code</button></a>
    <a style="color:white;" href="/page-report"><button class="w3-btn w3-round-xxlarge w3-green">Reports</button></a>
</div>

<div id='calendar'></div>



<script>
    var log= console.log;
    document.addEventListener('DOMContentLoaded', function () {
        var currentTime = new Date();
        var calendarEl = document.getElementById('calendar');
        var eventList = getTask();
        var formatEvents = [];
        for(i=0; i<eventList.length; i++){
            if(eventList[i].urgency == 1){
                eventList[i].color = '#00b8a9';
                formatEvents.push(eventList[i]);
            }else if(eventList[i].urgency == 2){
                eventList[i].color = '#feb72b';
                formatEvents.push(eventList[i]);
            }else if(eventList[i].urgency == 3){
                eventList[i].color = '#f6416c';
                formatEvents.push(eventList[i]);
            }
           

        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            events:eventList,
            defaultDate: currentTime,
            navLinks: true, // can click day/week names to navigate views
            businessHours: true, // display business hours
            editable: false,

        });

        calendar.render();
    }); //Doc ready

    function getTask(){
        var finalData;
        jQuery.ajax({
            url: 'api/?action=getTaskList',
            dataType: 'json',
            async: false
        }).done(function (da) {
            finalData =  da;
        }).fail(function (e) {
            finalData = "error: " + e;
        });
        return finalData;
    }
    
</script>

<?php
}
get_footer(); ?>