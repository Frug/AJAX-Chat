<?php
use AjaxChat\Loader;
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Show all errors:
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Path to the chat directory:
define('AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/../');

// Include Class libraries:
require(AJAX_CHAT_PATH.'vendor/autoload.php');

// Remember to set up the config file to point to your database:
file_exists(AJAX_CHAT_PATH.'src/config.php') or die('Failed to load lib/config.php. Did you remember to create a config file based on config.php.example?');

// Include custom libraries and initialization code:
require(AJAX_CHAT_PATH.'src/custom.php');

class CustomAJAXChatInstaller extends \AjaxChat\Integrations\Standalone\CustomAJAXChat {

	// Override the default initialize method to skip user session handling. We only want
	// a valid DB connection.
	public function initialize(array $config)
	{
		$this->_config = $config;

		$this->initDataBaseConnection();
	}

	function &getDataBaseTableCreationQueries() {
		$queries = array();
		$index = 0;
		// Retrieve the queries from the SQL file:
		$lines = file(AJAX_CHAT_PATH.'src/migrations/chat.sql');
		// Stop if an error occurs:
		if(!$lines) {
			die('Failed to load queries from file (chat.sql).');
		}
		foreach($lines as $line) {
			if(empty($line)) {
				continue;
			}
			$line = trim($line);
			if(count($queries) <= $index) {
				array_push($queries, $line."\n");
			} else {
				$queries[$index] .= $line."\n";	
			}
			// Create a new array item for each query:
			if(substr($line, -1) == ';') {
				$index++;
			}
		}
		return $queries;
	}

	function createDataBaseTables($printSuccessConfirmation=true) {
		$queries = $this->getDataBaseTableCreationQueries();
		foreach($queries as $sql) {
			// Create a new SQL query:
			$result = $this->db->sqlQuery($sql);
			
			// Stop if an error occurs:
			if($result->error()) {
				echo $result->getError();
				die();
			}
		}
		if($printSuccessConfirmation) {
			// Print a success confirmation:
			echo 'Database tables created successfully - please delete this file (install.php).';
		}
	}

}

// Initialize the chat installer:
$config = Loader::readConfigFile(AJAX_CHAT_PATH.'src/config.php');
$ajaxChatInstaller = new CustomAJAXChatInstaller($config);

// Create the database tables:
$ajaxChatInstaller->createDataBaseTables();
