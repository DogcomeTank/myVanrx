<?php
/* Template Name: Page - Technician Current Status */ 
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

<img src="<?php echo (get_avatar_url('3')); ?>" alt="">

<?php
}
get_footer(); ?>