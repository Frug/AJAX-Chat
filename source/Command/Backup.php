<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-16 
 */

/**
 * Class Command_Backup
 */
class Command_Backup extends Command_AbstractCommand
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
     */
    public function execute()
    {
        if (!$this->filesystem->isDirectory($this->pathConfiguration->getBackupPath())) {
            $this->filesystem->createDirectory($this->pathConfiguration->getBackupPath());
        }

        $identifierToPaths = array(
            'channels' => array(
                'backup' => $this->pathConfiguration->getBackupChannelsFilePath(),
                'chat' => $this->pathConfiguration->getChatChannelsFilePath()
            ),
            'configuration'  => array(
                'backup' => $this->pathConfiguration->getBackupConfigFilePath(),
                'chat' => $this->pathConfiguration->getChatConfigFilePath()
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

        foreach ($identifierToPaths as $identifier => $paths) {
            if ($this->filesystem->isFile($paths['backup'])) {
                echo $identifier . ' backup file available, will delete it ...' . PHP_EOL;
                $this->filesystem->deleteFile($paths['backup']);
            }
        }

        foreach ($identifierToPaths as $identifier => $paths) {
            echo 'creating backup of ' . $identifier . ' ...' . PHP_EOL;
            $this->filesystem->copy(
                $paths['chat'],
                $paths['backup']
            );
        }

        echo PHP_EOL;
        echo 'done' . PHP_EOL;
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
        if (is_null($this->pathConfiguration)) {
            throw new Exception(
                'configuration is mandatory'
            );
        }

        if (is_null($this->filesystem)) {
            throw new Exception(
                'filesystem is mandatory'
            );
        }
    }
}