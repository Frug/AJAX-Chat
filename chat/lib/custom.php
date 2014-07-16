<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// WordPress initialization:	
$wp_root_path	= AJAX_CHAT_PATH.'../';
require_once( $wp_root_path.'wp-config.php' );

// get Wordpress user info
global $current_user;
get_currentuserinfo();
?>