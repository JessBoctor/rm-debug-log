<?php
/*
Plugin Name: Rimoht Debug Log Plugin
Plugin URI:  http://rimoht.com/debug-log
Description: Debug your code using write_log() function
Version:     0.1
Author:      Jess Boctor
Author URI:  http://jessboctor.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages

The following constants must be defined in your wp-config.php file for this plugin to work:
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', true );

To use this plugin, use the 'write_log('Your Log Message')' function in any file. 
The log file will be in the uploads/rm-debug/ directory and will be noted by the current date.

Plugin based on the write_log() function created by Stu Miller
http://www.stumiller.me/sending-output-to-the-wordpress-debug-log/
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'RM_DEBUG_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define( 'RM_DEBUG_LOG_PATH', RM_DEBUG_PLUGIN_PATH . '/rm-debug/' );

//See if there is a debug file directory in the uploads directory
if( !file_exists( RM_DEBUG_LOG_PATH ) ){
	//If is isn't there, create it
	wp_mkdir_p( RM_DEBUG_LOG_PATH );
}

//Change the location of WordPress Native Debug log
//Taken from https://wordpress.stackexchange.com/a/84171
if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ) {
	$date = date('m-d-Y');
	ini_set( 'error_log', RM_DEBUG_LOG_PATH . 'rm-debug-' . $date . '.log' );
}

//Create the write_log() function
if ( !function_exists( 'write_log' ) ){
	function write_log( $log ){
		
		//Get the current date
		$date = date('m-d-Y');
		$timestamp = date( '[ m-d-y H:i:s ] ' );
		
		//Get the file path
		$debug_file = RM_DEBUG_LOG_PATH . 'rm-debug-' . $date . '.log';
		
		//Check if the WP_DEBUG constant is set to true
        if ( true === WP_DEBUG ) {
			
			//If it is an array or object, print it to the error log
            if( is_array( $log ) || is_object( $log ) ){
				error_log( $timestamp, 3, $debug_file );
                error_log( print_r( $log, true), 3, $debug_file );
				error_log( PHP_EOL, 3, $debug_file );
            } else {
				//register it to the error log
                error_log( $timestamp . $log . PHP_EOL, 3, $debug_file );
            }
        }
    }
}