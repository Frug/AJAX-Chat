<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 */

// Class to perform SQL (MySQL) queries:
class AJAXChatPostgreSQLQuery {

	var $_connectionID;
	var $_sql = '';
	var $_result = 0;
	var $_errno = 0;
	var $_error = '';

	// Constructor:
	function AJAXChatPostgreSQLQuery($sql, $connectionID = null) {
		$this->_sql = trim($sql);
		$this->_connectionID = $connectionID;
		if($this->_connectionID) {
			$this->_result = pg_query($this->_connectionID, $this->_sql);
			if(!$this->_result) {
				error_log($this->_sql);
				$this->_error = pg_last_error($this->_connectionID) . pg_result_error();
			}
		} else {
			$this->_result = pg_query($this->_sql);
			if(!$this->_result) {
				error_log($this->_sql);
				$this->_error = pg_last_error($this->_connectionID) . pg_result_error();
			}
		}
	}

	// Returns true if an error occured:
	function error() {
		// Returns true if the Result-ID is valid:
		return !(bool)($this->_result);
	}

	// Returns an Error-String:
	function getError() {
		if($this->error()) {
			$str  = 'Query: '	 .$this->_sql  ."\n";
			$str .= 'Error-Report: '	.$this->_error."\n";
		} else {
			$str = "No errors.";
		}
		return $str;
	}

	// Returns the content:
	function fetch() {
		if($this->error()) {
			return null;
		} else {
			return pg_fetch_assoc($this->_result);
		}
	}

	// Returns the number of rows (SELECT or SHOW):
	function numRows() {
		if($this->error()) {
			return null;
		} else {
			return pg_num_rows($this->_result);
		}
	}

	// Returns the number of affected rows (INSERT, UPDATE, REPLACE or DELETE):
	function affectedRows() {
		if($this->error()) {
			return null;
		} else {
			return pg_affected_rows($this->_connectionID);
		}
	}

	// Frees the memory:
	function free() {
		@pg_free_result($this->_result);
	}

}
?>
