<?php
/*
 * Use this file to define globals and load custom libraries that need global scope.
 * This file is loaded before all other AJAX Chat classes by the core index.php file.
 * It is not referenced anywhere else.
 * 
 * XenForo integration:
 * http://xenforo.com/
 */

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