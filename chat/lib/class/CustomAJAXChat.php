<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 * 
 * phpBB2 integration:
 * http://www.phpbb.com/
 */

class CustomAJAXChat extends AJAXChat {

	// Initialize custom configuration settings
	function initCustomConfig() {
		global $db;
		
		// Use the existing phpBB database connection:
		$this->setConfig('dbConnection', 'link', $db->db_connect_id);
	}

	// Initialize custom request variables:
	function initCustomRequestVars() {
		global $userdata;

		// Auto-login phpBB users:
		if(!$this->getRequestVar('logout') && ($userdata['user_id'] != ANONYMOUS)) {
			$this->setRequestVar('login', true);
		}
	}

	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {
		global $userdata;
		
		switch($tag) {

			case 'FORUM_LOGIN_URL':
				if($userdata['session_logged_in']) {
					return ($this->getRequestVar('view') == 'logs') ? './?view=logs' : './';
				} else {
					return '../login.php';
				}
				
			case 'REDIRECT_URL':
				if($userdata['session_logged_in']) {
					return '';
				} else {
					return ($this->getRequestVar('view') == 'logs' ? 'chat/?view=logs' : 'chat/');
				}
			
			default:
				return null;
		}
	}

	// Returns true if the userID of the logged in user is identical to the userID of the authentication system
	// or the user is authenticated as guest in the chat and the authentication system
	function revalidateUserID() {
		global $userdata;
		
		if($this->getUserRole() === AJAX_CHAT_GUEST && !$userdata['session_logged_in'] || ($this->getUserID() === $userdata['user_id'])) {
			return true;
		}
		return false;
	}

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		global $userdata;
		
		// Check if we have a valid registered user:
		if($userdata['session_logged_in']) {
			$userData = array();
			$userData['userID'] = $userdata['user_id'];
				
			$userData['userName'] = $this->trimUserName($userdata['username']);
			
			if($userdata['user_level'] == ADMIN)
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			elseif($userdata['user_level'] == MOD)
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
			global $userdata;

			$this->_channels = array();

			$allChannels = $this->getAllChannels();

			foreach($allChannels as $key=>$value) {
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($value, $this->getConfig('limitChannelList'))) {
					continue;
				}

				// Get the persmissions for the current forum_id:
				$auth = auth(AUTH_READ, $value, $userdata);

				// Add the valid channels to the channel list (the defaultChannelID is always valid):
				if($auth['auth_read'] ||  $value == $this->getConfig('defaultChannelID')) {
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
			global $db;

			$this->_allChannels = array();

			// Get valid phpBB forums:
			$sql = 'SELECT
							forum_id,
							forum_name
						FROM
							'.FORUMS_TABLE.'
						WHERE
							forum_status=0;';
			$result = $db->sql_query($sql);

			$defaultChannelFound = false;
			
			while ($row = $db->sql_fetchrow($result)) {
				$forumName = $this->trimChannelName($row['forum_name']);

				$this->_allChannels[$forumName] = $row['forum_id'];

				if(!$defaultChannelFound && $row['forum_id'] == $this->getConfig('defaultChannelID')) {
					$defaultChannelFound = true;
				}
			}
			$db->sql_freeresult($result);
			
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

	// Method to set the style cookie depending on the phpBB user style
	function setStyle() {
		global $db,$board_config,$userdata;
	
		if(isset($_COOKIE[$this->getConfig('sessionName').'_style']) && in_array($_COOKIE[$this->getConfig('sessionName').'_style'], $this->getConfig('styleAvailable')))
			return;
		
		$styleID = (!$board_config['override_user_style'] && $userdata['user_id'] != ANONYMOUS && $userdata['user_style'] > 0) ? $userdata['user_style'] : $board_config['default_style'];
		$sql = 'SELECT
						style_name
					FROM
						'.THEMES_TABLE.'
					WHERE
						themes_id = '.$this->db->makeSafe($styleID).';';
		$result = $db->sql_query($sql);
		$styleName = $db->sql_fetchfield('style_name');
		$db->sql_freeresult($result);
		
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
?>