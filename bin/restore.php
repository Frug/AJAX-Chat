<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

$isNotCalledFromCommandLineInterface = (PHP_SAPI !== 'cli');

try {
    if ($isNotCalledFromCommandLineInterface) {
        throw new Exception(
            'command line script only '
        );
    }

    $validCommands = array(
        'all' => true,
        'channels' => true,
        'configuration' => true,
        'users' => true,
        'version' => true
    );
    $usage = 'Usage: ' . PHP_EOL .
        basename(__FILE__) . ' [' . implode('|', array_keys($validCommands)) . ']' . PHP_EOL;

    if ($argc < 2) {
        echo $usage;
        exit(1);
    }

    $currentCommand = trim($argv[1]);

    if (!(isset($validCommands[$currentCommand]))) {
        echo $usage;
        exit(1);
    }

    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $restoreChannels = false;
    $restoreConfiguration = false;
    $restoreUsers = false;
    $restoreVersion = false;

    switch ($currentCommand) {
        case 'all':
            $restoreChannels = true;
            $restoreConfiguration = true;
            $restoreUsers = true;
            $restoreVersion = true;
            break;
        case 'channels':
            $restoreChannels = true;
            break;
        case 'configuration':
            $restoreConfiguration = true;
            break;
        case 'users':
            $restoreUsers = true;
            break;
        case 'version':
            $restoreVersion = true;
            break;
    }

    //@todo validate return statement of copy and throw exceptions when needed
    //@todo code duplication sucks
    if ($restoreChannels) {
        if (is_file($configuration['path_to_backup_channels'])) {
            echo 'channels backup file available, will restore it ...' . PHP_EOL;
            copy($configuration['path_to_backup_channels'], $configuration['path_to_public_channels']);
        } else {
            echo 'no channels backup file available ...' . PHP_EOL;
        }
    }

    if ($restoreConfiguration) {
        if (is_file($configuration['path_to_backup_configuration'])) {
            echo 'configuration backup file available, will restore it ...' . PHP_EOL;
            copy($configuration['path_to_backup_configuration'], $configuration['path_to_public_configuration']);
        } else {
            echo 'no configuration backup file available ...' . PHP_EOL;
        }
    }

    if ($restoreUsers) {
        if (is_file($configuration['path_to_backup_users'])) {
            echo 'users backup file available, will restore it ...' . PHP_EOL;
            copy($configuration['path_to_backup_users'], $configuration['path_to_public_users']);
        } else {
            echo 'no users backup file available ...' . PHP_EOL;
        }
    }

    if ($restoreVersion) {
        if (is_file($configuration['path_to_backup_version'])) {
            echo 'version backup file available, will restore it ...' . PHP_EOL;
            copy($configuration['path_to_backup_version'], $configuration['path_to_public_version']);
        } else {
            echo 'no version backup file available ...' . PHP_EOL;
        }
    }

} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo $usage . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}