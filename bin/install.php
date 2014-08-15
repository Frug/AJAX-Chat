<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$isNotCalledFromCommandLineInterface = (PHP_SAPI !== 'cli');

try {
    if ($isNotCalledFromCommandLineInterface) {
        throw new Exception(
            'command line script only '
        );
    }
    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $pathToExampleDirectory = $configuration['example']['path'];
    $pathToDataDirectory = $configuration['public']['data']['path'];
    $pathToLibDirectory = $configuration['public']['lib']['path'];

    $filesystem = new Filesystem();

    $identifierToPaths = array(
        'channels' => array(
            'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $configuration['example']['file']['channels'],
            'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['channels']
        ),
        'configuration'  => array(
            'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $configuration['example']['file']['configuration'],
            'public' => $pathToLibDirectory . DIRECTORY_SEPARATOR . $configuration['public']['lib']['file']['configuration']
        ),
        'users' => array(
            'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $configuration['example']['file']['users'],
            'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['users']
        ),
        'version' => array(
            'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $configuration['example']['file']['version'],
            'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['version']
        ),
    );

    foreach ($identifierToPaths as $identifier => $paths) {
        if (!$filesystem->isFile($paths['public'])) {
            echo 'no ' . $identifier . ' file available, will create one ...' . PHP_EOL;
            $filesystem->copy(
                $paths['example'],
                $paths['public']
            );
        }
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