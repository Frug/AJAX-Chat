<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

//paths
$pathToRoot = __DIR__;
$configuration = require_once $pathToRoot . DIRECTORY_SEPARATOR . 'configuration.php';

//constants
define('AJAX_CHAT_PATH', $configuration['path_to_public'] . DIRECTORY_SEPARATOR);

//load files
require_once $configuration['path_to_source_class_loader'];
require_once $configuration['path_to_public_classes'];

if (is_file($configuration['path_to_public_configuration'])) {
    require_once $configuration['path_to_public_configuration'];

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
