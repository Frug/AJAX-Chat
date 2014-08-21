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
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $commands = array(
        'all',
        'channels',
        'application',
        'users',
        'version'
    );

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Configuration_Path
     */
    private $pathConfiguration;

    /**
     * @param Configuration_Path $configuration
     */
    public function setPathConfiguration(Configuration_Path $configuration)
    {
        $this->pathConfiguration = $configuration;
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

        switch ($this->command) {
            case 'all':
                $identifiers = array(
                    'channels',
                    'pathConfiguration',
                    'users',
                    'version'
                );
                break;
            case 'channels':
                $identifiers[] = 'channels';
                break;
            case 'pathConfiguration':
                $identifiers[] = 'pathConfiguration';
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
                'backup' => $this->pathConfiguration->getBackupChannelsFilePath(),
                'chat' => $this->pathConfiguration->getChatChannelsFilePath()
            ),
            'pathConfiguration'  => array(
                'backup' => $this->pathConfiguration->getBackupConfigurationFilePath(),
                'chat' => $this->pathConfiguration->getChatConfigurationFilePath()
            ),
            'users' => array(
                'backup' => $this->pathConfiguration->getBackupUsersFilePath(),
                'chat' => $this->pathConfiguration->getChatUsersFilePath()
            ),
            'version' => array(
                'backup' => $this->pathConfiguration->getBackupVersionFilePath(),
                'chat' => $this->pathConfiguration->getChatVersionFilePath()
            ),
        );

        foreach ($identifiers as $identifier) {
            if ($this->filesystem->isFile($identifierToPaths[$identifier]['backup'])) {
                $this->output->addLine($identifier . ' backup file available, will restore it ...');
                $this->filesystem->copy(
                    $identifierToPaths[$identifier]['backup'],
                    $identifierToPaths[$identifier]['chat']
                );
            } else {
                $this->output->addLine('no ' . $identifier .' backup file available ...');
            }
        }
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            '[--' . implode('|--', $this->commands) . ']'
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if ($this->input->getNumberOfArguments() !== 1) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        foreach ($this->commands as $command) {
            if ($this->input->hasLongOption($command)) {
                $this->command = $command;
                break;
            }
        }

        if (is_null($this->command)) {
            throw new Exception(
                'invalid command provided'
            );
        }

        $this->command = $command;
    }
}