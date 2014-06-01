<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Initialize WordPress
if( isset( $_SERVER['DOCUMENT_ROOT'] ) ){
	$file	= $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';
	if( !file_exists( $file ) ){
		exit( 'AJAXChat was not able to locate wp-config.php in your WordPress installation, please edit lib/custom.php and add the correct path to the $file variable.');
	}
}else{
	exit( 'your server could be misconfigurated , DOCUMENT_ROOT was not available in server globals.' );
}
// require wp-config.php
require_once( $file );
// initiate $current_user var
global $current_user;
get_currentuserinfo();
?>