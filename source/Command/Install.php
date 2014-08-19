<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_Install
 */
class Command_Install extends Command_AbstractCommand
{
    /**
     * @var Configuration_Path
     */
    private $pathConfiguration;

    /**
     * @var Filesystem
     */
    private $filesystem;

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
     * @todo implement setup of database (check if connection is available, split it up in multiple steps (Command_Install_Files, Command_Install_Database)
     */
    public function execute()
    {
        $identifierToPaths = array(
            'channels' => array(
                'example' => $this->pathConfiguration->getExampleChannelsFilePath(),
                'chat' => $this->pathConfiguration->getChatChannelsFilePath()
            ),
            'configuration'  => array(
                'example' => $this->pathConfiguration->getExampleConfigurationFilePath(),
                'chat' => $this->pathConfiguration->getChatConfigurationFilePath()
            ),
            'users' => array(
                'example' => $this->pathConfiguration->getExampleUsersFilePath(),
                'chat' => $this->pathConfiguration->getChatUsersFilePath()
            ),
            'version' => array(
                'example' => $this->pathConfiguration->getExampleVersionFilePath(),
                'chat' => $this->pathConfiguration->getChatVersionFilePath()
            ),
        );

        foreach ($identifierToPaths as $identifier => $paths) {
            if (!$this->filesystem->isFile($paths['chat'])) {
                $directoryPath = dirname($paths['chat']);

                if (!$this->filesystem->isDirectory($directoryPath)) {
                    $this->output->addLine('no directory available at "' . $directoryPath . '", will create one ...');
                    $this->filesystem->createDirectory($directoryPath);
                }
                $this->output->addLine('no ' . $identifier . ' file available, will create one ...');
                $this->filesystem->copy(
                    $paths['example'],
                    $paths['chat']
                );
            } else {
                $this->output->addLine($identifier . ' file available, nothing to do ...');
            }
        }

        $this->output->addLine();
        $this->output->addLine('done');
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array();
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
    }
}