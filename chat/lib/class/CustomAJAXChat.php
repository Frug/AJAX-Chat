<?php

class CustomAJAXChat extends AJAXChat {

    private $feedModel;

    function initDataBaseConnection() {
        parent::initDataBaseConnection();
        $this->feedModel = new FeedEntryModel($this->db, $this->getDataBaseTable('messages'));
    }

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

            // To-do : based on requirement we may need to give more channels
			$validChannels = array(0);

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

    function insertCustomMessage($userID, $userName, $userRole, $channelID, $text, $ip=null, $mode=0) {
        $this->feedModel->insert($userID, $userName, $userRole, $channelID, $text, $this->ipToStorageFormat($ip), $mode);

		if($this->getConfig('socketServerEnabled')) {
			$this->sendSocketMessage(
				$this->getSocketBroadcastMessage(
					$this->db->getLastInsertedID(),
					time(),
					$userID,
					$userName,
					$userRole,
					$channelID,
					$text,
					$mode
				)
			);
		}
	}

    function getChatViewMessagesXML() {
        // Get the last messages in descending order (this optimises the LIMIT usage):
        $result =  $this->feedModel->select($this->getTeaserMessageCondition(), $this->getMessageFilter(), $this->getConfig('requestMessagesLimit'));

        $messages = '';

        // Add the messages in reverse order so it is ascending again:
        while($row = $result->fetch()) {
            $entry_data = json_decode($row['id'],true);
            $message = $this->getChatViewMessageXML(
                $row['id'],
                $row['timeStamp'],
                $row['userID'],
                $row['userName'],
                $entry_data['userRole'],
                $row['channelID'],
                $row['text']
            );
            $messages = $message.$messages;
        }
        $result->free();

        $messages = '<messages>'.$messages.'</messages>';
        return $messages;
    }

    function getTeaserViewMessagesXML() {
        // Get the last messages in descending order (this optimises the LIMIT usage):

        $result =  $this->feedModel->select($this->getMessageCondition(), $this->getMessageFilter(), $this->getConfig('requestMessagesLimit'));
        $messages = '';

        // Add the messages in reverse order so it is ascending again:
        while($row = $result->fetch()) {
            $entry_data = json_decode($row['id'],true);
            $message = '';
            $message .= '<message';
            $message .= ' id="'.$row['id'].'"';
            $message .= ' dateTime="'.date('r', $row['timeStamp']).'"';
            $message .= ' userID="'.$row['userID'].'"';
            $message .= ' userRole="'.$entry_data['userRole'].'"';
            $message .= ' channelID="'.$row['channelID'].'"';
            $message .= '>';
            $message .= '<username><![CDATA['.$this->encodeSpecialChars($row['userName']).']]></username>';
            $message .= '<text><![CDATA['.$this->encodeSpecialChars($row['text']).']]></text>';
            $message .= '</message>';
            $messages = $message.$messages;
        }
        $result->free();

        $messages = '<messages>'.$messages.'</messages>';
        return $messages;
    }

    function getLogsViewMessagesXML() {

        $result =  $this->feedModel->select($this->getTeaserMessageCondition(), $this->getMessageFilter(), $this->getConfig('requestMessagesLimit'));

        $xml = '<messages>';
        while($row = $result->fetch()) {
            $entry_data = json_decode($row['id'],true);
            $xml .= '<message';
            $xml .= ' id="'.$row['id'].'"';
            $xml .= ' dateTime="'.date('r', $row['timeStamp']).'"';
            $xml .= ' userID="'.$row['userID'].'"';
            $xml .= ' userRole="'.$entry_data['userRole'].'"';
            $xml .= ' channelID="'.$row['channelID'].'"';
            if($this->getUserRole() == AJAX_CHAT_ADMIN || $this->getUserRole() == AJAX_CHAT_MODERATOR) {
                $xml .= ' ip="'.$this->ipFromStorageFormat($row['ip']).'"';
            }
            $xml .= '>';
            $xml .= '<username><![CDATA['.$this->encodeSpecialChars($row['userName']).']]></username>';
            $xml .= '<text><![CDATA['.$this->encodeSpecialChars($row['text']).']]></text>';
            $xml .= '</message>';
        }
        $result->free();

        $xml .= '</messages>';

        return $xml;
    }

    function getMessageCondition() {
        $condition = 	'dateTime > '.$this->db->makeSafe($this->getRequestVar('lastID')).'
						AND (
							parent_id_code = '.$this->db->makeSafe($this->getChannel()).'
							OR
							parent_id_code = '.$this->db->makeSafe($this->getPrivateMessageID()).'
						)
						AND
						';
        if($this->getConfig('requestMessagesPriorChannelEnter') ||
            ($this->getConfig('requestMessagesPriorChannelEnterList') && in_array($this->getChannel(), $this->getConfig('requestMessagesPriorChannelEnterList')))) {
            $condition .= 'NOW() < DATE_ADD(dateTime, interval '.$this->getConfig('requestMessagesTimeDiff').' HOUR)';
        } else {
            $condition .= 'dateTime >= FROM_UNIXTIME(' . $this->getChannelEnterTimeStamp() . ')';
        }
        return $condition;
    }

    function getTeaserMessageCondition() {
        $channelID = $this->getValidRequestChannelID();
        $condition = 	'parent_id_code = '.$this->db->makeSafe($channelID).'
						AND
						';
        if($this->getConfig('requestMessagesPriorChannelEnter') ||
            ($this->getConfig('requestMessagesPriorChannelEnterList') && in_array($channelID, $this->getConfig('requestMessagesPriorChannelEnterList')))) {
            $condition .= 'NOW() < DATE_ADD(entry_time, interval '.$this->getConfig('requestMessagesTimeDiff').' HOUR)';
        } else {
            // Teaser content may not be shown for this channel:
            $condition .= '0 = 1';
        }
        return $condition;
    }

    function purgeLogs() {

        echo "can't delete messages";
        exit;
    }

    function deleteMessage($messageID) {
        echo "can't delete messages";
        exit;
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
