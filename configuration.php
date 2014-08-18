<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

return array(
    'backup' => array(
        'path' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup',
        'file' => array(
            'channels'      => 'channels.php',
            'application' => 'config.php',
            'users'         => 'users.php',
            'version'       => 'version.php'
        )
    ),
    'example' => array(
        'path' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'example',
        'file' => array(
            'channels'      => 'channels.php',
            'application' => 'application.php',
            'users'         => 'users.php',
            'version'       => 'version.php'
        )
    ),
    'public' => array(
        'path' => __DIR__ . DIRECTORY_SEPARATOR . 'chat',
        'data' => array(
            'path' => __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data',
            'file' => array(
                'channels'      => 'channels.php',
                'users'         => 'users.php',
                'version'       => 'version.php',
            )
        ),
        'lib' => array(
            'path' => __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib',
            'file' => array(
                'classes'       => 'classes.php',
                'application' => 'config.php'
            )
        )
    ),
    'root' => array(
        'path' => __DIR__
    )
);