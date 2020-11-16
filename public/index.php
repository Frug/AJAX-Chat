<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @author Philip Nicolcev
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Suppress errors:
ini_set('display_errors', 0);

// Path to the chat directory:
define('AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/../');

// Include custom libraries and initialization code:
require(AJAX_CHAT_PATH.'src/custom.php');

// Include Class libraries:
require(AJAX_CHAT_PATH.'vendor/autoload.php');

// Initialize the chat:
$ajaxChat = new \AjaxChat\Integrations\Standalone\CustomAJAXChat();
