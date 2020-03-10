<?php
/* Template Name: Page - Home */ 

// function home_page_scripts() {

//     wp_enqueue_script ( 'boostrap-script', get_stylesheet_directory_uri() . '/assets/bootstrap-4.0.0-dist/js/bootstrap.js' , array(), false, true);
//     wp_enqueue_style ( 'boostrap-style', get_stylesheet_directory_uri() . '/assets/bootstrap-4.0.0-dist/css/bootstrap.css' );
    
// }
// add_action( 'wp_enqueue_scripts', 'home_page_scripts' );

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
        <div>
            <h4>Total Scheduled Events: <span id="totalEventInCalender"></span></h4>
        </div>
        <div>
            <h4>Overdue Events: <span id="overdueEvents"></span></h4>
        </div>
        <div id='calendar'></div>
    </div>
</div>


<div id="loadingModal" class="w3-modal">
    <div class="w3-modal-content">
        <div class="w3-container w3-padding">
            <div class="loader w3-padding" style="margin:auto;"></div>
        </div>
    </div>
</div>


<div id="eventClickModal" class="w3-modal">
    <div class="w3-modal-content w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('eventClickModal').style.display='none'"
                class="w3-button w3-display-topright">&times;</span>
            <h2>Task Information</h2>
        </header>



        <div class="w3-container taskInfoDiv">
            <h5>Task ID: <span id="taskIDInModal"></span><button
                    class="w3-btn w3-margin w3-white w3-border w3-border-red w3-round-xlarge" id="closeTaskBtn">Close
                    Task</button></h5>
            <p>Created at: <span id="task_created_date"></span></p>
            <form id="updateTaskForm" class="w3-margin">
                <p>
                    <label>Title</label>
                    <input name="title" id="title" class="w3-input" type="text"></p>
                <p>
                    <label>Work Order</label>
                    <input name="work_order_id" id="work_order_id" class="w3-input" type="text"></p>
                <p>
                    <label>Description</label>
                    <input name="description" id="description" class="w3-input" type="text"></p>
                <p>
                    <label>Start</label>
                    <input name="start" id="start" class="w3-input" type="date" placeholder="yyyy-mm-dd"></p>
                <p>
                    <label>End</label>
                    <input name="end" id="end" class="w3-input" type="date" placeholder="yyyy-mm-dd"></p>
                <p>
                    <label>Urgency</label>
                    <select name="urgency" id="urgency" class="w3-input" id="urgency" name="urgency">
                        <option value="1">Normal</option>
                        <option value="2">Urgent</option>
                        <option value="3">Important</option>
                    </select></br></p>
                <p>
                    <button id="taskUpdateBtn" class="w3-btn w3-margin w3-white w3-border w3-border-green w3-round-xlarge">Update</button>
                </p>
            </form>
        </div>

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
        var overdueEvents = 0;


        displayTaskOnLeftPanel(eventList);


        for (i = 0; i < totalInEventList; i++) {
            var eventDate = new Date(eventList[i].start);
            // Total future schedule events
            if (eventList[i].start != null && currentTime < eventDate) {
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

            if (currentTime > eventDate) {
                overdueEvents++;
            }
            // log(eventDate);
        }

        jQuery('#taskUpdateBtn').click(function (e) {
            e.preventDefault();
            var taskDataForm = jQuery("#updateTaskForm");
            var jsonData = getFormData(taskDataForm);
            log(jsonData);

        });

        jQuery('#totalEventInCalender').empty().text(totalScheduleEvent);

        jQuery('#overdueEvents').empty().text(overdueEvents);

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
            eventClick: function (info) {
                showTaskInfoInModal(info.event.id);
            }

        });

        calendar.render();


    }); //Doc ready

    // +++++++++continue here +++++++++++
    function showTaskInfoInModal(id) {
        apiURL = 'api/?action=getTaskInfoByTaskId&taskID=' + id;
        jQuery.ajax({
            url: apiURL,
            dataType: 'json',
            async: false
        }).done(function (da) {
            jQuery('#loadingModal').css('display', 'block');
            jQuery('#taskIDInModal').empty().text(da[0].id);
            jQuery('#work_order_id').val(da[0].work_order_id);
            jQuery('#title').val(da[0].title);
            jQuery('#description').val(da[0].description);
            
            if(da[0].start !== null){
                let startDate =Date(da[0].start);
                startDate = formatDate(startDate);
                jQuery('#start').val(startDate);
            }
            
            if(da[0].end !== null){
                let endDate =Date(da[0].end);
                endDate = formatDate(endDate);
                jQuery('#end').val(endDate);
            }

            jQuery('#urgency').val(da[0].urgency);
            jQuery('#task_created_date').text(da[0].task_created_date);
            jQuery('#loadingModal').css('display', 'none');
            jQuery('#eventClickModal').css('display', 'block');
        }).fail(function (e) {
            alert("error: " + e);
        });
    }

    function leftTaskListClickEvent(id) {
        // document.getElementById('eventClickModal').style.display = 'block';
        showTaskInfoInModal(id);
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

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }

    function closeTaskById(id) {

        jQuery.ajax({
            url: "/api/?action=closeTaskById&taskID=" + id,
        }).done(function (da) {
            log(da);
        }); // End ajax
    }

    function displayTaskOnLeftPanel(data) {
        jQuery('.taskUL').empty();
        var totalData = data.length;
        var taskList = "";
        for (i = 0; i < totalData; i++) {
            if (data[i].start == null) {
                taskList +=
                    `<li>
                        <button value="${data[i].id}" class="w3-small w3-white w3-border w3-border-blue w3-round"  onclick="leftTaskListClickEvent(jQuery(this).val())">${data[i].title}</button></li>
                `;
            }
            // log(data[i]);
            // if(data[i].start)

        }
        jQuery('.taskUL').append(taskList);
    } //End displayTaskOnLeftPanel()

    function getFormData(form) {
        var unindexed_array = form.serializeArray();
        var indexed_array = {};

        jQuery.map(unindexed_array, function (n, i) {
            indexed_array[n['name']] = n['value'];
        });

        return indexed_array;
    }
</script>

<?php
}
get_footer(); ?>