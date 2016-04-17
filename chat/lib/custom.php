<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 * 
 * FluxBB integration:
 * http://fluxbb.org/
 */

// FluxBB initialization:
$fluxbb_root_path = dirname(AJAX_CHAT_PATH) . '/';
define('PUN_ROOT', $fluxbb_root_path);
require PUN_ROOT.'include/common.php';
