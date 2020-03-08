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
    <a style="color:white;" href="/work-orders/page-create-work-order/"><button
            class="w3-btn w3-round-xxlarge w3-green">Upload Work Orders</button></a>
    <a style="color:white;" href="/add-task/"><button class="w3-btn w3-round-xxlarge w3-green">Tasks</button></a>
    <a style="color:white;" href="/qr-code"><button class="w3-btn w3-round-xxlarge w3-green">Create QR Code</button></a>
    <a style="color:white;" href="/page-report"><button class="w3-btn w3-round-xxlarge w3-green">Reports</button></a>
</div>

<div class="spacer-28px"></div>

<div class="w3-row-padding w3-section w3-stretch w3-margin">
    <div class="w3-col s12 m3 w3-border w3-margin-bottom">
    <h4 class="w3-center">Tasks</h4>
        <ul class="w3-ul taskUL">
            <li>
        <button class="w3-small w3-white w3-border w3-border-blue w3-round">Small</button></li>
            <li>
        <button class="w3-small w3-white w3-border w3-border-blue w3-round">Small</button></li>
            <li>
        <button class="w3-small w3-white w3-border w3-border-blue w3-round">Small</button></li>
        </ul>
    </div>
    <div class="w3-col s12 m9">
        <div><h4>Total Scheduled Events: <span id="totalEventInCalender"></span></h4></div>
        <div><h4>Over Due Events: <span id="overDueEvents"></span></h4></div>
        <div id='calendar'></div>
    </div>
</div>

<div id="eventClickModal" class="w3-modal">
    <div class="w3-modal-content w3-card-4">
      <header class="w3-container w3-teal"> 
        <span onclick="document.getElementById('eventClickModal').style.display='none'" 
        class="w3-button w3-display-topright">&times;</span>
        <h2>Modal Header</h2>
      </header>
      <div class="w3-container">
        <p>Some text..</p>
        <p>Some text..</p>
      </div>
      <footer class="w3-container w3-teal">
        <p>Modal Footer</p>
      </footer>
    </div>
  </div>





<script>
    var log = console.log;
    document.addEventListener('DOMContentLoaded', function () {
        var currentTime = new Date();
        var calendarEl = document.getElementById('calendar');
        var eventList = getTask();
        var totalInEventList = eventList.length;
        var totalScheduleEvent = 0;
        var overDueEvents = 0;
        
        // jQuery('#totalEventInCalender').empty().text(totalInEventList+1);

        displayTaskOnLeftPanel(eventList);


        for (i = 0; i < totalInEventList; i++) {
            // log(eventList[i].start);
            // Total future schedule events
            if(eventList[i].start != null && currentTime < eventDate){
                log(eventList[i].start)
                totalScheduleEvent++;
            }

            // Display color
            if (eventList[i].urgency == 1) {
                eventList[i].color = '#00b8a9';
            } else if (eventList[i].urgency == 2) {
                eventList[i].color = '#feb72b';
            } else if (eventList[i].urgency == 3) {
                eventList[i].color = '#f6416c';
            }

            // count over due events
            var eventDate = new Date(eventList[i].start);
            if(currentTime > eventDate){
                overDueEvents++;
            }
            // log(eventDate);


        }
        jQuery('#totalEventInCalender').empty().text(totalScheduleEvent);

        jQuery('#overDueEvents').empty().text(overDueEvents);

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            events: eventList,
            defaultDate: currentTime,
            navLinks: true, // can click day/week names to navigate views
            businessHours: true, // display business hours
            editable: false,
            eventClick: function(info){
                log(info.event.id);
            }

        });

        calendar.render();
    }); //Doc ready

    function leftTaskListClickEvent(data){
        log(data.value);
        document.getElementById('eventClickModal').style.display='block';
    }

    function getTask() {
        var finalData;
        jQuery.ajax({
            url: 'api/?action=getTaskList',
            dataType: 'json',
            async: false
        }).done(function (da) {
            finalData = da;
        }).fail(function (e) {
            finalData = "error: " + e;
        });
        return finalData;
    }
    function displayTaskOnLeftPanel(data){
        jQuery('.taskUL').empty();
        var totalData = data.length;
        var taskList ="";
        for(i=0; i < totalData; i++){
            // log(data[i]);
            // if(data[i].start)
            taskList += 
            `<li>
                <button value="${data[i].id}" class="w3-small w3-white w3-border w3-border-blue w3-round"  onclick="leftTaskListClickEvent(this)">${data[i].title}</button></li>
        `;
        }
        jQuery('.taskUL').append(taskList);
    }//End displayTaskOnLeftPanel()


</script>

<?php
}
get_footer(); ?>