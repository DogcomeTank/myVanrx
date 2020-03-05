<?php
/* Template Name: Clock In Functions */ 

// get_header();



$the_action = $_GET['action'];
$work_order_id = $_GET['woid'];


if(!is_user_logged_in()){
    die ("Please login");
    exit;
}else{
    global $current_user;
    $current_user = wp_get_current_user();
    $login_user_id = $current_user->ID;
}

if($the_action==false){
    die ("Action Not Found.");
}



if($work_order_id==false){
    die ("Work order ID Not Provided.");
}else{
    $work_order_validation = check_work_order_exist($work_order_id);
    if(!$work_order_validation){
        die('Work Order Not Found.');
    }
}

switch ($the_action) {
    case "login":
        login_date_insert($work_order_id, $login_user_id);
        break;
    case "deleteTime":
        echo "i equals 1";
        break;
    case 2:
        echo "i equals 2";
        break;
}


function login_date_insert($wo_id, $user_id){
    global  $wpdb; $current_user;

    $if_logined = $wpdb->get_results ( "
    SELECT * FROM `time_sheet` WHERE `end_time` IS NULL AND `wp_user_id` = '". $user_id ."' ORDER BY id DESC LIMIT 1;
    " );



    if(count($if_logined) == 0){
        // login
        $wpdb->insert('time_sheet', array(
            'work_order_id' => $wo_id,
            'wp_user_id' => $user_id,
            'start_time' => date("Y-m-d H:i:s"), 
        ));
        echo $user_id. ", You've login";
    }else{
        // logout
        $word_order_to_update = $if_logined[0]->id;
        $start_time = $if_logined[0]->start_time;
        // Declare and define two dates 
        $date1 = strtotime($start_time);  
        $date2 = strtotime(date("Y-m-d H:i:s"));  
        $total_time = $date2 - $date1;  

        // check if total time is positive
        if($total_time >= 0){
            $where = [ 'id' => $word_order_to_update,
                        'wp_user_id' => $user_id,
                        ];

            $updated = $wpdb->update( 'time_sheet', array(
                'end_time' => date("Y-m-d H:i:s"), 
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
                echo $user_id. ", You've logout. Total clockin time: ".round($total_clockin_time, 2). " minutes";
            }
    
        }else{
            echo "Time cannot be negetive.";
        }
        
    }

}

function check_work_order_exist($wo_id){
    global  $wpdb;
    $result = $wpdb->get_results ( "
    SELECT * FROM `work_order` WHERE `id` = '1'
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