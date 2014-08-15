<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

//@todo validate return statement of mkdir|unlink|copy and throw exceptions when needed
$pathToBackupDirectory = $configuration['backup']['path'];
$pathToPublicDirectory = $configuration['public']['path'];

if (!is_dir($pathToBackupDirectory)) {
    mkdir($pathToBackupDirectory);
}

//@todo code duplication sucks
if (is_file($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['channels'])) {
    echo 'channels backup file available, will delete it ...' . PHP_EOL;
    unlink($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['channels']);
}

if (is_file($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['configuration'])) {
    echo 'configuration backup file available, will delete it ...' . PHP_EOL;
    unlink($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['configuration']);
}

if (is_file($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['users'])) {
    echo 'users backup file available, will delete it ...' . PHP_EOL;
    unlink($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['users']);
}

if (is_file($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['version'])) {
    echo 'version backup file available, will delete it ...' . PHP_EOL;
    unlink($pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['version']);
}

echo 'creating backup of channels ...' . PHP_EOL;
copy(
    $pathToPublicDirectory . DIRECTORY_SEPARATOR . $configuration['public']['file']['channels'],
    $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['channels']
);

echo 'creating backup of configuration ...' . PHP_EOL;
copy(
    $pathToPublicDirectory . DIRECTORY_SEPARATOR . $configuration['public']['file']['configuration'],
    $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['configuration']
);

echo 'creating backup of users ...' . PHP_EOL;
copy(
    $pathToPublicDirectory . DIRECTORY_SEPARATOR . $configuration['public']['file']['users'],
    $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['users']
);

echo 'creating backup of version ...' . PHP_EOL;
copy(
    $pathToPublicDirectory . DIRECTORY_SEPARATOR . $configuration['public']['file']['version'],
    $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['version']
);

echo PHP_EOL;
echo 'done' . PHP_EOL;
