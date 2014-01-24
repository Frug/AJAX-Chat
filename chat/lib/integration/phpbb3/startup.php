<?php
/*
 * Use this file to define globals and load custom libraries that need global scope.
 * This file is loaded before all other AJAX Chat classes by the core index.php file.
 * It is not referenced anywhere else.
 * 
 * phpBB3 integration:
 * http://www.phpbb.com/
 */

// Set up globals and include files for phpBB3
define('IN_PHPBB', true);
$phpbb_root_path = AJAX_CHAT_PATH.'../';
$phpEx = 'php';
require($phpbb_root_path.'common.php');