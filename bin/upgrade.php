<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

try {
    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoLoader.php';

    $application = new Application_Cli();

    $verifyInstallation = $application->getVerifyInstallationLocalFilesCommand();
    $verifyInstallation->verify();
    $verifyInstallation->execute();

    $command = $application->getUpgradeCommand();
    try {
        $command->verify();
    } catch (Exception $exception) {
        throw new Exception(implode("\n", $command->getUsage()));
    }
    $command->execute();

    $command->getOutput()->addLine('done');

    foreach ($command->getOutput()->toArray() as $line) {
        echo $line . PHP_EOL;
    }
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo 'Usage:' . PHP_EOL . basename(__FILE__) . ' ' . $exception->getMessage() . PHP_EOL;
    exit(1);
}
