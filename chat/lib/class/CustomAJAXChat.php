<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

class CustomAJAXChat extends AJAXChat {

    // Returns an associative array containing userName, userID and userRole
    // Returns null if login is invalid
    function getValidLoginUserData() {
        if(isset($_COOKIE['sp'])) {
            $user = new User();
            if ($user->auth($_COOKIE['sp'])) {
					$userData = [];
					$userData['userID'] = $user->getId();
					$userData['userName'] = $this->trimUserName($user->getId());
					$userData['userRole'] = $this->getACUserRole($user->getRole());

                    return $userData;
            }
        }
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

    function getACUserRole($role) {
        if($role == 'ADMIN') {
            return AJAX_CHAT_ADMIN;
        } else {
            return AJAX_CHAT_MODERATOR;
        }
    }

	/*function getSPUserDetails($id) {
        // Retrieve the channel of the given message:
        $sql = 'SELECT
					CONCAT(first_name, \' \', last_name) as name
				FROM
					user
				WHERE
					id='.$id.';';

        // Create a new SQL query:
        $result = $this->_config['primaryConn']->sqlQuery($sql);

        // Stop if an error occurs:
        if($result->error()) {
            echo $result->getError();
            die();
        }

        $row = $result->fetch();
        return isset($row['name']) ? $row['name'] : '';
    }*/

}
