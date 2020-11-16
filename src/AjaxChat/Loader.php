<?php
namespace AjaxChat;
/*
 * @package AJAX_Chat
 * @author Philip Nicolcev 
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

class Loader {
    public static function NewFromConfig(string $configPath) {
		$config = null;
		if (!include($configPath)) {
			echo('<strong>Error:</strong> Could not find configuration file at "'.$configPath.'". Check to make sure the file exists.');
			die();
		}

        if (!array_key_exists('integration', $config)) {
            return new \AjaxChat\Integrations\Standalone\CustomAJAXChat($config);
        }

        switch (strtolower($config['integration'])) {
        case 'phpbb3':
            return new \AjaxChat\Integrations\PhpBB3\CustomAJAXChat($config);
            break;
        case 'standalone':
        default:
            return new \AjaxChat\Integrations\Standalone\CustomAJAXChat($config);
        }

    }
}
