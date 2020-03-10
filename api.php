<?php
/* Template Name: API */ 

if(!is_user_logged_in()){
    die ("Please login");
}else{
    global $current_user;
    $current_user = wp_get_current_user();
    $login_user_id = $current_user->ID;
}

// link check http://vanrxproduction.local/api/?action=userClockInAndOut&wonum=WO-000786

$the_action = $work_order_id = $work_type_id =$work_order_list = $bin_location = $workType =  $system_num = "";

$the_action = sanitize_text_field($_GET['action']);
$work_order_id = sanitize_text_field($_GET['woid']);
$work_order_number = sanitize_text_field($_GET['wonum']);
$work_type_id = sanitize_text_field($_GET['wtid']);
$workType = sanitize_text_field($_GET['workType']);
$system_num = sanitize_text_field($_GET['sysNum']);
$newBinLocation = sanitize_text_field($_GET['newLocation']);
$extra_work_type = sanitize_text_field($_GET['extrawt']);
$task_id = sanitize_text_field($_GET['taskID']);

if($work_order_id == "" && !$work_order_number == ""){
    $work_order_id =  getWorkOrderIdByWorkNumber($work_order_number);
}

switch ($the_action) {
    case "getLoginHistory":
        GetUserLoginHistory($login_user_id);
        break;
    case "getWorkOrderList":
        getWorkOrderList();
        break;
    case "getWorkOrderHistory":
        getWorkOrderHistory($work_order_number);
        break;
    case "getWorkOrderNumber":
        getWorkOrderNumber();
        break;
    case "userClockInAndOut":
        userClockInAndOut($work_order_id, $login_user_id, $work_type_id, $extra_work_type);
        break;
    case "clockInToLast":
        clockInToLast($login_user_id);
        break;
    case "getWorkTypeList":
        getWorkTypeList();
        break;
    case "getShortageList":
        getShortageList($work_order_id);
        break;
    case "isUserClockIn":
        isUserClockIn($login_user_id);
        break;
    case "getCurrentBinLocation":
        getCurrentBinLocation($work_order_id);
        break;
    case "getStorageLocation":
        getStorageLocation();
        break;
    case "changBinLocation":
        changBinLocation($newBinLocation, $work_order_id);
        break;
    case "getSystemList":
        getSystemList();
        break;
    case "getTaskList":
        getTaskList();
        break;
    case "getEdrawingByWorkNum":
        getEdrawingByWorkNum($work_order_number);
        break;
    case "extraWorkTypeList":
        extraWorkTypeList();
        break;
    case "insertAssembly":
        insertAssembly();
        break;
    case "getTaskInfoByTaskId":
        getTaskInfoByTaskId($task_id);
        break;
    case "closeTaskById":
        closeTaskById($task_id);
    break;
}

function getTaskInfoByTaskId($tid){
    global $wpdb;
    $sql_get_task_info = "
        SELECT *
        FROM `vanrx_tasks`
        WHERE id = ".$tid."
    ";
    $task_info = $wpdb->get_results($sql_get_task_info);
    print json_encode($task_info);
}

function closeTaskById($tid){
    global $wpdb;
    $where = ['id'=>$tid];
    $closeTaskResult = $wpdb->update('vanrx_tasks', array("status"=> 1 ), $where);
    print json_encode($closeTaskResult);
}

function insertAssembly(){
    global $wpdb;
    $sql_get_assembly_id = "
        SELECT assembly_id
        FROM `work_order`
    ";
    $total_assembly_insert = 0;
    $result = $wpdb->get_results($sql_get_assembly_id, 'ARRAY_A');

    foreach ($result as $key) {
        // print_r ($key['assembly_id'] );
        $assmbly_num = $key['assembly_id'];
        $sql_get_assembly_list_by_num = "
            SELECT *
            FROM `assembly_list`
            WHERE assembly_number = ".$assmbly_num."
        ";
        $if_assembly = $wpdb->get_results($sql_get_assembly_list_by_num);
        if(count($if_assembly) == 0){
            $sql_insert_assembly['assembly_number'] = $assmbly_num;
            $sql_insert_assembly['estimate_time'] = '3600';
            $res2 = $wpdb->insert('assembly_list', $sql_insert_assembly);
            $total_assembly_insert++;
        }
    }

    echo $total_assembly_insert;

}

function extraWorkTypeList(){
    global $wpdb;
    $sql_get_extra_work_type = "
        SELECT *
        FROM `work_order_type_extra`
    ";
    $result = $wpdb->get_results($sql_get_extra_work_type);
    print json_encode($result);
}

function getTaskList(){
    global $wpdb;
    $last_month = date('Y-m-d H:m:s', strtotime("-1 months", strtotime("NOW"))); 
    $sql_get_tasks = "
        SELECT *
        FROM `vanrx_tasks`
        WHERE status = 0
    ";
    $result = $wpdb->get_results($sql_get_tasks);
    print json_encode($result);
}

function getEdrawingByWorkNum($wn){
    global $wpdb;
    $sql_get_edrawing = "
        SELECT assembly_id FROM work_order 
        WHERE work_order_number = '".$wn."' LIMIT 1 
    ";
    $result = $wpdb->get_results($sql_get_edrawing);
    print json_encode($result);
}

// function getSystemWorkTotal($sys_n, $wt){
//     global $wpdb;
//     $sql_get_total_time = "
//         SELECT SUM( time_sheet.total_time) FROM `time_sheet`
//         INNER JOIN work_order
//         ON work_order.id = time_sheet.work_order_id
//         WHERE work_order.sys_number = '".$sys_n."'
//         AND time_sheet.work_order_type_id = ".$wt."
//     ";
//     $result = $wpdb->get_results($sql_get_total_time);
//     print json_encode($result[0]);
// };

function getSystemList(){
    global $wpdb;
    $sql_getSystemList = "
        SELECT sys_number
        FROM `work_order`
        GROUP BY sys_number
        ORDER BY `sys_number` DESC
    ";
    $result = $wpdb->get_results($sql_getSystemList);
    print json_encode($result);
}

function getCurrentBinLocation($id_work){
    global $wpdb;
    $res = [];
    $sql_getCurrentBinLocation = "
        SELECT * FROM `work_order_location`
        WHERE work_order_id = ".$id_work."
    ";

    $result = $wpdb->get_results($sql_getCurrentBinLocation);
    $res['total'] = count($result) ;
    if(count($result) != 0){
       $sql_get_location_name = "
       SELECT storage_location.location 
            FROM storage_location
            INNER JOIN work_order_location
            ON work_order_location.storage_location_id = storage_location.id
            WHERE work_order_location.storage_location_id = '".$result[0]->storage_location_id."'
       ";

       
       $f = $wpdb->get_results($sql_get_location_name);
       $res["location"] = $f[0]->location;
    }

    print json_encode($res);

}//getBinLocation()

function changBinLocation($new_bin_location, $workID){
    global $wpdb;
    $res = [];
    $sql_check_current_location = "SELECT * FROM `work_order_location` WHERE `work_order_id` = '" .$workID. "'";
    
    $result = $wpdb->get_results($sql_check_current_location);


    if(count($result) == 0){
        $sql_insert_location['work_order_id'] = $workID;
        $sql_insert_location['storage_location_id'] = $new_bin_location;
        $wpdb->insert('work_order_location', $sql_insert_location);
        $res["status"] = "Created";
    }else{
        $where = ['work_order_id'=>$workID];
        $wpdb->update('work_order_location', array("storage_location_id"=>$new_bin_location), $where);
        $res['status'] = "updated";
    }
    print json_encode($res);
}//end changBinLocation();


function getStorageLocation(){
    global $wpdb;
    $userClockInInfo = array();
    // check if user have logged in
    $locationList = $wpdb->get_results ( "
        SELECT * FROM `storage_location`;
    " );
    print json_encode($locationList);
}

function isUserClockIn($user_id){
    global $wpdb;
    $userClockInInfo = array();
    // check if user have logged in
    $if_user_have_clock_in = $wpdb->get_results ( "
        SELECT * FROM `time_sheet` WHERE `end_time` IS NULL AND `wp_user_id` = '". $user_id ."' ORDER BY id DESC LIMIT 1;
    " );
    if(count($if_user_have_clock_in) == 0){
        $userClockInInfo["isClockIn"] = false;
    }else{
        $userClockInInfo["isClockIn"] = true;
    }

    $have_user_clock_in = $wpdb->get_results ( "
        SELECT * FROM `time_sheet` WHERE `wp_user_id` = '". $user_id ."' ORDER BY id DESC LIMIT 1;
    " );
    if(count($have_user_clock_in)==0){
        $userClockInInfo["haveClockIn"] = false;
        $userClockInInfo["clockIninfo"] = 0;
    }else{
        $userClockInInfo["haveClockIn"] = true;
        $userClockInInfo["clockIninfo"] = $have_user_clock_in;
    }
    print json_encode($userClockInInfo);
}

function getWorkTypeList(){
    global $wpdb;
    $datatables_sql = "
        SELECT * FROM `work_order_type`
    ";
    $data = $wpdb->get_results ( $datatables_sql );

    $result = json_decode(json_encode($data),true);

    print_r(json_encode($data));
}
// end getWorkTypeList

function clockInToLast($user){
    global $wpdb; 
    $get_l_last_work_id = $wpdb->get_results ( "
        SELECT * FROM `time_sheet` WHERE `wp_user_id` = '". $user ."' ORDER BY id DESC LIMIT 1;
    " );

    if(count($get_l_last_work_id) == 0){
        echo "No Record Found.";
    }else{
        $last_work_id = $get_l_last_work_id[0]->work_order_id;
        $work_type = $get_l_last_work_id[0]->work_order_type_id;
        userClockInAndOut($last_work_id, $user, $work_type);
    }

    
}
// end clockInToLast()


// ++++++++++++++++++++++++
function getShortageList($wn){
    global $wpdb;
    $sql_get_shortage = "
        SELECT work_order_bom.id, work_order_bom.status, work_order.work_order_number, part_list.component, part_list.title, work_order_bom.qty
        FROM `work_order_bom` 
        INNER JOIN work_order
        ON work_order_bom.work_order_id = work_order.id
        INNER JOIN  part_list
        ON work_order_bom.component = part_list.id
        WHERE `status` != 'Completed' AND  `work_order_id` = '".$wn ."'
        ORDER BY part_list.component
    ";
    $result = $wpdb->get_results($sql_get_shortage);
    print json_encode($result);
}//End getShortageList()


// Clock in/out
function userClockInAndOut($wo_id, $user_id, $work_type = 1, $extra_wt = null){

    if(!if_work_order_exist($wo_id)){
        exit("Work Order Not Found: " . $wo_id);
    }
    global  $wpdb; 
    // check if user have logged in
    $if_user_have_clock_in = $wpdb->get_results ( "
    SELECT * FROM `time_sheet` WHERE `end_time` IS NULL AND `wp_user_id` = '". $user_id ."' ORDER BY id DESC LIMIT 1;
    " );
    if($work_type == 0){
        $work_type = 1;
    }

    if(count($if_user_have_clock_in) == 0){
        // Clock in
        $result = $wpdb->insert('time_sheet', array(
            'work_order_id' => $wo_id,
            'wp_user_id' => $user_id,
            'work_order_type_id' => $work_type,
            'start_time' => current_time('mysql'), 
        ));

        if($result === false){
            echo "login Error";
        }else{

            // insert extra work type
            if($extra_wt != null){
                // Get current time sheet id
                $current_time_sheet_id = $wpdb->get_results ( "
                    SELECT id FROM `time_sheet` WHERE `end_time` IS NULL AND `wp_user_id` = '". $user_id ."' ORDER BY id DESC LIMIT 1;
                " );

                $extra_work_type_result = $wpdb->insert('work_type_time_sheet', array(
                    'work_type_id' => $extra_wt,
                    'time_sheet_id' => $current_time_sheet_id[0]->id,
                ));
                if($extra_work_type_result === false){
                    echo "Extra Work Type Insert Error";
                }else{
                    echo "You've clocked in.";
                }
            }else{
                echo "You've clocked in.";
            }

            
        }

    }else{
        // Clock out
        $word_order_to_update = $if_user_have_clock_in[0]->id;
        $start_time = $if_user_have_clock_in[0]->start_time;
        // Declare and define two dates 
        $date1 = strtotime($start_time);  
        $date2 = strtotime(current_time('mysql'));  
        $total_time = $date2 - $date1;  

        // check if total time is positive
        if($total_time >= 0){
            $where = [ 'id' => $word_order_to_update,
                        'wp_user_id' => $user_id,
                        ];

            $updated = $wpdb->update( 'time_sheet', array(
                'end_time' => current_time('mysql'), 
                'total_time' => $total_time,
            ), $where );
     
            if ( false === $updated ) {
                // There was an error.
                echo "Error updating time sheet.". $updated;
            } else {
                // No error. You can check updated to see how many rows were changed.
                $update_result = $wpdb->get_results('
                SELECT * FROM `time_sheet` WHERE `id` = '. $word_order_to_update .'
                ');
                $total_clockin_time = $update_result[0]->total_time/60;
                echo "You've clocked out. Total clock in time: ".round($total_clockin_time, 2). " minutes";
            }
    
        }else{
            echo "Time cannot be negetive.";
        }

        
    }

}

function getWorkOrderHistory($wn){
    // /api/?action=getWorkOrderHistory&wonum=WO-000786
    global  $wpdb;
    $work_order_id = getWorkOrderIdByWorkNumber($wn);
    $datatables_sql = "
        SELECT wp_users.display_name, work_order.work_order_number, work_order.name, time_sheet.start_time, time_sheet.end_time, time_sheet.total_time
        FROM  time_sheet
        INNER JOIN work_order
            ON time_sheet.work_order_id = work_order.id 
        INNER JOIN wp_users
            ON time_sheet.wp_user_id = wp_users.ID 
        WHERE time_sheet.work_order_id = ". $work_order_id ."
        LIMIT 1000
    ";

    $result = $wpdb->get_results ( $datatables_sql);

    print json_encode($result);
}

function if_work_order_exist($wo_id){
    global  $wpdb;
    $result = $wpdb->get_results ( "
    SELECT * FROM `work_order` WHERE `id` = ".$wo_id."
    " );

    if ( count($result)>0  ) {
        return true;
    }else{
        return false;
    }
}

function checkDateToInsert($date_to_check){
    if ($date_to_check){
        // Check if the variable is an instance of DateTime.
        if ($date_to_check instanceof DateTime) {
            return  true;
        } else {
            return false;
        } // You were also missing this closing brace
    }
    else {
        return false; 
    }
}
// end Clock in/out



function GetUserLoginHistory($user_id){

    $datatables_sql = "
        SELECT work_order.work_order_number, work_order.name, time_sheet.start_time, time_sheet.end_time, time_sheet.total_time
        FROM time_sheet
        INNER JOIN work_order
        ON time_sheet.work_order_id = work_order.id
        WHERE time_sheet.wp_user_id = ". $user_id ."
        LIMIT 1000
    ";
    
    $datatable_columns = array(
        array( 'db' => 'work_order_number', 'dt' => 0 ),
        array( 'db' => 'name', 'dt' => 1 ),
        array( 'db' => 'start_time',  'dt' => 2 ),
        array( 'db' => 'end_time',   'dt' => 3 ),
        array( 'db' => 'total_time',     'dt' => 4 ),
    );

    $result = datatables_data_output($datatable_columns, $datatables_sql);

    print json_encode($result);
    
}
// End GetUserLoginHistory();


function getWorkOrderList(){
    $datatables_sql = "
        SELECT * FROM `work_order` ORDER BY `id` DESC
        LIMIT 1000
    ";

    $datatable_columns = array(
        array( 'db' => 'id', 'dt' => 0 ),
        array( 'db' => 'work_order_number', 'dt' => 1 ),
        array( 'db' => 'assembly_id', 'dt' => 2 ),
        array( 'db' => 'name',  'dt' => 3 ),
        array( 'db' => 'sys_number',  'dt' => 4 )
    );

    $result = datatables_data_output($datatable_columns, $datatables_sql);

    print json_encode($result);

}


function getWorkOrderNumber(){
    global $wpdb;
    $search = sanitize_text_field($_POST['q']);
    $datatables_sql = "
        SELECT `work_order_id` FROM `work_order` WHERE `work_order_id` LIKE '%".$search."%' LIMIT 50
    ";

    $data = $wpdb->get_results ( $datatables_sql );

    $data["result"] = count($data);

    print json_encode($data);
}
    



/**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */

function datatables_data_output ( $columns, $sql ){

    global $wpdb;
    $row = array();
    $data = $wpdb->get_results ( $sql );
    

    if(count($data) == 0){

        for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
            $column = $columns[$j];
            $row[ $column['dt'] ] = "NA";
            
        }
        $out[] = $row;
        
    }else{
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            
    
    
            $data_i = json_decode(json_encode($data[$i]), true);
    
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                }
                else {
                    $row[ $column['dt'] ] = $data_i[ $columns[$j]['db'] ];
                }
            }
    
            $out[] = $row;
        }
    
        
    }

    $format_data['data'] = $out;

    return $format_data;

}



