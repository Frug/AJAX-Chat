<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 * 
 * SMF integration:
 * http://www.simplemachines.org/
 */

class CustomAJAXChat extends AJAXChat {

	// Initialize custom configuration settings
	function initCustomConfig() {
		global $db_name,$db_connection;
		
		// Use the existing SMF database connection:
		$this->setConfig('dbConnection', 'link', $db_connection);
	}

	// Override the database connection method to make sure the SMF database is selected:
	function initDataBaseConnection() {
		global $db_name;

		// Call the parent method to initialize the database connection:
		parent::initDataBaseConnection();

		// Select the SMF database:
		$this->db->select($db_name);
		if($this->db->error()) {
			echo $this->db->getError();
			die();
		}
	}

	// Initialize custom request variables:
	function initCustomRequestVars() {
		global $context;

		// Auto-login phpBB users:
		if(!$this->getRequestVar('logout') && !$context['user']['is_guest']) {
			$this->setRequestVar('login', true);
		}
	}

	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {
		global $context,$boardurl;
		
		switch($tag) {

			case 'FORUM_LOGIN_URL':
				if(!$context['user']['is_guest']) {
					return ($this->getRequestVar('view') == 'logs') ? './?view=logs' : './';
				} else {
					return $this->htmlEncode($boardurl).'/index.php?action=login2';
				}

				
			case 'REDIRECT_URL':
				if(!$context['user']['is_guest']) {
					return '';
				} else {
					$redirectURL = $this->getRequestVar('view') == 'logs' ? $this->getChatURL().'?view=logs' : $this->getChatURL();
					if(!$this->getRequestVar('logout')) {
						// Set the redirect URL after login to the chat:
						ssi_login($redirectURL, false);
					} else {
						// Reset the redirect URL on logout:
						ssi_login($boardurl.'/index.php', false);
					}
					return $redirectURL;
				}
			
			default:
				return null;
		}
	}

	// Returns true if the userID of the logged in user is identical to the userID of the authentication system
	// or the user is authenticated as guest in the chat and the authentication system
	function revalidateUserID() {
		global $context;
		
		if($this->getUserRole() === AJAX_CHAT_GUEST && $context['user']['is_guest'] || ($this->getUserID() === $context['user']['id'])) {
			return true;
		}
		return false;
	}

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		global $context,$user_info;
		
		// Check if we have a valid registered user:
		if(!$context['user']['is_guest']) {
			$userData = array();
			$userData['userID'] = $context['user']['id'];
			
			$userData['userName'] = $this->trimUserName($context['user']['name']);
			
			if($context['user']['is_admin'])
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			// $context['user']['is_mod'] is always false if no board is loaded.
			// As a workaround we check the permission 'calendar_post'.
			// This is only set to true for global moderators by default: 
			elseif(in_array('calendar_post', $user_info['permissions']))
				$userData['userRole'] = AJAX_CHAT_MODERATOR;
			else
				$userData['userRole'] = AJAX_CHAT_USER;

			return $userData;
			
		} else {
			// Guest users:
			return $this->getGuestUser();
		}
	}

	// Store the channels the current user has access to
	// Make sure channel names don't contain any whitespace
	function &getChannels() {
		if($this->_channels === null) {
			global $db_prefix,$user_info;
			
			$this->_channels = array();
			
			$sql = 'SELECT
						ID_BOARD,
						name
					FROM
						'.$db_prefix.'boards AS b
					WHERE
						'.$user_info['query_see_board'].';';

			// Create a new SQL query:
			$result = $this->db->sqlQuery($sql);
			
			// Stop if an error occurs:
			if($result->error()) {
			    echo $result->getError();
			    die();
			}

			$defaultChannelFound = false;
						
			while($row = $result->fetch()) {
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($row['ID_BOARD'], $this->getConfig('limitChannelList'))) {
					continue;
				}

				$forumName = $this->trimChannelName($row['name']);
				
				$this->_channels[$forumName] = $row['ID_BOARD'];

				if(!$defaultChannelFound && $row['ID_BOARD'] == $this->getConfig('defaultChannelID')) {
					$defaultChannelFound = true;
				}
			}		
			$result->free();

			if(!$defaultChannelFound) {
				// Add the default channel as first array element to the channel list:
				$this->_channels = array_merge(
					array(
						$this->trimChannelName($this->getConfig('defaultChannelName'))=>$this->getConfig('defaultChannelID')
					),
					$this->_channels
				);
			}
		}
		return $this->_channels;
	}

	// Store all existing channels
	// Make sure channel names don't contain any whitespace
	function &getAllChannels() {
		if($this->_allChannels === null) {
			global $db_prefix,$user_info;
			
			$this->_allChannels = array();
			
			$sql = 'SELECT
						ID_BOARD,
						name
					FROM
						'.$db_prefix.'boards;';

			// Create a new SQL query:
			$result = $this->db->sqlQuery($sql);
			
			// Stop if an error occurs:
			if($result->error()) {
			    echo $result->getError();
			    die();
			}

			$defaultChannelFound = false;
						
			while($row = $result->fetch()) {
				$forumName = $this->trimChannelName($row['name']);
				
				$this->_allChannels[$forumName] = $row['ID_BOARD'];

				if(!$defaultChannelFound && $row['ID_BOARD'] == $this->getConfig('defaultChannelID')) {
					$defaultChannelFound = true;
				}
			}		
			$result->free();

			if(!$defaultChannelFound) {
				// Add the default channel as first array element to the channel list:
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

	// Method to set the style cookie depending on the SMF user style:
	function setStyle() {
		global $db_prefix,$settings;
		
		if(isset($_COOKIE[$this->getConfig('sessionName').'_style']) && in_array($_COOKIE[$this->getConfig('sessionName').'_style'], $this->getConfig('styleAvailable')))
			return;
		
		$sql = 'SELECT
						value
					FROM
						'.$db_prefix.'themes
					WHERE
						ID_THEME = '.$this->db->makeSafe($settings['theme_id']).'
					AND
						variable = \'name\';';

		// Create a new SQL query:
		$result = $this->db->sqlQuery($sql);
			
		// Stop if an error occurs:
		if($result->error()) {
		    echo $result->getError();
		    die();
		}
		
		$row = $result->fetch();
		$styleName = $row['value'];
		
		$result->free();
		
		if(!in_array($styleName, $this->getConfig('styleAvailable'))) {
			$styleName = $this->getConfig('styleDefault');
		}
		
		setcookie(
			$this->getConfig('sessionName').'_style',
			$styleName,
			time()+60*60*24*$this->getConfig('sessionCookieLifeTime'),
			$this->getConfig('sessionCookiePath'),
			$this->getConfig('sessionCookieDomain'),
			$this->getConfig('sessionCookieSecure')
		);
		return;
	}

}
