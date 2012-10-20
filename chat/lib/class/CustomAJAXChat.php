<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 * 
 * phpBB3 integration:
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
		global $user;

		// Auto-login phpBB users:
		if(!$this->getRequestVar('logout') && ($user->data['user_id'] != ANONYMOUS)) {
			$this->setRequestVar('login', true);
		}
	}

	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {
		global $user;
		
		switch($tag) {

			case 'FORUM_LOGIN_URL':
				if($user->data['is_registered']) {
					return ($this->getRequestVar('view') == 'logs') ? './?view=logs' : './';
				} else {
					return $this->htmlEncode(generate_board_url().'/ucp.php?mode=login');
				}
				
			case 'REDIRECT_URL':
				if($user->data['is_registered']) {
					return '';
				} else {
					return $this->htmlEncode($this->getRequestVar('view') == 'logs' ? $this->getChatURL().'?view=logs' : $this->getChatURL());
				}
			
			default:
				return null;
		}
	}

	// Returns true if the userID of the logged in user is identical to the userID of the authentication system
	// or the user is authenticated as guest in the chat and the authentication system
	function revalidateUserID() {
		global $user;
		
		if($this->getUserRole() === AJAX_CHAT_GUEST && $user->data['user_id'] == ANONYMOUS || ($this->getUserID() === $user->data['user_id'])) {
			return true;
		}
		return false;
	}

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		global $auth,$user;
		
		// Return false if given user is a bot:
		if($user->data['is_bot']) {
			return false;
		}
		
		// Check if we have a valid registered user:
		if($user->data['is_registered']) {
			$userData = array();
			$userData['userID'] = $user->data['user_id'];

			$userData['userName'] = $this->trimUserName($user->data['username']);
			
			if($auth->acl_get('a_'))
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			elseif($auth->acl_get('m_'))
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
			global $auth;

			$this->_channels = array();

			$allChannels = $this->getAllChannels();

			foreach($allChannels as $key=>$value) {
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($value, $this->getConfig('limitChannelList'))) {
					continue;
				}

				// Add the valid channels to the channel list (the defaultChannelID is always valid):
				if($value == $this->getConfig('defaultChannelID') || $auth->acl_get('f_read', $value)) {
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
							forum_type=1
						AND
							forum_password=\'\';';
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
		global $config,$user,$db;
		
		if(isset($_COOKIE[$this->getConfig('sessionName').'_style']) && in_array($_COOKIE[$this->getConfig('sessionName').'_style'], $this->getConfig('styleAvailable')))
			return;
		
		$styleID = (!$config['override_user_style'] && $user->data['user_id'] != ANONYMOUS) ? $user->data['user_style'] : $config['default_style'];
		$sql = 'SELECT
						style_name
					FROM
						'.STYLES_TABLE.'
					WHERE
						style_id = \''.$db->sql_escape($styleID).'\';';
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