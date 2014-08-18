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
        $pathToExampleDirectory = $this->configuration['example']['path'];
        $pathToDataDirectory = $this->configuration['public']['data']['path'];
        $pathToLibDirectory = $this->configuration['public']['lib']['path'];

        $identifierToPaths = array(
            'channels' => array(
                'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $this->configuration['example']['file']['channels'],
                'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['channels']
            ),
            'application'  => array(
                'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $this->configuration['example']['file']['application'],
                'public' => $pathToLibDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['lib']['file']['application']
            ),
            'users' => array(
                'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $this->configuration['example']['file']['users'],
                'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['users']
            ),
            'version' => array(
                'example' => $pathToExampleDirectory . DIRECTORY_SEPARATOR . $this->configuration['example']['file']['version'],
                'public' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['version']
            ),
        );

        foreach ($identifierToPaths as $identifier => $paths) {
            if (!$this->filesystem->isFile($paths['public'])) {
                echo 'no ' . $identifier . ' file available, will create one ...' . PHP_EOL;
                $this->filesystem->copy(
                    $paths['example'],
                    $paths['public']
                );
            }
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
    }
}