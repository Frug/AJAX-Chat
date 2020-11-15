<?php
namespace AjaxChat\Database;
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

use AjaxChat\Database\MySQLiConnection;

// Class to initialize the DataBase connection:
class Database {

	protected
		$_db;

	public function __construct(array $dbConnectionConfig) {
		switch($dbConnectionConfig['type']) {
			case 'mysqli':
			default:
				$this->_db = new MySQLiConnection($dbConnectionConfig);
				break;
		}
	}
	
	// Method to connect to the DataBase server:
	public function connect(&$dbConnectionConfig) {
		return $this->_db->connect($dbConnectionConfig);
	}
	
	// Method to select the DataBase:
	public function select($dbName) {
		return $this->_db->select($dbName);
	}
	
	// Method to determine if an error has occured:
	public function error() {
		return $this->_db->error();
	}
	
	// Method to return the error report:
	public function getError() {
		return $this->_db->getError();
	}
	
	// Method to return the connection identifier:
	public function &getConnectionID() {
		return $this->_db->getConnectionID();
	}
	
	// Method to prevent SQL injections:
	public function makeSafe($value) {
		return $this->_db->makeSafe($value);
	}

	// Method to perform SQL queries:
	public function sqlQuery($sql) {
		return $this->_db->sqlQuery($sql);
	}
	
	// Method to retrieve the current DataBase name:
	public function getName() {
		return $this->_db->getName();
		//If your database has hyphens ( - ) in it, try using this instead:
		//return '`'.$this->_db->getName().'`';
	}

	// Method to retrieve the last inserted ID:
	public function getLastInsertedID() {
		return $this->_db->getLastInsertedID();
	}

}
