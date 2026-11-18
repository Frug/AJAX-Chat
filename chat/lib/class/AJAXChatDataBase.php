<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Class to initialize the DataBase connection.
// Only MySQLi is supported: the old mysql_* extension was removed in PHP 7.
class AJAXChatDataBase {

	protected
		$_db;

	function __construct(&$dbConnectionConfig) {
		if(isset($dbConnectionConfig['type']) && $dbConnectionConfig['type'] && $dbConnectionConfig['type'] !== 'mysqli') {
			trigger_error('AJAX Chat: database type "'.$dbConnectionConfig['type'].'" is not supported anymore, using mysqli.', E_USER_NOTICE);
		}
		$this->_db = new AJAXChatDataBaseMySQLi($dbConnectionConfig);
	}

	// Method to connect to the DataBase server:
	function connect(&$dbConnectionConfig) {
		return $this->_db->connect($dbConnectionConfig);
	}

	// Method to select the DataBase:
	function select($dbName) {
		return $this->_db->select($dbName);
	}

	// Method to determine if an error has occured:
	function error() {
		return $this->_db->error();
	}

	// Method to return the error report:
	function getError() {
		return $this->_db->getError();
	}

	// Method to return the connection identifier:
	function &getConnectionID() {
		return $this->_db->getConnectionID();
	}

	// Method to prevent SQL injections:
	function makeSafe($value) {
		return $this->_db->makeSafe($value);
	}

	// Method to perform SQL queries:
	function sqlQuery($sql) {
		return $this->_db->sqlQuery($sql);
	}

	// Method to retrieve the current DataBase name:
	function getName() {
		return $this->_db->getName();
		//If your database has hyphens ( - ) in it, try using this instead:
		//return '`'.$this->_db->getName().'`';
	}

	// Method to retrieve the last inserted ID:
	function getLastInsertedID() {
		return $this->_db->getLastInsertedID();
	}

}
