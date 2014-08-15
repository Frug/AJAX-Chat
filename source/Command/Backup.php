<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-16 
 */

/**
 * Class Command_Backup
 */
class Command_Backup implements Command_CommandInterface
{
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
        $pathToBackupDirectory = $this->configuration['backup']['path'];
        $pathToDataDirectory = $this->configuration['public']['data']['path'];
        $pathToLibDirectory = $this->configuration['public']['lib']['path'];

        if (!$this->filesystem->isDirectory($pathToBackupDirectory)) {
            $this->filesystem->createDirectory($pathToBackupDirectory);
        }

        $identifierToPaths = array(
            'channels' => array(
                'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $this->configuration['backup']['file']['channels'],
                'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['channels']
            ),
            'configuration'  => array(
                'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $this->configuration['backup']['file']['configuration'],
                'public' => $pathToLibDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['lib']['file']['configuration']
            ),
            'users' => array(
                'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $this->configuration['backup']['file']['users'],
                'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['users']
            ),
            'version' => array(
                'backup' => $pathToBackupDirectory . DIRECTORY_SEPARATOR . $this->configuration['backup']['file']['version'],
                'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['version']
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
                $paths['public'],
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
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (is_null($this->configuration)) {
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