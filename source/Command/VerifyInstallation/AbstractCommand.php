<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-21 
 */

/**
 * Class Command_VerifyInstallation_AbstractCommand
 */
abstract class Command_VerifyInstallation_AbstractCommand extends Command_AbstractCommand
{
    /**
     * @var AbstractApplication
     */
    protected $application;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Configuration_Path
     */
    protected $pathConfiguration;

    /**
     * @param AbstractApplication $application
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param Configuration_Path $configuration
     */
    public function setPathConfiguration(Configuration_Path $configuration)
    {
        $this->pathConfiguration = $configuration;
    }
}