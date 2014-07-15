<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

class CustomAJAXChat extends AJAXChat {

	// Initialize custom configuration settings
	function initCustomConfig() {
		
		// Use the existing WordPress database connection:
		$this->setConfig('dbConnection', 'host', DB_HOST);
		$this->setConfig('dbConnection', 'user', DB_USER);
		$this->setConfig('dbConnection', 'pass', DB_PASSWORD);
		$this->setConfig('dbConnection', 'name', DB_NAME);

	}

	// Initialize custom request variables:
	function initCustomRequestVars(){
		if( !$this->getRequestVar('logout') && is_user_logged_in() ) {
			$this->setRequestVar('login', true);
		}
	}
	
	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {
		global $user;
		
		switch($tag) {
			case 'LOGIN_URL':
				return '../wp-login.php';
			case 'REDIRECT_URL':
				return 'chat/';
			default:
				return null;
		}
	}
	
	// Returns an associative array containing userName, userID and userRole
	// Returns guest login if null
	function getValidLoginUserData() {
		global $current_user; // get variables from WP


		// Check if we have a valid registered user:
		$customUsers = $this->getCustomUsers();
		if( isset( $current_user ) && is_user_logged_in() ){
			$userData 				= 	array();
			$userData['userID']		=	$current_user->data->ID;
			$userData['userName']	=	$this->trimUserName( $current_user->data->user_login );
			if( current_user_can('install_plugins') ){
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			}else if( current_user_can('edit_published_posts') || 
						current_user_can('edit_posts') ){ 
				$userData['userRole'] = AJAX_CHAT_MODERATOR;
				}else{
				$userData['userRole'] = AJAX_CHAT_USER;
			}
			return $userData;
		}
		if( $this->getRequestVar('password') ) {
				$userName = $this->getRequestVar('userName');
				$userName = $this->convertEncoding($userName, $this->getConfig('contentEncoding'), $this->getConfig('sourceEncoding'));
				
				$password = $this->getRequestVar('password');
				$password = $this->convertEncoding($password, $this->getConfig('contentEncoding'), $this->getConfig('sourceEncoding'));
				
				foreach( $customUsers as $key => $value ) {
					if( ($value['userName'] == $userName) && ($value['password'] == $password) ) {
						$userData = array();
						$userData['userID'] = $key;
						$userData['userName'] = $this->trimUserName($value['userName']);
						
						$userData['userRole'] = $value['userRole'];
						return $userData;
					}
				}
				return null;
			}else{
				// Guest users:
				return $this->getGuestUser();
			}
	}
	function getGuestUser() {
		if(!$this->getConfig('allowGuestLogins'))
		return null;
		
		if($this->getConfig('allowGuestUserName')) {
			$maxLength =	$this->getConfig('userNameMaxLength')
			- $this->stringLength($this->getConfig('guestUserPrefix'))
			- $this->stringLength($this->getConfig('guestUserSuffix'));
			
			$userName	=$this->getRequestVar('userName');
			
			// Trim guest userName:
			$userName = $this->trimString($this->getRequestVar('userName'), null, $maxLength, true, true);
			
			// check if usernick choosen as guest is in the WP database.. if its is Deny access.
			if( username_exists( $userName ) ){
				return null;
			}
			
			// If given userName is invalid, create one:
			if(!$userName) {
				$userName = $this->createGuestUserName();
			} else {
				// Add the guest users prefix and suffix to the given userName:
				$userName = $this->getConfig('guestUserPrefix').$userName.$this->getConfig('guestUserSuffix');	
			}
		} else {
			$userName = $this->createGuestUserName();
		}
		
		$userData = array();
		$userData['userID'] = $this->createGuestUserID();
		$userData['userName'] = $userName;
		$userData['userRole'] = AJAX_CHAT_GUEST;
		return $userData;		
	}

	// Store the channels the current user has access to
	// Make sure channel names don't contain any whitespace
	function &getChannels() {
		if($this->_channels === null) {
			$this->_channels = array();
			
			$customUsers = $this->getCustomUsers();
			
			// Get the channels, the user has access to:
			if($this->getUserRole() == AJAX_CHAT_GUEST) {
				$validChannels = $customUsers[0]['channels'];
			} else {
				$validChannels = $customUsers[$this->getUserID()]['channels'];
			}
			
			// Add the valid channels to the channel list (the defaultChannelID is always valid):
			foreach($this->getAllChannels() as $key=>$value) {
				if ($value == $this->getConfig('defaultChannelID')) {
					$this->_channels[$key] = $value;
					continue;
				}
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($value, $this->getConfig('limitChannelList'))) {
					continue;
				}
				if(in_array($value, $validChannels)) {
					$this->_channels[$key] = $value;
				}
			}
			$this->_channels = array_merge($this->_channels, $this->getCustomChannels());
		}
		return $this->_channels;
	}

	// Store all existing channels
	// Make sure channel names don't contain any whitespace
	function &getAllChannels() {
		if($this->_allChannels === null) {
			// Get all existing channels:
			$customChannels = $this->getCustomChannels();
			
			$defaultChannelFound = false;
			
			foreach($customChannels as $name=>$id) {
				$this->_allChannels[$this->trimChannelName($name)] = $id;
				if($id == $this->getConfig('defaultChannelID')) {
					$defaultChannelFound = true;
				}
			}
			
			if(!$defaultChannelFound) {
				// Add the default channel as first array element to the channel list
				// First remove it in case it appeard under a different ID
				unset($this->_allChannels[$this->getConfig('defaultChannelName')]);
				$this->_allChannels = array_merge(
					array(
						$this->trimChannelName($this->getConfig('defaultChannelName'))=>$this->getConfig('defaultChannelID')
					),
					$this->_allChannels
				);
			}
			$this->_allChannels = array_merge($this->_allChannels, $this->getCustomChannels());
		}
		return $this->_allChannels;
	}

	function &getCustomUsers() {
		// List containing the registered chat users:
		$users = null;
		require(AJAX_CHAT_PATH.'lib/data/users.php');
		return $users;
	}
	
	function getCustomChannels() {
		// List containing the custom channels:
		$channels = null;
		require(AJAX_CHAT_PATH.'lib/data/channels.php');
		// Channel array structure should be:
		// ChannelName => ChannelID
		return array_flip($channels);
	}

}
