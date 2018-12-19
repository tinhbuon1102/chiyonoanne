<?php
/*
Plugin Name: Booking Ultra Pro Professional & Ultimate
Plugin URI: https://bookingultrapro.com
Description: Add-on for for Booking Ultra Pro Professional & Ultimate.
Version: 1.0.0
Author: Booking Ultra Pro
Author URI: https://bookingultrapro.com/
*/

define('bookingup_ultimate_url',plugin_dir_url(__FILE__ ));
define('bookingup_ultimate_path',plugin_dir_path(__FILE__ ));

$plugin = plugin_basename(__FILE__);


/* Master Class  */
require_once (bookingup_ultimate_path . 'classes/bup.ultimate.class.php');

register_activation_hook( __FILE__, 'bup_ultimate_activation');

function  bup_ultimate_activation( $network_wide ) 
{
	$plugin = "booking-ultra-pro-ultimate/index.php";
	$plugin_path = '';	
	
	if ( is_multisite() && $network_wide ) // See if being activated on the entire network or one blog
	{ 
		activate_plugin($plugin_path,NULL,true);			
		
	} else { // Running on a single blog		   	
			
		activate_plugin($plugin_path,NULL,false);		
		
	}
}
global $bupultimate;
$bupultimate = new BookingUltraProUltimate();