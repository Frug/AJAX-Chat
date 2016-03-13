<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 * 
 * phpBB3 integration:
 * http://www.phpbb.com/
 */

// phpBB initialization:

// Varaibles required to be set before including common.php
define('IN_PHPBB', true);
$phpbb_root_path = dirname(AJAX_CHAT_PATH) . '/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

if (!file_exists($phpbb_root_path.'common.'.$phpEx)) {
    throw new \Exception("Unable to locate phpBB3's common.php. AJAX Chat expects common.php to be located in ${phpbb_root_path}common.${phpEx}. Check install location and change \$phpbb_root_path to point to phpBB's common.php in lib/custom.php if needed.");
}
require($phpbb_root_path.'common.'.$phpEx);

$request->enable_super_globals();
// phpBB session management:
$user->session_begin();
$auth->acl($user->data);
