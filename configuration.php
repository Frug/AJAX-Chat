<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

return array(
    'backup' => array(
        'path' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup',
        'file' => array(
            'channels' => 'channels.php',
            'configuration' => 'config.php',
            'users' => 'users.php',
            'version' => 'version.php'
        )
    ),
    'example' => array(
        'path' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'example',
        'file' => array(
            'channels' => 'channels.php',
            'configuration' => 'configuration.php',
            'users' => 'users.php',
            'version' => 'version.php'
        )
    ),
    'public' => array(
        'path' => __DIR__ . DIRECTORY_SEPARATOR . 'chat',
        'file' => array(
            'channels' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'channels.php',
            'classes' =>  DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'classes.php',
            'configuration' =>  DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'config.php',
            'users' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'users.php',
            'version' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'version.php',
        )
    )
);