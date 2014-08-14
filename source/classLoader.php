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
require_once $pathToCommand . 'ChannelCommandInterface.php';
require_once $pathToCommand . 'UserCommandInterface.php';
require_once $pathToCommand . 'AbstractCommand.php';
require_once $pathToCommand . 'AbstractChannelCommand.php';
require_once $pathToCommand . 'AbstractUserCommand.php';
require_once $pathToCommand . 'UserUserAddCommand.php';
require_once $pathToCommand . 'UserUserDeleteCommand.php';
require_once $pathToCommand . 'UserUserEditCommand.php';
require_once $pathToCommand . 'UserUserListCommand.php';
