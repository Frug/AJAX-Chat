<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 * 
 * vBulletin integration:
 * http://www.vbulletin.com/
 */

// vBulletin initialization:	
error_reporting(E_ALL & ~E_NOTICE);
define('THIS_SCRIPT', 'ajax_chat');
chdir(AJAX_CHAT_PATH.'../');
require(AJAX_CHAT_PATH.'../global.php');
?>