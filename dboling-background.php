<?php
/*
Plugin Name: dboling Fullscreen Background
Plugin URI: 
Description: Easily add a responsive, fullscreen background that stays centered and at correct aspect ratio to entire site. Can also randomly cycle through multiple backgrounds.
Version: 1.0.0
Author: Darrien Boling
Author URI: http://dboling.com
License: MIT
*/

/*  Copyright 2016 Darrien Boling

    This program is free software; you can redistribute it and/or modify
    it under the terms of the MIT license.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
    

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function dboling_background_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'dboling-background.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://dboling.com" target="_blank">Author Website</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'dboling_background_plugin_row_meta', 10, 2 );


function dboling_background_plugin_action_links( $actions, $plugin_file ){
	
	static $plugin;

	if ( !isset($plugin) ){
		$plugin = plugin_basename(__FILE__);
	}
		
	if ($plugin == $plugin_file) {
		
		if ( is_ssl() ) {
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=dboling_background', 'https' ).'">Settings</a>';
		}else{
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=dboling_background', 'http' ).'">Settings</a>';
		}
		
		$settings = array($settings_link);
		
		$actions = array_merge($settings, $actions);
			
	}
	
	return $actions;
	
}
add_filter( 'plugin_action_links', 'dboling_background_plugin_action_links', 10, 5 );


	function dboling_background() {
		add_plugins_page( 'dboling Background Settings', 'dboling Background', 'manage_options', 'dboling_background', 'dboling_background_settings' );
	}
	add_action( 'admin_menu', 'dboling_background' );


	function dboling_background_register_setting() {
		register_setting( 'dboling_setting_background_link', 'dboling_background_random' );
	}
	add_action( 'admin_init', 'dboling_background_register_setting' );
	
		
	function dboling_background_settings(){
		?>
			<div class="wrap">
				<h2>dboling Fullscreen Background Settings</h2>

				<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ){ ?>
					<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
						<p><strong>Settings saved.</strong></p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Thanks!</span></button>
					</div>
				<?php } ?>
                
            	<form method="post" action="options.php">
                	<?php settings_fields( 'dboling_setting_background_link' ); ?>
                	<table class="form-table">
                		<tbody>

                            <tr>
                                <th><label for="dboling_background_random">Background URLs</label></th>
                                <td>
                                    <textarea id="dboling_background_random" name="dboling_background_random" rows="10" cols="50" class="large-text code" style="white-space:nowrap !important;"><?php echo esc_textarea( get_option('dboling_background_random') ); ?></textarea>
                                    <h3>Enter list of background URLs, one per line.</h3>
                                    <p class="description">They will be displayed at random. If you just want to display one image at fullscreen (no randomization), feel free to add only one URL.</p>
                                </td>
                            </tr>

                    	</tbody>
                    </table>
                    <p class="submit"><input id="submit" class="button button-primary" type="submit" name="submit" value="Save Changes"></p>
                </form>
            </div>
		<?php 
	}


function dboling_background_css(){
	?>

		<?php if( get_option('dboling_background_random') )  : ?>

    		<?php
    			$get_links 		= 	str_replace(' ', '', get_option('dboling_background_random') );
    			$preg_replace 	= 	preg_replace( "/\s+/", "\n", $get_links );
    			$explode 		= 	explode("\n", $preg_replace);
    			$make_array 	= 	(array) $explode;
    			$array 			=	$make_array;
    			$count			=	count($array) - 1;
    			$random 		=	rand(0, $count);
    			$background_link 	= 	$array[$random];
    		?>

			<style type="text/css">
				/* dboling Fullscreen Background Plugin */
				body{
					background:url(<?php echo $background_link; ?>) no-repeat center center fixed !important;
					-webkit-background-size: cover !important;
					-moz-background-size: cover !important;
					-o-background-size: cover !important;
					background-size: cover !important;
				}
			</style>
		<?php endif; ?>
    
	<?php
}
add_action( 'wp_head', 'dboling_background_css', 999 );


?>