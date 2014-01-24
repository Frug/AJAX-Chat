<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Suppress errors.
error_reporting(0);

// Path to the chat directory:
define('AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');

// Include settings and class libraries:
require(AJAX_CHAT_PATH.'lib/classes.php');

// Include startup items for integration versions:
require(AJAX_CHAT_PATH.'lib/integration/'.$AJAXChatConfig['integration'].'/startup.php');

// Initialize the chat:
$ajaxChat = new CustomAJAXChat($AJAXChatConfig);
?>