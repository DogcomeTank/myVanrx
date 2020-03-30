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


<div class="w3-bar-item w3-button w3-margin" onclick="myAccFunc()">
  Useful Links <i class="fa fa-caret-down"></i></div>
<div class="w3-padding w3-margin-bottom w3-hide w3-white w3-card-4" id="usefulAcc">
    <ul class="w3-ul">
        <li>
            <a href="/work-orders/page-create-work-order/">Upload Work Orders</a>
        </li>
        <li>
            <a href="/qr-code">Create QR Code</a>
        </li>
        <li>
            <a href="/page-csv-data-compare">CSV Compare</a>
        </li>
        <li>
            <a href="/page-report">Reports</a>
        </li>
        <li>
            <a href="/sa25-build-tree">SA25 Build Tree</a>
        </li>
        <li>
            <a href="/kanban">Kanban</a>
        </li>
    </ul>
</div>

<div class="w3-bar-item w3-button w3-margin" onclick="calendarAccFunc()">
  Calendar Information <i class="fa fa-caret-down"></i></div>
<div class="w3-padding w3-margin-bottom w3-hide w3-white w3-card-4" id="calendarAcc">
    <ul class="w3-ul">
        <li>
            Today Events: <span id="todayEventInCalender"></span>
        </li>
        <li>
            Upcoming Events: <span id="totalEventInCalender"></span>
        </li>
        <li>
            Overdue Events: <span id="overdueEvents"></span>
        </li>
    </ul>
</div>

<div class="spacer-28px"></div>

<div class="w3-row-padding w3-section w3-stretch sl-margin-8">
    <div class="w3-col s12 m3 w3-margin-bottom sl-remove-padding">
        <h4 class="w3-center">Tasks</h4>
        <p class="w3-center"><a href="/add-task"><i class="fa fa-plus"></i> New Task</a></p>
        <ul class="w3-ul taskUL">

        </ul>
    </div>
    <div class="w3-col s12 m9 sl-remove-padding">
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

<div id="addNewTaskModal" class="w3-modal">
    <div style="max-width: 600px; margin: auto;" id="newTaskAdded"></div>
    <div class="w3-modal-content w3-card-4" style="max-width: 600px; margin: auto;">
        <header class="w3-container w3-green">
            <span onclick="document.getElementById('eventClickModal').style.display='none'"
                class="w3-button w3-display-topright">&times;</span>
                <h2>Add New Task</h2>
        </header>

        <form id="addNewTaskForm" class="w3-container">
            <label>Work Order Number (Optional)</label>
            <input class="w3-input" name="work_order_id" type="text">
            <label>Title</label>
            <input class="w3-input" name="title" type="text" required>
            <label>Description (Optional)</label>
            <input class="w3-input" name="description" type="text">
            <label>Start Date (Optional)</label>
            <input class="w3-input" name="start" type="date">
            <label>End Date (Optional)</label>
            <input class="w3-input" name="end" type="date"></br>
            <label for="urgency">Urgency</label>
            <select class="w3-input" id="urgency" name="urgency">
                <option value="1">Normal</option>
                <option value="2">Urgent</option>
                <option value="3">Important</option>
            </select></br>
            <input type="submit" class="w3-botton">
        </form>
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
                    class="w3-btn w3-white w3-border w3-border-red w3-round-xlarge" id="closeTaskBtn">Close
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
                    <button id="taskUpdateBtn"
                        class="w3-btn w3-margin w3-white w3-border w3-border-green w3-round-xlarge">Update</button>
                </p>
            </form>
        </div>

    </div>
</div>



<script>
    var log = console.log;
    document.addEventListener('DOMContentLoaded', function () {
        var currentTime = new Date();
        currentTime.setHours(0);
        currentTime.setMinutes(0);
        currentTime.setSeconds(0, 0);
        
        var calendarEl = document.getElementById('calendar');
        var eventList = getTask();
        var totalInEventList = eventList.length;
        var totalScheduleEvent = 0;
        var overdueEvents = 0;
        var todayEvents = 0;


        displayTaskOnLeftPanel(eventList);


        for (i = 0; i < totalInEventList; i++) {
			var eventDate = new Date(eventList[i].start);
            // Total future schedule events
            //alert(eventDate);
            if (eventList[i].start != null && currentTime < eventDate) {
                if(currentTime.getFullYear() == eventDate.getFullYear() && currentTime.getMonth() == eventDate.getMonth() && currentTime.getDate() == eventDate.getDate() ){
                }else{
                    totalScheduleEvent++;
                }
                
            }

            if (eventList[i].start != null && currentTime.getFullYear() == eventDate.getFullYear() && currentTime.getMonth() == eventDate.getMonth() && currentTime.getDate() == eventDate.getDate() ) {
                todayEvents++;
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
            if(eventList[i].start != null && currentTime > eventDate ){
                if (currentTime.getFullYear() == eventDate.getFullYear() && currentTime.getMonth() == eventDate.getMonth() && currentTime.getDate() == eventDate.getDate()) {
                    
                }else{
                    overdueEvents++;
                }
            }
          // jQuery('#overdueEvents').empty().text(overdueEvents);
            // log(eventDate);
        }

        jQuery('#taskUpdateBtn').click(function (e) {
            e.preventDefault();
            let taskDataForm = jQuery("#updateTaskForm");
            let jsonData = getFormData(taskDataForm);
            let taskId = jQuery('#taskIDInModal').text();
            var data = {
                taskData: jsonData,
                taskId: taskId,
                action: "task_update",
            };
            ajaxUpdateTask(data);
            
            // log(jsonData);

        });

        jQuery('#closeTaskBtn').click(function (e) {
            e.preventDefault();
            let taskToClose = jQuery('#taskIDInModal').text();
            closeTaskById(taskToClose);
        });

        jQuery('#totalEventInCalender').empty().text(totalScheduleEvent);
        jQuery('#todayEventInCalender').empty().text(todayEvents);
        jQuery('#overdueEvents').empty().text(overdueEvents);

        function ajaxUpdateTask(postData) {
            var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";

            jQuery.ajax({
                url: ajax_url,
                type: "POST",
                data: postData
            }).done(function (da) {
                location.reload();
            }).fail(function (e) {
                alert("error: " + e);
            });;
        }
        var calendar = new FullCalendar.Calendar(calendarEl, {
            // plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            events: eventList,
            defaultDate: currentTime,
            navLinks: true, // can click day/week names to navigate views
            // businessHours: true, // display business hours
            editable: false,
            eventClick: function (info) {
                showTaskInfoInModal(info.event.id);
            }

        });

        calendar.render();


    }); //Doc ready

    
    function myAccFunc() {
        var x = document.getElementById("usefulAcc");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else { 
            x.className = x.className.replace(" w3-show", "");
        }
    }

    function calendarAccFunc() {
        var x = document.getElementById("calendarAcc");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else { 
            x.className = x.className.replace(" w3-show", "");
        }
    }
    
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
            log(da[0].start);
            if (da[0].start !== null) {
                let startDate = new Date(da[0].start);
                startDate = formatDate(startDate);
                jQuery('#start').val(startDate);
            }else{
                jQuery('#start').val(0);
                
            }

            if (da[0].end !== null) {
                let endDate = new Date(da[0].end);
                endDate = formatDate(endDate);
                jQuery('#end').val(endDate);
            }else{
                jQuery('#end').val(0);
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
        if (confirm('Do you want to close task?')) {
            jQuery.ajax({
                url: "/api/?action=closeTaskById&taskID=" + id,
            }).done(function (da) {
                location.reload();
            }); // End ajax
        } else {
            return false;
        }

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