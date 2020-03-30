<?php
/* Template Name:Page - Assembly List*/ 


function report_page_scripts() {

    // wp_enqueue_script ( 'chartjs-script', get_stylesheet_directory_uri() . '/inc/Chart.js/chart.js' );
    // wp_enqueue_style ( 'chartjs-style', get_stylesheet_directory_uri() . '/inc/Chart.js/chart.css' );

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
?>

<div class="w3-padding">

<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(  
        'post_type' => 'assembly',
        'post_status' => 'publish',
        'posts_per_page' => 5, 
        'orderby' => 'title', 
        'order' => 'ASC',
		'paged'=>$paged
    );
	
	
    $loop = new WP_Query( $args ); 
	?>
	<table class="w3-table">
		<tr>
		  <th>ID</th>
		  <th>Picture</th>
		  <th>Name</th>
		</tr>

	<?php
    
    while ( 
        
        $loop->have_posts() ) : $loop->the_post(); 
		$featured_img = wp_get_attachment_image_src( $post->ID );
		$assembly_name = get_post_meta($post->ID, 'assembly_name', false);
		?>

		<tr>
		  <td><a href="<?php echo get_post_permalink(); ?>"><?php echo the_title(); ?></a></td>
		  <td><?php echo get_the_post_thumbnail( $post->ID, array( 80, 80) ); ?></td>
		  <td><?php echo $assembly_name[0]; ?></td>
		</tr>
		<?php
        

        

    endwhile;
	?>
	</table>
	<?php
	$total_pages = $loop->max_num_pages;
 
    if ($total_pages > 1){
        $current_page = max(1, get_query_var('paged'));
 
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    }

    wp_reset_postdata(); 

?>


</div>
 <?php
  
}
get_footer();