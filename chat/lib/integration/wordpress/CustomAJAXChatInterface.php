<?php
/* 
 * Use the interface class to expose chat methods such as database connections without side-effects
 * such as session handling, request var processing, or browser output.
 * 
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

class CustomAJAXChatInterface extends CustomAJAXChat {

	function initialize($config) {
		// Initialize configuration settings:
		$this->initConfig($config);

		// Initialize the DataBase connection:
		$this->initDataBaseConnection();
	}

}
