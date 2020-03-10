<?php
/* Template Name: Page - Vanrx Tasks */ 
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

?>

<div style="padding-bottom: 28px;">
    <div style="max-width: 600px; margin: auto;" id="newTaskAdded"></div>
    <div class="w3-card-4" style="max-width: 600px; margin: auto;">


        <div class="w3-container w3-green">
            <h2>Add New Task</h2>
        </div>

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


<script>
var log = console.log;
    jQuery("#addNewTaskForm").submit(function (e) {
        e.preventDefault();
        var taskDataForm = jQuery("#addNewTaskForm");
        var jsonData = getFormData(taskDataForm);

        var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";

        var data = {
            taskData: jsonData,
            action: "task_upload",
        };

        postData(ajax_url, data)
            .then((data) => {
                log(data);
                jQuery("<h3>Task Added.</h3>").appendTo("#newTaskAdded");
            });
    });
    async function postData(url = '', data = {}) {
        
        const response = await ajaxPostReturnData(data);
        return await response; // parses JSON response into native JavaScript objects
    }

    function ajaxPostReturnData(postData){
        var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
        // functions.php
        var finalData;
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: postData,
            dataType: 'json',
            async: false
        }).done(function (da) {
            finalData =  da;
        }).fail(function (e) {
            finalData = "error: " + e;
        });
        return finalData;
    }

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