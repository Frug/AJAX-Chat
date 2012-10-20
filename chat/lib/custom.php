<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 * 
 * phpBB3 integration:
 * http://www.phpbb.com/
 */

// phpBB initialization:		
define('IN_PHPBB', true);
$phpbb_root_path = AJAX_CHAT_PATH.'../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path.'common.'.$phpEx);

// phpBB session management:
$user->session_begin();
$auth->acl($user->data);
?>