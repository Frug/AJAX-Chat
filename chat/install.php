<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Installer / upgrader for the AJAX Chat database tables.
// It is safe to run more than once: existing tables and messages are never dropped,
// old MyISAM/utf8 tables are converted to InnoDB/utf8mb4 (needed for emoji and other 4-byte characters).
// Delete this file after the installation anyway.

// Suppress errors in the output, details go to the server error log:
ini_set('display_errors', 0);

// Path to the chat directory:
define('AJAX_CHAT_PATH', __DIR__.'/');

// Remember to set up the config file to point to your database:
file_exists(AJAX_CHAT_PATH.'lib/config.php') or die('Failed to load lib/config.php. Did you remember to create a config file based on config.php.example?');

// Include custom libraries and initialization code:
require(AJAX_CHAT_PATH.'lib/custom.php');

// Include Class libraries:
require(AJAX_CHAT_PATH.'lib/classes.php');

class CustomAJAXChatInstaller extends CustomAJAXChatInterface {

	// Default table names used in chat.sql, replaced by the names configured in lib/config.php:
	protected $_defaultTableNames = array(
		'online' => 'ajax_chat_online',
		'messages' => 'ajax_chat_messages',
		'bans' => 'ajax_chat_bans',
		'invitations' => 'ajax_chat_invitations'
	);

	function &getDataBaseTableCreationQueries() {
		$queries = array();
		$sql = @file_get_contents(AJAX_CHAT_PATH.'chat.sql');
		// Stop if an error occurs:
		if(!$sql) {
			die('Failed to load queries from file (chat.sql).');
		}
		$replacements = array();
		foreach($this->_defaultTableNames as $key=>$defaultName) {
			$replacements[' '.$defaultName.' '] = ' '.$this->getDataBaseTable($key).' ';
		}
		foreach(explode(';', $sql) as $query) {
			$query = trim($query);
			if($query !== '') {
				$queries[] = strtr($query, $replacements);
			}
		}
		return $queries;
	}

	function getTableStatus($key) {
		$sql = 'SHOW TABLE STATUS'
			.($this->db->getName() ? ' FROM `'.str_replace('`', '``', $this->db->getName()).'`' : '')
			.' LIKE '.$this->db->makeSafe($this->getConfig('dbTableNames', $key)).';';
		$result = $this->db->sqlQuery($sql);
		if($result->error()) {
			$this->handleDataBaseError($result->getError());
		}
		$row = $result->fetch();
		$result->free();
		return $row;
	}

	function upgradeDataBaseTables() {
		$upgraded = array();
		foreach(array_keys($this->_defaultTableNames) as $key) {
			$status = $this->getTableStatus($key);
			if(!$status) {
				continue;
			}
			$isInnoDB = (strtolower($status['Engine']) === 'innodb');
			$isUtf8mb4 = (strpos(strtolower((string)$status['Collation']), 'utf8mb4') === 0);
			if($isInnoDB && $isUtf8mb4) {
				continue;
			}
			$sql = 'ALTER TABLE '.$this->getDataBaseTable($key)
				.' ENGINE=InnoDB, CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;';
			$result = $this->db->sqlQuery($sql);
			if($result->error()) {
				$this->handleDataBaseError($result->getError());
			}
			$upgraded[] = $this->getConfig('dbTableNames', $key);
		}
		return $upgraded;
	}

	function createDataBaseTables($printSuccessConfirmation=true) {
		// Convert existing tables first, then create the missing ones (CREATE TABLE IF NOT EXISTS):
		$upgraded = $this->upgradeDataBaseTables();
		foreach($this->getDataBaseTableCreationQueries() as $sql) {
			// Create a new SQL query:
			$result = $this->db->sqlQuery($sql);

			// Stop if an error occurs:
			if($result->error()) {
				$this->handleDataBaseError($result->getError());
			}
		}
		if($printSuccessConfirmation) {
			header('Content-Type: text/plain; charset=UTF-8');
			echo 'Database tables are ready.'."\n";
			if($upgraded) {
				echo 'Converted to InnoDB/utf8mb4: '.implode(', ', $upgraded)."\n";
			}
			echo 'Please delete this file (install.php).'."\n";
		}
	}

}

// Initialize the chat installer:
$ajaxChatInstaller = new CustomAJAXChatInstaller();

// Create or upgrade the database tables:
$ajaxChatInstaller->createDataBaseTables();
