<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 * 
 * MyBB integration:
 * http://www.mybboard.net/
 */

class CustomAJAXChat extends AJAXChat {

	// Initialize custom configuration settings
	function initCustomConfig() {
		global $db;
		
		// Use the existing MyBB database connection:
		$this->setConfig('dbConnection', 'link', $db->link);
	}

	// Initialize custom request variables:
	function initCustomRequestVars() {
		global $mybb;

		// Auto-login MyBB users:
		if(!$this->getRequestVar('logout') && $mybb->user['uid']) {
			$this->setRequestVar('login', true);
		}
	}

	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {		
		global $mybb;
		
		switch($tag) {

			case 'FORUM_LOGIN_URL':
				if($mybb->user['uid']) {
					return ($this->getRequestVar('view') == 'logs') ? './?view=logs' : './';
				} else {
					return $mybb->settings['bburl'].'/member.php';
				}
				
			case 'REDIRECT_URL':
				if($mybb->user['uid']) {
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
		global $mybb;
		
		if($this->getUserRole() === AJAX_CHAT_GUEST && !$mybb->user['uid'] || ($this->getUserID() === $mybb->user['uid'])) {
			return true;
		}
		return false;
	}

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		global $mybb;
		
		// Check if we have a valid registered user:
		if($mybb->user['uid']) {
			$userData = array();
			$userData['userID'] = $mybb->user['uid'];

			$userData['userName'] = $this->trimUserName($mybb->user['username']);
			
			// Take the userrole from the MyBB users primary group:
			if($mybb->user['usergroup'] == 4)
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			else if($mybb->user['usergroup'] == 3)
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
			$this->_channels = array();

			$allChannels = $this->getAllChannels();

			// Build the forum permissions for all forums:
			$forumPermissions = forum_permissions();

			foreach($allChannels as $key=>$value) {
				if ($value == $this->getConfig('defaultChannelID')) {
					$this->_channels[$key] = $value;
					continue;
				}
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($value, $this->getConfig('limitChannelList'))) {
					continue;
				}

				// Add the valid channels to the channel list (the defaultChannelID is always valid):
				if($forumPermissions[$value]['canview'] == 'yes' ||  $value == $this->getConfig('defaultChannelID')) {
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

			// Get all MyBB forums:
			$sql = 'SELECT
							fid,
							name
						FROM
							'.TABLE_PREFIX.'forums
						WHERE
							type=\'f\';';
			$result = $db->query($sql);

			$defaultChannelFound = false;

			while ($row = $db->fetch_array($result)) {
				$forumName = $this->trimChannelName($row['name']);

				$this->_allChannels[$forumName] = $row['fid'];

				if(!$defaultChannelFound && $row['fid'] == $this->getConfig('defaultChannelID')) {
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

	// Method to set the style cookie depending on the MyBB user style
	function setStyle() {
		global $theme;
		
		if(isset($_COOKIE[$this->getConfig('sessionName').'_style']) && in_array($_COOKIE[$this->getConfig('sessionName').'_style'], $this->getConfig('styleAvailable')))
			return;

		$styleName = $theme['name'];
		
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