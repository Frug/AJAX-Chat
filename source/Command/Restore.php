<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_Restore
 */
class Command_Restore extends Command_AbstractCommand
{
    /**
     * @var array
     */
    private $availableCommands = array(
        'all' => true,
        'channels' => true,
        'configuration' => true,
        'users' => true,
        'version' => true
    );

    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $identifiers = array();

        $pathToBackupDirectory = $this->configuration['backup']['path'];
        $pathToDataDirectory = $this->configuration['public']['data']['path'];
        $pathToLibDirectory = $this->configuration['public']['lib']['path'];

        switch ($this->command) {
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
            if ($this->filesystem->isFile($identifierToPaths[$identifier]['backup'])) {
                echo $identifier . ' backup file available, will restore it ...' . PHP_EOL;
                $this->filesystem->copy(
                    $identifierToPaths[$identifier]['backup'],
                    $identifierToPaths[$identifier]['public']
                );
            } else {
                echo 'no ' . $identifier .' backup file available ...' . PHP_EOL;
            }
        }
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            '[' . implode('|', array_keys($this->availableCommands)) . ']'
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) != 2) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $command = trim($this->arguments[1]);

        if (!(isset($this->availableCommands[$command]))) {
            throw new Exception(
                'invalid command provided'
            );
        }

        $this->command = $command;
    }
}