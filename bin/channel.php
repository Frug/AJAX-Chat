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

    //@todo verify if is installed and up to date
    $command = new Command_Channel();
    $command->setArguments($argv);
    $command->setConfiguration($configuration);
    $command->setChannels($channels);
    $command->setFilesystem(new Filesystem());
    try {
        $command->verify();
    } catch (Exception $exception) {
        throw new Exception(implode("\n", $command->getUsage()));
    }
    $command->execute();
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo 'Usage:' . PHP_EOL . basename(__FILE__) . ' ' . $exception->getMessage() . PHP_EOL;
    exit(1);
}