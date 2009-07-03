<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 */

// Show all errors:
error_reporting(E_ALL);

// Path to the chat directory:
define('AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');

// Include custom libraries and initialization code:
require(AJAX_CHAT_PATH.'lib/custom.php');

// Include Class libraries:
require(AJAX_CHAT_PATH.'lib/classes.php');

class CustomAJAXChatInstaller extends CustomAJAXChatInterface {

	function &getDataBaseTableCreationQueries() {
		$queries = array();
		$index = 0;
		// Retrieve the queries from the SQL file:
		$lines = file(AJAX_CHAT_PATH.'chat.sql');
		// Stop if an error occurs:
		if(!$lines) {
			echo 'Failed to load queries from file (chat.sql).';
			die();
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
$ajaxChatInstaller = new CustomAJAXChatInstaller();

// Create the database tables:
$ajaxChatInstaller->createDataBaseTables();
?>