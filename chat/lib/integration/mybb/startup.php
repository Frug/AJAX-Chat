<?php
/*
 * Use this file to define globals and load custom libraries that need global scope.
 * This file is loaded before all other AJAX Chat classes by the core index.php file.
 * It is not referenced anywhere else.
 * 
 * MyBB integration:
 * http://www.mybboard.net/
 */

// Set up globals and include files for MyBB
define('IN_MYBB', 1);
chdir(AJAX_CHAT_PATH.'../');
require(AJAX_CHAT_PATH.'../global.php');