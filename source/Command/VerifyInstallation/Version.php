<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-21 
 */

/**
 * Class Command_VerifyInstallation_Version
 */
class Command_VerifyInstallation_Version extends Command_VerifyInstallation_AbstractCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        $exampleVersion = $this->application->getExampleVersion();
        $currentVersion = $this->application->getCurrentVersion();

        if ($exampleVersion !== $currentVersion) {
            throw new Exception(
                'current version "' . $currentVersion . '" and code version "' . $exampleVersion . '" differs, update needed'
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