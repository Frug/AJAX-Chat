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
    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $pathToBackupDirectory = $configuration['backup']['path'];
    $pathToDataDirectory = $configuration['public']['data']['path'];
    $pathToLibDirectory = $configuration['public']['lib']['path'];

    $filesystem = new Filesystem();

    if (!$filesystem->isDirectory($pathToBackupDirectory)) {
        $filesystem->createDirectory($pathToBackupDirectory);
    }

    $identifierToPaths = array(
        'channels' => array(
            'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['channels'],
            'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['channels']
        ),
        'configuration'  => array(
            'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['configuration'],
            'public' => $pathToLibDirectory . DIRECTORY_SEPARATOR . $configuration['public']['lib']['file']['configuration']
        ),
        'users' => array(
            'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['users'],
            'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['users']
        ),
        'version' => array(
            'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $configuration['backup']['file']['version'],
            'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['version']
        ),
    );

    foreach ($identifierToPaths as $identifier => $paths) {
        if (is_file($paths['backup'])) {
            echo $identifier . ' backup file available, will delete it ...' . PHP_EOL;
            $filesystem->deleteFile($paths['backup']);
        }
    }

    foreach ($identifierToPaths as $identifier => $paths) {
        echo 'creating backup of ' . $identifier . ' ...' . PHP_EOL;
        $filesystem->copy(
            $paths['public'],
            $paths['backup']
        );
    }

    echo PHP_EOL;
    echo 'done' . PHP_EOL;
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo $usage . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}
