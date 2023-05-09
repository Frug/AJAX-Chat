<?php
namespace AjaxChat;

class Loader {
    /**
     * Loads the right integration version of CustomAJAXChat based on config.
     */
    public static function NewFromConfig(string $configPath) {
        $config = self::readConfigFile($configPath);

        if (!array_key_exists('integration', $config)) {
            return new \AjaxChat\Integrations\Standalone\CustomAJAXChat($config);
        }

        switch (strtolower($config['integration'])) {
        case 'phpbb3':
            return new \AjaxChat\Integrations\PhpBB3\CustomAJAXChat($config);
        case 'standalone':
        default:
            return new \AjaxChat\Integrations\Standalone\CustomAJAXChat($config);
        }

    }

    /**
     * Note that loading the standard config file has the side effect of setting several global variables.
     */
    public static function readConfigFile(string $configPath) {
		$config = [];
		if (!include_once($configPath)) {
			echo('<strong>Error:</strong> Could not find configuration file at "'.$configPath.'". Check to make sure the file exists.');
			die();
		}
        return $config;
    }
}
