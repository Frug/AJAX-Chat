<?php
namespace AjaxChat\Database;

/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Class to initialize the MySQL DataBase connection:
class MySQLiConnection {

    protected
		$_connectionID,
		$_errno = 0,
		$_error = '',
		$_dbName;

	public function __construct(array $dbConnectionConfig) {
		$this->_connectionID = $dbConnectionConfig['link'];
		$this->_dbName = $dbConnectionConfig['name'];
	}
	
	// Method to connect to the DataBase server:
	public function connect(array $dbConnectionConfig) {
		$this->_connectionID = new \mysqli(
			$dbConnectionConfig['host'],
			$dbConnectionConfig['user'],
			$dbConnectionConfig['pass']
		);
		if($this->_connectionID->connect_errno) {
			$this->_errno = mysqli_connect_errno();
			$this->_error = mysqli_connect_error();
			return false;
		}
		return true;
	}
	
	// Method to select the DataBase:
	public function select(string $dbName) {
		if(!$this->_connectionID->select_db($dbName)) {
			$this->_errno = $this->_connectionID->errno;
			$this->_error = $this->_connectionID->error;
			return false;
		}
		$this->_dbName = $dbName;
		return true;	
	}
	
	// Method to determine if an error has occured:
	public function error() {
		return (bool)$this->_error;
	}
	
	// Method to return the error report:
    public function getError() {
		if($this->error()) {
			$str = 'Error-Report: '	.$this->_error."\n";
			$str .= 'Error-Code: '.$this->_errno."\n";
		} else {
			$str = 'No errors.'."\n";
		}
		return $str;		
	}
	
	// Method to return the connection identifier:
	public function &getConnectionID() {
		return $this->_connectionID;
	}
	
	// Method to prevent SQL injections:
	public function makeSafe($value) {
		return "'".$this->_connectionID->escape_string($value)."'";
	}

	// Method to perform SQL queries:
	public function sqlQuery($sql) {
		return new MySQLiQuery($sql, $this->_connectionID);
	}

	// Method to retrieve the current DataBase name:
	public function getName() {
		return $this->_dbName;
	}

	// Method to retrieve the last inserted ID:
	public function getLastInsertedID() {
		return $this->_connectionID->insert_id;
	}

}
