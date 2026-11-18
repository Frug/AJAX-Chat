<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Class to initialize the MySQL DataBase connection:
class AJAXChatDataBaseMySQLi {

	protected
		$_connectionID,
		$_errno = 0,
		$_error = '',
		$_dbName;

	function __construct(&$dbConnectionConfig) {
		// PHP 8.1+ makes mysqli throw exceptions by default.
		// AJAX Chat checks every query result itself, so the classic error mode is restored:
		mysqli_report(MYSQLI_REPORT_OFF);

		$this->_connectionID = isset($dbConnectionConfig['link']) ? $dbConnectionConfig['link'] : null;
		$this->_dbName = isset($dbConnectionConfig['name']) ? $dbConnectionConfig['name'] : null;
	}

	// Method to connect to the DataBase server:
	function connect(&$dbConnectionConfig) {
		$port = !empty($dbConnectionConfig['port']) ? (int)$dbConnectionConfig['port'] : null;
		$this->_connectionID = mysqli_init();
		if(!@$this->_connectionID->real_connect(
			$dbConnectionConfig['host'],
			$dbConnectionConfig['user'],
			$dbConnectionConfig['pass'],
			null,
			$port
		)) {
			$this->_errno = mysqli_connect_errno();
			$this->_error = mysqli_connect_error();
			return false;
		}
		// utf8mb4 is the real UTF-8 in MySQL (utf8 only stores 3 bytes: emoji would break queries):
		$charset = !empty($dbConnectionConfig['charset']) ? $dbConnectionConfig['charset'] : 'utf8mb4';
		if(!$this->_connectionID->set_charset($charset)) {
			$this->_errno = $this->_connectionID->errno;
			$this->_error = $this->_connectionID->error;
			return false;
		}
		return true;
	}

	// Method to select the DataBase:
	function select($dbName) {
		if(!$this->_connectionID->select_db($dbName)) {
			$this->_errno = $this->_connectionID->errno;
			$this->_error = $this->_connectionID->error;
			return false;
		}
		$this->_dbName = $dbName;
		return true;
	}

	// Method to determine if an error has occured:
	function error() {
		return (bool)$this->_error;
	}

	// Method to return the error report:
	function getError() {
		if($this->error()) {
			$str = 'Error-Report: '.$this->_error."\n";
			$str .= 'Error-Code: '.$this->_errno."\n";
		} else {
			$str = 'No errors.'."\n";
		}
		return $str;
	}

	// Method to return the connection identifier:
	function &getConnectionID() {
		return $this->_connectionID;
	}

	// Method to prevent SQL injections:
	function makeSafe($value) {
		// (string) cast: PHP 8.1+ deprecates passing null to string functions:
		return "'".$this->_connectionID->real_escape_string((string)$value)."'";
	}

	// Method to perform SQL queries:
	function sqlQuery($sql) {
		return new AJAXChatMySQLiQuery($sql, $this->_connectionID);
	}

	// Method to retrieve the current DataBase name:
	function getName() {
		return $this->_dbName;
	}

	// Method to retrieve the last inserted ID:
	function getLastInsertedID() {
		return $this->_connectionID->insert_id;
	}

}
