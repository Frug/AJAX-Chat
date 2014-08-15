<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$pathToExampleDirectory = $configuration['public']['path'];
$pathToPublicDataDirectory = $configuration['public']['data']['path'];

//@todo see backup.php
//->chat/lib/data/
if (!is_dir($pathToPublicDataDirectory)) {
    mkdir($pathToPublicDataDirectory);
}

//@todo code duplication sucks
if (!is_file($pathToPublicDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['channels'])) {
    echo 'no channels file available, will create one ...' . PHP_EOL;
    copy(
        $pathToExampleDirectory . DIRECTORY_SEPARATOR . $configuration['example']['file']['channels'],
        $pathToPublicDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['channels']
    );
}

if (!is_file($configuration['path_to_public_configuration'])) {
    echo 'no configuration file available, will create one ...' . PHP_EOL;
    copy($configuration['path_to_example_configuration'], $configuration['path_to_public_configuration']);
}

if (!is_file($configuration['path_to_public_users'])) {
    echo 'no users file available, will create one ...' . PHP_EOL;
    copy($configuration['path_to_example_users'], $configuration['path_to_public_users']);
}

if (!is_file($configuration['path_to_public_version'])) {
    echo 'no version file available, will create one ...' . PHP_EOL;
    copy($configuration['path_to_example_version'], $configuration['path_to_public_version']);
}

echo PHP_EOL;
echo 'done' . PHP_EOL;
