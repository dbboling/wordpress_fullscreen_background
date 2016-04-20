<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Uninstall Plugin */

// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out!


/*else:
	If uninstalled plugin, these options will be deleted
*/
delete_option('boling_background_link');
delete_option('boling_background_random');

?>