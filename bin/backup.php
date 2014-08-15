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

    //@todo move into factory
    $command = new Command_Backup();
    $command->setConfiguration($configuration);
    $command->setFilesystem(new Filesystem());
    $command->verify();
    $command->execute();
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo $usage . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}