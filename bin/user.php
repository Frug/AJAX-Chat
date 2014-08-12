<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

define('AJAX_CHAT_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'chat') . DIRECTORY_SEPARATOR);
require_once AJAX_CHAT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'classes.php';

$isCalledFromCommandLineInterface = (PHP_SAPI === 'cli');

try {
    //@todo implement edit <user_id>
    $validCommands = array('add', 'delete', 'list');
    $usage = 'Usage: ' . PHP_EOL .
        basename(__FILE__) . ' [' . implode('|', $validCommands) . ']' . PHP_EOL;

    if ($argc < 2) {
        echo $usage;
        exit(1);
    }

    $command = trim($argv[1]);

    if (!in_array($command, $validCommands)) {
        echo $usage;
        exit(1);
    }

    $pathToChannelsPhp = realpath(__DIR__) . DIRECTORY_SEPARATOR .
        '..' . DIRECTORY_SEPARATOR .
        'chat' . DIRECTORY_SEPARATOR .
        'lib' . DIRECTORY_SEPARATOR .
        'data' . DIRECTORY_SEPARATOR .
        'channels.php';

    $pathToConfigPhp = realpath(__DIR__) . DIRECTORY_SEPARATOR .
        '..' . DIRECTORY_SEPARATOR .
        'chat' . DIRECTORY_SEPARATOR .
        'lib' . DIRECTORY_SEPARATOR .
        'config.php';

    $pathToUsersPhp = realpath(__DIR__) . DIRECTORY_SEPARATOR .
        '..' . DIRECTORY_SEPARATOR .
        'chat' . DIRECTORY_SEPARATOR .
        'lib' . DIRECTORY_SEPARATOR .
        'data' . DIRECTORY_SEPARATOR .
        'users.php';

    //copy file if not available
    if (!is_file($pathToChannelsPhp)) {
        echo 'no channels file available, will create one ...' . PHP_EOL;
        copy($pathToChannelsPhp . '.example', $pathToChannelsPhp);
    }

    if (!is_file($pathToConfigPhp)) {
        echo 'no configuration file available, will create one ...' . PHP_EOL;
        copy($pathToConfigPhp . '.example', $pathToConfigPhp);
    }

    if (!is_file($pathToUsersPhp)) {
        echo 'no users file available, will create one ...' . PHP_EOL;
        copy($pathToUsersPhp . '.example', $pathToUsersPhp);
    }

    require_once $pathToChannelsPhp;
    require_once $pathToConfigPhp;
    require_once $pathToUsersPhp;

    $roles = array(
        AJAX_CHAT_GUEST     => 'AJAX_CHAT_GUEST',
        AJAX_CHAT_USER      => 'AJAX_CHAT_USER',
        AJAX_CHAT_MODERATOR => 'AJAX_CHAT_MODERATOR',
        AJAX_CHAT_ADMIN     => 'AJAX_CHAT_ADMIN',
        AJAX_CHAT_CHATBOT   => 'AJAX_CHAT_CHATBOT',
        AJAX_CHAT_CUSTOM    => 'AJAX_CHAT_CUSTOM',
        AJAX_CHAT_BANNED    => 'AJAX_CHAT_BANNED'
    );

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . $command . '.php';
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}