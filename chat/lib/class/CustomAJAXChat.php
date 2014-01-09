<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 * 
 * PunBB integration:
 * http://punbb.org/
 */

class CustomAJAXChat extends AJAXChat {

	// Initialize custom configuration settings
	function initCustomConfig() {
		global $forum_db;
		
		
		// Use the existing PunBB database connection:
		$this->setConfig('dbConnection', 'link', $forum_db->link_id);
	}

	// Initialize custom request variables:
	function initCustomRequestVars() {
		global $forum_user;

		// Auto-login phpBB users:
		if(!$this->getRequestVar('logout') && !$forum_user['is_guest']) {
			$this->setRequestVar('login', true);
		}
	}

	// Replace custom template tags:
	function replaceCustomTemplateTags($tag, $tagContent) {
		global $forum_user,$forum_config;

		switch($tag) {

			case 'FORUM_LOGIN_URL':
				if(!$forum_user['is_guest']) {
					return ($this->getRequestVar('view') == 'logs') ? './?view=logs' : './';
				} else {
					return $this->htmlEncode($forum_config['o_base_url'].'/login.php?action=in');
				}
				
			case 'REDIRECT_URL':
				if(!$forum_user['is_guest']) {
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
		global $forum_user;
		
		if($this->getUserRole() === AJAX_CHAT_GUEST && $forum_user['is_guest'] || ($this->getUserID() === $forum_user['id'])) {
			return true;
		}
		return false;
	}

	// Store the channels the current user has access to
	// Make sure channel names don't contain any whitespace
	function &getChannels() {
		if($this->_channels === null) {
			global $forum_db,$forum_user;
			
			$this->_channels = array();
			
			// Get valid PunBB forums:
			$sql = 'SELECT
						id,
						forum_name
					FROM
						'.$forum_db->prefix.'forums AS f
					LEFT JOIN
						'.$forum_db->prefix.'forum_perms AS fp
					ON
						(fp.forum_id=f.id AND fp.group_id=\''.$forum_db->escape($forum_user['g_id']).'\')
					WHERE
						(fp.read_forum IS NULL OR fp.read_forum=1);';
			$result = $forum_db->query($sql);

			$defaultChannelFound = false;

			while ($row = $forum_db->fetch_assoc($result)) {
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
			$forum_db->free_result($result);

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

	// Method to set the style cookie depending on the PunBB user style:
	function setStyle() {
		global $forum_user;
		
		if(isset($_COOKIE[$this->getConfig('sessionName').'_style']) && in_array($_COOKIE[$this->getConfig('sessionName').'_style'], $this->getConfig('styleAvailable')))
			return;
		
		$styleName = $forum_user['style'];
		
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