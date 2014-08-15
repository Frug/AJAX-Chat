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
        'add'       => 'Command_Channel_Add',
        'edit'      => 'Command_Channel_Edit',
        'delete'    => 'Command_Channel_Delete',
        'list'      => 'Command_Channel_List'
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
    require_once 'install.php';

    $pathToChannelsPhp = $configuration['public']['data']['path'] . DIRECTORY_SEPARATOR . $configuration['public']['data']['file']['channels'];

    require_once $pathToChannelsPhp;

    $commandClass = $validCommands[$currentCommand];
    $fileToChannels = new File($pathToChannelsPhp);

    /** @var Command_Channel_CommandInterface $command */
    $command = new $commandClass();

    $command->setArguments($argv);
    $command->setChannels($channels);
    $command->setChannelFile($fileToChannels);

    try {
        $command->verify();
    } catch (Exception $exception) {
        throw new Exception($currentCommand . ' ' . implode("\n", $command->getUsage()));
    }
    $command->execute();
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo $usage . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}