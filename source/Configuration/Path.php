<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Configuration_Path
 */
class Configuration_Path
{
    /**
     * @var string
     */
    private $pathToRootDirectory;

    /**
     * @param string $relativePathToRootDirectory - relative path from Configuration_Path file
     * @throws Exception
     */
    public function __construct($relativePathToRootDirectory)
    {
        $this->pathToRootDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . $relativePathToRootDirectory);

        if (!is_dir($relativePathToRootDirectory)) {
            throw new Exception(
                'provided root path is not a directory: ' . PHP_EOL .
                '"' . $this->pathToRootDirectory . '"'
            );
        }
    }

    //begin of path
    /**
     * @return string
     */
    public function getBackupPath()
    {
        return $this->pathToRootDirectory . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'backup';
    }

    /**
     * @return string
     */
    public function getBackupChannelsFilePath()
    {
        return $this->getBackupPath() . DIRECTORY_SEPARATOR . $this->getChannelsFileName();
    }

    /**
     * @return string
     */
    public function getBackupConfigurationFilePath()
    {
        return $this->getBackupPath() . DIRECTORY_SEPARATOR . $this->getConfigurationFileName();
    }

    /**
     * @return string
     */
    public function getBackupUsersFilePath()
    {
        return $this->getBackupPath() . DIRECTORY_SEPARATOR . $this->getUsersFileName();
    }

    /**
     * @return string
     */
    public function getBackupVersionFilePath()
    {
        return $this->getBackupPath() . DIRECTORY_SEPARATOR . $this->getVersionFileName();
    }

    /**
     * @return string
     */
    public function getExamplePath()
    {
        return $this->pathToRootDirectory . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'example';
    }

    /**
     * @return string
     */
    public function getExampleChannelsFilePath()
    {
        return $this->getExamplePath() . DIRECTORY_SEPARATOR . $this->getChannelsFileName();
    }

    /**
     * @return string
     */
    public function getExampleConfigurationFilePath()
    {
        return $this->getChatPath() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $this->getConfigurationFileName() . '.example';
    }

    /**
     * @return string
     */
    public function getExampleUsersFilePath()
    {
        return $this->getExamplePath() . DIRECTORY_SEPARATOR . $this->getUsersFileName();
    }

    /**
     * @return string
     */
    public function getExampleVersionFilePath()
    {
        return $this->getExamplePath() . DIRECTORY_SEPARATOR . $this->getVersionFileName();
    }

    /**
     * @return string
     */
    public function getChatPath()
    {
        return $this->pathToRootDirectory . DIRECTORY_SEPARATOR . 'chat';
    }

    /**
     * @return string
     */
    public function getChatChannelsFilePath()
    {
        return $this->getChatPath() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $this->getChannelsFileName();
    }

    /**
     * @return string
     */
    public function getChatClassesFilePath()
    {
        return $this->getChatPath() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $this->getClassesFileName();
    }

    /**
     * @return string
     */
    public function getChatConfigurationFilePath()
    {
        return $this->getChatPath() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $this->getConfigurationFileName();
    }

    /**
     * @return string
     */
    public function getChatUsersFilePath()
    {
        return $this->getChatPath() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $this->getUsersFileName();
    }

    /**
     * @return string
     */
    public function getChatVersionFilePath()
    {
        return $this->getChatPath() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $this->getVersionFileName();
    }

    /**
     * @return string
     */
    public function getReleasePath()
    {
        return $this->pathToRootDirectory . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'release';
    }
    //end of path

    //begin of file name
    /**
     * @return string
     */
    public function getChannelsFileName()
    {
        return 'channels.php';
    }

    /**
     * @return string
     */
    public function getClassesFileName()
    {
        return 'classes.php';
    }

    /**
     * @return string
     */
    public function getConfigurationFileName()
    {
        return 'config.php';
    }

    /**
     * @return string
     */
    public function getUsersFileName()
    {
        return 'users.php';
    }

    /**
     * @return string
     */
    public function getVersionFileName()
    {
        return 'version.php';
    }
    //end of file name
}