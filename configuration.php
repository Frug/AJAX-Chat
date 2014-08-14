<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

//@todo refactor
/*
what about
return array(
    'backup' => array(
        'path' => '',
        'file' => array(
            'channels' => ''
        )
    )
)
*/
return array(
    'path_to_backup' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup',
    'path_to_backup_channels' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'channels.php',
    'path_to_backup_configuration' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'config.php',
    'path_to_backup_users' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'users.php',
    'path_to_backup_version' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'version.php',
    'path_to_example' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'example',
    'path_to_example_channels' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'channels.php',
    'path_to_example_configuration' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'channels.php',
    'path_to_example_users' => __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'config.php.example',
    'path_to_example_version' => __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'version.php',
    'path_to_public' => __DIR__ . DIRECTORY_SEPARATOR . 'chat',
    'path_to_public_channels' => __DIR__ .  DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'channels.php',
    'path_to_public_classes' => __DIR__ .  DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'classes.php',
    'path_to_public_configuration' => __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'config.php',
    'path_to_public_users' => __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'users.php',
    'path_to_public_version' => __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'version.php',
    'path_to_source_class_loader' => __DIR__ . DIRECTORY_SEPARATOR . 'source' . DIRECTORY_SEPARATOR . 'classLoader.php'
);