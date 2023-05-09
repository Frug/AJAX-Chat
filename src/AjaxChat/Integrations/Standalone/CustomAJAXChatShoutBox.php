<?php
namespace AjaxChat\Integrations\Standalone;

use \AjaxChat\Template;

class CustomAJAXChatShoutBox extends CustomAJAXChat
{

	function initialize()
	{
		// Initialize configuration settings:
		$this->initConfig();
	}

	function getShoutBoxContent()
	{
		$template = new Template($this, AJAX_CHAT_PATH . 'src/template/shoutbox.html');

		// Return parsed template content:
		return $template->getParsedContent();
	}

}