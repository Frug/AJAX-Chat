<?php
/*
 * Use this file to define globals and load custom libraries that need global scope.
 * This file is loaded before all other AJAX Chat classes by the core index.php file.
 * It is not referenced anywhere else.
 * 
 * PunBB integration:
 * http://punbb.org/
 */

// Set up globals and include files for punBB
define('PUN_ROOT', AJAX_CHAT_PATH.'../');
require PUN_ROOT.'include/common.php';