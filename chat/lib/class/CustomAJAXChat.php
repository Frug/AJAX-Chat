<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 * 
 * PunBB integration:
 * http://punbb.org/
 */

class CustomAJAXChat extends AJAXChat {

	// Initialize custom configuration settings
	function initCustomConfig() {
		global $db;
		
		// Use the existing PunBB database connection:
		$this->setConfig('dbConnection', 'link', $db->link_id);
	}

	// Initialize custom request variables:
	function initCustomRequestVars() {
		global $pun_user;

		// Auto-login phpBB users:
		if(!$this->getRequestVar('logout') && !$pun_user['is_guest']) {
			$this->setRequestVar('login', true);
		}
	}

	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {
		global $pun_user,$pun_config;

		switch($tag) {

			case 'FORUM_LOGIN_URL':
				if(!$pun_user['is_guest']) {
					return ($this->getRequestVar('view') == 'logs') ? './?view=logs' : './';
				} else {
					return $this->htmlEncode($pun_config['o_base_url'].'/login.php?action=in');
				}
				
			case 'REDIRECT_URL':
				if(!$pun_user['is_guest']) {
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
		global $pun_user;
		
		if($this->getUserRole() === AJAX_CHAT_GUEST && $pun_user['is_guest'] || ($this->getUserID() === $pun_user['id'])) {
			return true;
		}
		return false;
	}

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		global $pun_user;
		
		// Check if we have a valid registered user:
		if(!$pun_user['is_guest']) {
			$userData = array();
			$userData['userID'] = $pun_user['id'];
			
			$userData['userName'] = $this->trimUserName($pun_user['username']);
			
			if($pun_user['g_id'] == PUN_ADMIN)
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			elseif($pun_user['g_id'] == PUN_MOD)
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
			global $db,$pun_user;
			
			$this->_channels = array();
			
			// Get valid PunBB forums:
			$sql = 'SELECT
						id,
						forum_name
					FROM
						'.$db->prefix.'forums AS f
					LEFT JOIN
						'.$db->prefix.'forum_perms AS fp
					ON
						(fp.forum_id=f.id AND fp.group_id=\''.$db->escape($pun_user['g_id']).'\')
					WHERE
						(fp.read_forum IS NULL OR fp.read_forum=1);';
			$result = $db->query($sql);

			$defaultChannelFound = false;

			while ($row = $db->fetch_assoc($result)) {
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($row['id'], $this->getConfig('limitChannelList'))) {
					continue;
				}

				$forumName = $this->trimChannelName($row['forum_name']);
				
				$this->_channels[$forumName] = $row['id'];

				if(!$defaultChannelFound && $row['id'] == $this->getConfig('defaultChannelID')) {
					$defaultChannelFound = true;
				}
			}
			$db->free_result($result);

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
			global $db,$pun_user;
			
			$this->_allChannels = array();
			
			// Get all PunBB forums:
			$sql = 'SELECT
						id,
						forum_name
					FROM
						'.$db->prefix.'forums;';
			$result = $db->query($sql);

			$defaultChannelFound = false;

			while ($row = $db->fetch_assoc($result)) {
				$forumName = $this->trimChannelName($row['forum_name']);
				
				$this->_allChannels[$forumName] = $row['id'];

				if(!$defaultChannelFound && $row['id'] == $this->getConfig('defaultChannelID')) {
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

	// Method to set the style cookie depending on the PunBB user style:
	function setStyle() {
		global $pun_user;
		
		if(isset($_COOKIE[$this->getConfig('sessionName').'_style']) && in_array($_COOKIE[$this->getConfig('sessionName').'_style'], $this->getConfig('styleAvailable')))
			return;
		
		$styleName = $pun_user['style'];
		
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