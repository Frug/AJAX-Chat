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

    //@todo verify if is installed and up to date
    $filesystem = new Filesystem();
    $identifiers = array();

    $pathToBackupDirectory = $configuration['backup']['path'];
    $pathToDataDirectory = $configuration['public']['data']['path'];
    $pathToLibDirectory = $configuration['public']['lib']['path'];

    switch ($currentCommand) {
        case 'all':
            $identifiers = array(
                'channels',
                'configuration',
                'users',
                'version'
            );
            break;
        case 'channels':
            $identifiers[] = 'channels';
            break;
        case 'configuration':
            $identifiers[] = 'configuration';
            break;
        case 'users':
            $identifiers[] = 'users';
            break;
        case 'version':
            $identifiers[] = 'version';
            break;
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

    foreach ($identifiers as $identifier) {
        if ($filesystem->isFile($identifierToPaths[$identifier]['backup'])) {
            echo $identifier . ' backup file available, will restore it ...' . PHP_EOL;
            $filesystem->copy(
                $identifierToPaths[$identifier]['backup'],
                $identifierToPaths[$identifier]['public']
            );
        } else {
            echo 'no ' . $identifier .' backup file available ...' . PHP_EOL;
        }
    }
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo $usage . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}