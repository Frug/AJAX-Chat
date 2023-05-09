<?php
namespace AjaxChat\Integrations\Standalone;

class CustomAJAXChat extends \AjaxChat\AJAXChat {

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		
		$customUsers = $this->getCustomUsers();
		
		if($this->getRequestVar('password')) {
			// Check if we have a valid registered user:

			$userName = $this->getRequestVar('userName');
			$userName = $this->convertEncoding($userName, $this->getConfig('contentEncoding'), $this->getConfig('sourceEncoding'));

			$password = $this->getRequestVar('password');
			$password = $this->convertEncoding($password, $this->getConfig('contentEncoding'), $this->getConfig('sourceEncoding'));

			foreach($customUsers as $key=>$value) {
				if(($value['userName'] == $userName) && ($value['password'] == $password)) {
					$userData = array();
					$userData['userID'] = $key;
					$userData['userName'] = $this->trimUserName($value['userName']);
					$userData['userRole'] = $value['userRole'];
					return $userData;
				}
			}
			
			return null;
		} else {
			// Guest users:
			return $this->getGuestUser();
		}
	}

	// Store the channels the current user has access to
	// Make sure channel names don't contain any whitespace
	public function getChannels() {
		if($this->_channels !== null) {
			return $this->_channels;
		}

		$this->_channels = [];
			
		$customUsers = $this->getCustomUsers();
		
		// Get the channels, the user has access to:
		if($this->getUserRole() == AJAX_CHAT_GUEST) {
			$validChannels = $customUsers[0]['channels'];
		} else {
			$validChannels = $customUsers[$this->getUserID()]['channels'];
		}

		// Add the valid channels to the channel list (the defaultChannelID is always valid):
		foreach($this->getAllChannels() as $key=>$value) {
			if($value == $this->getConfig('defaultChannelID')) {
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
		
		return $this->_channels;
	}

	// Store all existing channels
	// Make sure channel names don't contain any whitespace
	function getAllChannels() {
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
		}
		return $this->_allChannels;
	}

	public function getCustomUsers() {
		// List containing the registered chat users:
		$users = [];
		require(AJAX_CHAT_PATH.'src/data/users.php');
		return $users;
	}
	
	public function getCustomChannels() {
		// List containing the custom channels:
		$channel_data = file_get_contents(AJAX_CHAT_PATH.'src/data/channels.json');
		$channels = (array)json_decode($channel_data);	
		// Channel array structure should be:
		// ChannelName => ChannelID
		return array_flip($channels);
	}

}
