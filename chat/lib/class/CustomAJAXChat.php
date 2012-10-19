<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 * 
 * vBulletin integration:
 * http://www.vbulletin.com/
 */

class CustomAJAXChat extends AJAXChat {

	// Initialize custom configuration settings
	function initCustomConfig() {
		global $db;
		
		// Use the existing vBulletin database connection:
		$this->setConfig('dbConnection', 'link', $db->connection_master);
	}

	// Initialize custom request variables:
	function initCustomRequestVars() {
		global $vbulletin;

		// Auto-login vBulletin users:
		if(!$this->getRequestVar('logout') && $vbulletin->userinfo['userid']) {
			$this->setRequestVar('login', true);
		}
	}

	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {
		global $vbulletin;
		
		switch($tag) {

			case 'FORUM_LOGIN_URL':
				if($vbulletin->userinfo['userid']) {
					return ($this->getRequestVar('view') == 'logs') ? './?view=logs' : './';
				} else {
					return $this->htmlEncode($vbulletin->options['bburl']).'/login.php?do=login';
				}
				
			case 'REDIRECT_URL':
				if($vbulletin->userinfo['userid']) {
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
		global $vbulletin;
		
		if($this->getUserRole() === AJAX_CHAT_GUEST && !$vbulletin->userinfo['userid'] || ($this->getUserID() === $vbulletin->userinfo['userid'])) {
			return true;
		}
		return false;
	}

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		global $vbulletin;
		
		// Check if we have a valid registered user:
		if($vbulletin->userinfo['userid']) {
			$userData = array();
			$userData['userID'] = $vbulletin->userinfo['userid'];

			$userData['userName'] = $this->trimUserName($vbulletin->userinfo['username']);
			
			if($vbulletin->userinfo['permissions']['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['cancontrolpanel'])
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			elseif($vbulletin->userinfo['permissions']['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['ismoderator'])
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
			global $vbulletin;

			$this->_channels = array();

			$allChannels = $this->getAllChannels();

			foreach($allChannels as $key=>$value) {
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($value, $this->getConfig('limitChannelList'))) {
					continue;
				}

				// Add the valid channels to the channel list (the defaultChannelID is always valid):
				if(
					$value == $this->getConfig('defaultChannelID') ||
					(($vbulletin->userinfo['forumpermissions']["$value"] & $vbulletin->bf_ugp_forumpermissions['canview']) &&
					($vbulletin->userinfo['forumpermissions']["$value"] & $vbulletin->bf_ugp_forumpermissions['canviewthreads']))
				) {
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

			// Get valid vBulletin forums (skip categories and password-protected forums):
			$sql = 'SELECT
							forumid,
							title
						FROM
							'.TABLE_PREFIX.'forum
						WHERE
							options & 4
						AND
							password=\'\';';
			$result = $db->query_read_slave($sql);

			$defaultChannelFound = false;

			while ($row = $db->fetch_array($result)) {
				$forumName = $this->trimChannelName($row['title']);

				$this->_allChannels[$forumName] = $row['forumid'];

				if(!$defaultChannelFound && $row['forumid'] == $this->getConfig('defaultChannelID')) {
					$defaultChannelFound = true;
				}
			}
			$db->free_result($result);

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

	// Method to set the style cookie depending on the vBulletin user style
	function setStyle() {
		global $style;
				
		if(isset($_COOKIE[$this->getConfig('sessionName').'_style']) && in_array($_COOKIE[$this->getConfig('sessionName').'_style'], $this->getConfig('styleAvailable')))
			return;
		
		$styleName = $style['title'];
		
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