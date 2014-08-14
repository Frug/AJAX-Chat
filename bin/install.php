<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

if (!is_dir($configuration['path_to_examples'])) {
    mkdir($configuration['path_to_examples']);
}

if (!is_file($configuration['path_to_public_channels'])) {
    echo 'no channels file available, will create one ...' . PHP_EOL;
    copy($configuration['path_to_example_channels'], $configuration['path_to_public_channels']);
}

if (!is_file($configuration['path_to_public_configuration'])) {
    echo 'no configuration file available, will create one ...' . PHP_EOL;
    copy($configuration['path_to_example_configuration'], $configuration['path_to_public_configuration']);
}

if (!is_file($configuration['path_to_public_users'])) {
    echo 'no users file available, will create one ...' . PHP_EOL;
    copy($configuration['path_to_example_users'], $configuration['path_to_public_users']);
}
