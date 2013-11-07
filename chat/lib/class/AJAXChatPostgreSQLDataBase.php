<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 */

// Class to initialize the MySQL DataBase connection:
class AJAXChatDatabasePostgreSQL {

	var $_connectionID;
	var $_errno = 0;
	var $_error = '';
	var $_dbName;

	function type() {
		return 'postgresql';
	}

	function AJAXChatDataBasePostgreSQL(&$dbConnectionConfig) {
		$this->_connectionID = $dbConnectionConfig['link'];
		$this->_dbName = $dbConnectionConfig['name'];
	}
	
	// Method to connect to the DataBase server:
	function connect(&$dbConnectionConfig) {
		$connstring =
			" host="     . $dbConnectionConfig['host'] .
			" dbname="   . $dbConnectionConfig['name'] .
			" user="     . $dbConnectionConfig['user'] .
 			" password=" . $dbConnectionConfig['pass'] ;

		$this->_connectionID = @pg_connect($connstring);

		if(!$this->_connectionID) {
			$this->_errno = null;
			$this->_error = 'Database connection failed: ' . pg_result_error();
			return false;
		}
		return true;
	}
	
	// Method to select the DataBase:
	function select($dbName) {
		if($dbName != pg_dbname($this->_connectionID)) {
			die('Switching databases is not supported.');
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
			$str = 'Error-Report: '	.$this->_error."\n";
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
		return "'".pg_escape_string($value)."'";
	}
	
	// Method to perform SQL queries:
	function sqlQuery($sql) {
		return new AJAXChatPostgreSQLQuery($sql, $this->_connectionID);
	}

	// Method to retrieve the current DataBase name:
	function getName() {
		return $this->_dbName;
	}

	// Method to retrieve the last inserted ID:
	function getLastInsertedID() {
		// Note: LASTVAL Requires PostgreSQL >= 8.1
		$sql    = 'SELECT LASTVAL() AS lastval';
		$result = $this->sqlQuery($sql);

		// Stop if an error occurs:
		if($result->error()) {
			echo $result->getError();
			die();
		}

		$row = $result->fetch();
		return $row['lastval'];
	}

	// IP Address storage format
	function ipToStorageFormat($ip) {
		return $ip;
	}
	
	function ipFromStorageFormat($ip) {
		return $ip;
	}

	// SQL date manipulation
	function dateAddSqlFragment($sql_expr, $amount, $unit) {
		$amount = intval($amount);
		return "$sql_expr + interval '$amount $unit'";
	}
	function unixTimestampSqlFragment($column_name) {
		return "extract(epoch from $column_name)";
	}	

	// Database table name
	function getDataBaseTable($table) {
		return $table;
	}

}
?>
