<?php
namespace AjaxChat\Integrations\PhpBB3;
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */
use AjaxChat\CustomAJAXChat;

class CustomAJAXChatInterface extends CustomAJAXChat {

	function initialize() {
		// Initialize configuration settings:
		$this->initConfig();

		// Initialize the DataBase connection:
		$this->initDataBaseConnection();
	}

}
