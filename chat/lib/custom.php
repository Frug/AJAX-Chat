<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Include custom libraries and initialization code here

// How to use XenForo Objects: Once initialized the below you can use XenForo objects
define('XF_ROOT', AJAX_CHAT_PATH . '../');
define('TIMENOW', time());
define('SESSION_BYPASS', false); // if true: logged in user info and sessions are not needed
require_once(XF_ROOT . '/library/XenForo/Autoloader.php');
XenForo_Autoloader::getInstance()->setupAutoloader(XF_ROOT . '/library');
XenForo_Application::initialize(XF_ROOT . '/library', XF_ROOT);
XenForo_Application::set('page_start_time', TIMENOW);
XenForo_Application::disablePhpErrorHandler();

// Loads class dependencies, initilizing XF code events listeners.
$dependencies = new XenForo_Dependencies_Public();
$dependencies->preLoadData();

XenForo_Session::startPublicSession();
error_reporting(E_ALL & ~E_NOTICE); // Turn off the strict error reporting.

?>