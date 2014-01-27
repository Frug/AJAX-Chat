<?php
/*
 * @package AJAX_Chat
* @author Sebastian Tschan
* @copyright (c) Sebastian Tschan
* @license Modified MIT License
* @link https://blueimp.net/ajax/
*/

class CustomAJAXChat extends AJAXChat {

	// Override to initialize custom configuration settings:
	function initCustomConfig() {
			
	}

	// Returns an associative array containing userName, userID and userRole
	// Returns null if login is invalid
	function getValidLoginUserData() {
		// Fetch current visitor info
		$visitor = XenForo_Visitor::getInstance();

		if ($visitor['user_id']) {
			//Registered user
			return $this->_setupUserData($visitor);
		} else {

			if ($login = $this->getRequestVar('userName'))
			{
				//Authenticate
				$password =  $this->getRequestVar('password');

				$loginModel = $this->_getLoginModel();

				$needCaptcha = $loginModel->requireLoginCaptcha($login);
				if ($needCaptcha)
				{
					switch (XenForo_Application::getOptions()->loginLimit)
					{
						case 'captcha':
						case 'block':
							return null;
					}
				}

				$userModel = $this->_getUserModel();

				$userId = $userModel->validateAuthentication($login, $password);
				if (!$userId)
				{
					$loginModel->logLoginAttempt($login);

					return null;
				}

				$loginModel->clearLoginAttempts($login);

				$userModel->setUserRememberCookie($userId);
				
				XenForo_Model_Ip::log($userId, 'user', $userId, 'login');

				$userModel->deleteSessionActivity(0, $this->getSessionIP());

				$session = XenForo_Application::get('session');
				$session->changeUserId($userId);
				XenForo_Visitor::setup($userId);
				
				return $this->_setupUserData(XenForo_Visitor::getInstance());
			}
			else
			{
				return $this->getGuestUser();
			}
		}
	}

	// Returns true if the userID of the logged in user is identical to the userID of the authentication system
	// or the user is authenticated as guest in the chat and the authentication system
	function revalidateUserID() {
		// Gets current visitor info
		$visitor = XenForo_Visitor::getInstance();

		return ($this->getUserRole() === AJAX_CHAT_GUEST && !$visitor['user_id'] || ($this->getUserID() === $visitor['user_id']));
	}

	// Add values to the request variables array: $this->_requestVars['customVariable'] = null;
	function initCustomRequestVars() {
		if($this->getRequestVar('logout') != true) {
			// Fetch current visitor info
			$visitor = XenForo_Visitor::getInstance();

			// Auto login if user is authenticated in XenForo
			if ($visitor['user_id'] != 0) {
				$this->setRequestVar('login', true);
			}
		}
	}



	// Store the channels the current user has access to
	// Make sure channel names don't contain any whitespace
	function &getChannels() {
		if($this->_channels === null) {
			$this->_channels = array();
			
			// Fetch visitor & visitor permissions combo
			$visitor = XenForo_Visitor::getInstance();
			$permissionCombinationId = $visitor['permission_combination_id'];

			/* @var $nodeModel XenForo_Model_Node */
			$nodeModel = XenForo_Model::create("XenForo_Model_Node");

			$categoryModel = $this->_getCategoryModel();
			
			// Add the valid channels to the channel list (the defaultChannelID is always valid):
			foreach ($this->getAllChannels() AS $key => $nodeId) {
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($nodeId, $this->getConfig('limitChannelList'))) {
					continue;
				}

				// Checks user permissions, using canViewCategory, whether this actually is a category or not (same behavior).
				if(in_array($nodeId, $this->_channels) || $categoryModel->canViewCategory(array('node_id' => $nodeId))) {
					$this->_channels[$key] = $nodeId;
				}
			}
			
// 			// Setting default channel info from config. NB: $key=>$value is $nam=>$id...
 			$this->_channels[$this->getConfig('defaultChannelName')] = $this->getConfig('defaultChannelID');
				
		}
		return $this->_channels;
	}

	// Store all existing channels
	// Make sure channel names don't contain any whitespace
	function &getAllChannels() {
		if($this->_allChannels === null) {
			$this->_allChannels = array();

			/* @var $nodeModel XenForo_Model_Node */
			$nodeModel = XenForo_Model::create("XenForo_Model_Node");
			// Get all forums and/or categories (depending on config)
			$allChannels = $nodeModel->getAllNodes();

			foreach($allChannels as $nodeId=>$node) {
				$nodeTitle = $this->trimChannelName($node['title']);
				$this->_allChannels[$nodeTitle] = $nodeId;
			}
			
			// Default channel, public to everyone:
			$this->_allChannels[$this->trimChannelName($this->getConfig('defaultChannelName'))] = $this->getConfig('defaultChannelID');
		}
		return $this->_allChannels;
	}

	/**
	 * 
	 * @param XenForo_Visitor $visitor
	 * @return multitype:string unknown Ambigous <string, mixed>
	 */
	protected function _setupUserData($visitor)
	{
		$userData = array();
		$userData['userID'] = $visitor['user_id'];
		$userData['userName'] = $this->trimUserName($visitor['username']);

		// TODO : Use permissions to set role.
		if ($visitor['is_admin'])
		{
			$userData['userRole'] = AJAX_CHAT_ADMIN;
		} else {
			if ($visitor['is_moderator']) {
				$userData['userRole'] = AJAX_CHAT_MODERATOR;
			} else {
				$userData['userRole'] = AJAX_CHAT_USER;
			}
		}

		return $userData;
	}

	/**
	 * @return XenForo_Model_Login
	 *
	 */
	protected function _getLoginModel() {
		return XenForo_Model::create("XenForo_Model_Login");
	}
	
	/**
	 * @return XenForo_Model_User
	 *
	 */
	protected function _getUserModel() {
		return XenForo_Model::create("XenForo_Model_User");
	}
	
	/**
	 * @return XenForo_Model_Category
	 *
	 */
	protected function _getCategoryModel() {
		return XenForo_Model::create("XenForo_Model_Category");
	}
}
?>