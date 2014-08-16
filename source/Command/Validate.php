<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_Validate
 */
class Command_Validate extends Command_AbstractCommand
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
        $pathToDataDirectory = $this->configuration['public']['data']['path'];
        $pathToExampleDirectory = $this->configuration['example']['path'];
        $pathToLibDirectory = $this->configuration['public']['lib']['path'];

        if (!$this->filesystem->isDirectory($pathToLibDirectory)) {
            throw new Exception(
                'directory "lib" is missing, installation needed'
            );
        }

        if (!$this->filesystem->isDirectory($pathToDataDirectory)) {
            throw new Exception(
                'directory "data" is missing, installation needed'
            );
        }

        $identifierToPublicPath = array(
            'channels' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['channels'],
            'configuration'  => $pathToLibDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['lib']['file']['configuration'],
            'users' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['users'],
            'version' => $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['version']
        );

        foreach ($identifierToPublicPath as $identifier => $path) {
            if (!$this->filesystem->isFile($path)) {
                throw new Exception(
                    'file "' . $identifier . '" is missing, installation needed'
                );
            }
        }

        $exampleVersion = require_once $pathToExampleDirectory . DIRECTORY_SEPARATOR . $this->configuration['example']['file']['version'];
        $publicVersion = require_once $pathToDataDirectory . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['version'];

        if ($exampleVersion !== $publicVersion) {
            throw new Exception(
                'current version "' . $publicVersion . '" and code version "' . $exampleVersion . '" differs, update needed'
            );
        }

        echo 'installation is valid.' . PHP_EOL;
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