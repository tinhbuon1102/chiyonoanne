<?php
defined('ABSPATH') or die("No script kiddies please!");

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

if(isset($_POST['everest_admin_theme'])){
	$plugin_settings = array_map('stripslashes_deep', $_POST['everest_admin_theme']);
	$sanitized_array = self:: sanitize_array($plugin_settings);
}
$key = update_option('eat_admin_theme_settings', $sanitized_array);
if($key == TRUE){
	wp_redirect(admin_url().'admin.php?page=everest-admin-theme&message=1');
}else{
	wp_redirect(admin_url().'admin.php?page=everest-admin-theme&message=2');
}
exit();