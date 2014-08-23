<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_VerifyInstallation
 */
class Command_VerifyInstallation extends Command_AbstractCommand
{
    /**
     * @var AbstractApplication
     */
    protected $application;

    /**
     * @param AbstractApplication $application
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $this->verifyLocalFiles($this->application, $this->input, $this->output);
        $this->verifyVersion($this->application, $this->input, $this->output);
        //@todo fetch current version from url
    }

    /**
     * @param AbstractApplication $application
     */
    private function verifyLocalFiles(AbstractApplication $application)
    {
        $command = $application->getVerifyInstallationLocalFilesCommand();
        $command->verify();
        $command->execute();
    }

    /**
     * @param AbstractApplication $application
     */
    private function verifyVersion(AbstractApplication $application)
    {
        $command = $application->getVerifyInstallationVersionCommand();
        $command->verify();
        $command->execute();
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