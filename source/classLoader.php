<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

$pathToScript = __DIR__ . DIRECTORY_SEPARATOR;
$pathToCommand = $pathToScript . 'Command' . DIRECTORY_SEPARATOR;

require_once $pathToScript . 'File.php';
require_once $pathToScript . 'String.php';
require_once $pathToCommand . 'CommandInterface.php';
require_once $pathToCommand . 'AbstractCommand.php';
require_once $pathToCommand . 'UserAddCommand.php';
require_once $pathToCommand . 'UserDeleteCommand.php';
require_once $pathToCommand . 'UserEditCommand.php';
require_once $pathToCommand . 'UserListCommand.php';
