<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-21 
 */

/**
 * Class Command_VerifyInstallation_LocalFiles
 */
class Command_VerifyInstallation_LocalFiles extends Command_VerifyInstallation_AbstractCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        $pathToLibDirectory = $this->pathConfiguration->getChatPath() . DIRECTORY_SEPARATOR . 'lib';

        if (!$this->filesystem->isDirectory($pathToLibDirectory)) {
            throw new Exception(
                'directory "lib" is missing, installation needed'
            );
        }

        $pathToDataDirectory = $pathToLibDirectory . DIRECTORY_SEPARATOR . 'data';

        if (!$this->filesystem->isDirectory($pathToDataDirectory)) {
            throw new Exception(
                'directory "data" is missing, installation needed'
            );
        }

        $identifierToPublicPath = array(
            'channels' => $this->pathConfiguration->getChatChannelsFilePath(),
            'pathConfiguration'  => $this->pathConfiguration->getChatConfigurationFilePath(),
            'users' => $this->pathConfiguration->getChatUsersFilePath(),
            'version' => $this->pathConfiguration->getChatVersionFilePath()
        );

        foreach ($identifierToPublicPath as $identifier => $path) {
            if (!$this->filesystem->isFile($path)) {
                throw new Exception(
                    'file "' . $identifier . '" is missing, installation needed'
                );
            }
        }

        $pathToInstallPhp = $this->pathConfiguration->getChatPath() . DIRECTORY_SEPARATOR . 'install.php';

        if ($this->filesystem->isFile($pathToInstallPhp)) {
            throw new Exception(
                'file "' . $pathToInstallPhp . '" still available'
            );
        }
    }

    /**
     * @return array
     */
    public function getUsage()
    {
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
    }
}