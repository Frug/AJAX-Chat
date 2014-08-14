<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

//@todo validate return statement of mkdir|unlink|copy and throw exceptions when needed
if (!is_dir($configuration['path_to_backup'])) {
    mkdir($configuration['path_to_backup']);
}

if (is_file($configuration['path_to_backup_channels'])) {
    echo 'channels backup file available, will delete it ...' . PHP_EOL;
    unlink($configuration['path_to_backup_channels']);
}

if (is_file($configuration['path_to_backup_configuration'])) {
    echo 'configuration backup file available, will delete it ...' . PHP_EOL;
    unlink($configuration['path_to_backup_configuration']);
}

if (is_file($configuration['path_to_backup_users'])) {
    echo 'users backup file available, will delete it ...' . PHP_EOL;
    unlink($configuration['path_to_backup_users']);
}

echo 'creating backup of channels ...' . PHP_EOL;
copy($configuration['path_to_public_channels'], $configuration['path_to_backup_channels']);

echo 'creating backup of configuration ...' . PHP_EOL;
copy($configuration['path_to_public_configuration'], $configuration['path_to_backup_configuration']);

echo 'creating backup of users ...' . PHP_EOL;
copy($configuration['path_to_public_users'], $configuration['path_to_backup_users']);

echo PHP_EOL;
echo 'done' . PHP_EOL;
