<?php
/*
 * Use this file to define globals and load custom libraries that need global scope.
 * This file is loaded before all other AJAX Chat classes by the core index.php file.
 * It is not referenced anywhere else.
 * 
 * Wordpress integration:
 * http://www.wordpress.org/
 */
 
// WordPress initialization:	
$wp_root_path	= AJAX_CHAT_PATH.'../';
require_once( $wp_root_path.'wp-config.php' );

// get Wordpress user info
global $current_user;
get_currentuserinfo();
