<?php
/*
Plugin Name: Clear All Transients
Plugin URI: http://json.sx/clearalltransients
Description: Allows you to delete all transients from WordPress. 
Version: 0.1
Author: Jason Stallings
Author URI: http://json.sx
License: GPL2
*/
?>



<?php

add_action( 'admin_menu', 'clearalltransients_menu' );
add_action( 'admin_footer', 'clearalltransients_javascript' );
add_action('wp_ajax_clearalltransients_clear', 'clearalltransients_clear');


function clearalltransients_menu() 
{
	add_submenu_page("tools.php", 'Clear All Transients', 'Clear All Transients', 'manage_options', 'clearalltransients_menu', 'clearalltransients_options' );
}


function clearalltransients_options() 
{
	if ( !current_user_can( 'manage_options' ) )  
	{
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}


//HTML Displayed in the Backend	
?>

<div class="wrap">
<div id="icon-tools" class="icon32"></div><h2>Clear all Transients</h2>
<br>
<h3>Current transients: <span id="currentTransients">

<?php
global $wpdb;
$transients=$wpdb->query("SELECT * FROM $wpdb->options WHERE `option_name` LIKE '_transient_%'");
echo $transients;
?>
</span>
</h3>
<br>
<div style="width:175px;">
<span class="spinner">
</span>
<input class="button-primary" type="button" name="clearalltransients" id="ClearAllTransientsButton" value="Clear All Transients">
</div>
</div>

<?php
}


function clearalltransients_javascript() 
{
?>
<script type="text/javascript" >
jQuery(document).ready(function($) 
{
	var loadingIndicator = $(".spinner").hide();
	loadingIndicator.ajaxStart(function() {
	    loadingIndicator.show();
	}).ajaxStop(function() {
	    loadingIndicator.hide();
	});	

	jQuery("#ClearAllTransientsButton").on("click", function ()
	{
		var data = {
			action: 'clearalltransients_clear',
		};	
		$.post(ajaxurl, data, function(response) {
			$("#currentTransients").text(response.replace(/\s+/g, ' '))
		});
	});


});
</script>

<?php
}

function clearalltransients_clear() 
{
	global $wpdb; // this is how you get access to the database

	$wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '_transient_%'");

	$transients=$wpdb->query("SELECT * FROM $wpdb->options WHERE `option_name` LIKE '_transient_%'");

	echo $transients;

	die();
}




?>
