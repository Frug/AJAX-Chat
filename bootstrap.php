<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

//autoloader
require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoLoader.php';

//@todo move define of constants to application and implement "getRoles()" method

//application
///*
$configuration = require_once __DIR__ . DIRECTORY_SEPARATOR . 'configuration.php';
//*/

//constants
define('AJAX_CHAT_PATH', $configuration['public']['path'] . DIRECTORY_SEPARATOR);

//chat files
///*
$pathToPublicClasses = $configuration['public']['lib']['path'] . DIRECTORY_SEPARATOR . $configuration['public']['lib']['file']['classes'];
$pathToPublicConfiguration = $configuration['public']['lib']['path'] . DIRECTORY_SEPARATOR . $configuration['public']['lib']['file']['application'];

require_once $pathToPublicClasses;

if (is_file($pathToPublicConfiguration)) {
    require_once $pathToPublicConfiguration;

    //create properties
    $roles = array(
        AJAX_CHAT_GUEST     => 'AJAX_CHAT_GUEST',
        AJAX_CHAT_USER      => 'AJAX_CHAT_USER',
        AJAX_CHAT_MODERATOR => 'AJAX_CHAT_MODERATOR',
        AJAX_CHAT_ADMIN     => 'AJAX_CHAT_ADMIN',
        AJAX_CHAT_CHATBOT   => 'AJAX_CHAT_CHATBOT',
        AJAX_CHAT_CUSTOM    => 'AJAX_CHAT_CUSTOM',
        AJAX_CHAT_BANNED    => 'AJAX_CHAT_BANNED'
    );
}
//*/
