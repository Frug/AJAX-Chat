<?php
namespace AjaxChat;
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Class to provide methods for file system access:
class FileSystem {

	public static function getFileContents($file) {
		if(function_exists('file_get_contents')) {
			return file_get_contents($file);
		}
		return(implode('', file($file)));
	}

}
