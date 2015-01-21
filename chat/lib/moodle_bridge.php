<?php
/*
 * @package AJAX_Chat
 * @author Paolo Rizzello
 * @copyright (c) Paolo Rizzello
 * @license Modified MIT License
 */

/*
 * This singleton object implements the integration logic.
 */
class MoodleBridge {
	private $_users;
	private $_currentUser;

	public static function Get() {
	        static $inst = null;
        	if ($inst === null) {
			$inst = new MoodleBridge();
		}
		return $inst;
	}

	private function __construct() {
		require_once(dirname(__FILE__).'/../../config.php'); //Moodle Config File

		require_login(); //this one will show moodle login too
		
		$this->_currentUser = $USER->id; //Set Current Logged user
		
		$this->_users = array();		

		$this->setAllUsers($DB);
		$this->setAllAdmins(); //This one will override the admins stored as normal users
	}
	
	private function setAllUsers($db) {
		$users = $db->get_records('user');
		foreach ($users as $user) {
			$chatUser = $this->convertUser($user, false);
			$this->_users[$chatUser["userID"]] = $chatUser;
		}
	}
	
	private function setAllAdmins() {
		$admins = get_admins();
		
		foreach ($admins as $admin) {
			$chatUser = $this->convertUser($admin, true);
			$this->_users[$chatUser["userID"]] = $chatUser;
		}
	}
	
	/*
	 * Converts a moodle user object in an ajax chat user data
	 */
	private function convertUser($moodleUser, $isAdmin = false) {
		$userData = array();
		$userData['userID'] = $moodleUser->id;
		$userData['userName'] = $moodleUser->username;
		
		if($isAdmin) {
			$userData['userRole'] = AJAX_CHAT_ADMIN; 
			$userData['channels'] = array(0,1);
		} else {
			$userData['userRole'] = AJAX_CHAT_USER;
			$userData['channels'] = array(0);
		}
		return $userData;
	}	
	
	/*
 	 * Returns the current logged user data
 	 */
	public function CurrentUser() {
		return $this->_users[$this->_currentUser];
	}
	
	/*
 	 * Returns all moodle users as chat users
 	 */
	public function AllUsers() {
		return $this->_users;
	}
	
	/*
 	 * Returns all moodle courses as channels
 	 */
	public function AllChannels() {
		//Not yet implemented
	}
}


?>
