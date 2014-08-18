<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_Update
 */
class Command_Update extends Command_AbstractCommand
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
        //__general
        //there should be a version directory per release
        //there should be a "not released" directory that contains all prepared updates
        //each version directory can contain multiple update php files, prefixed with a suffix
        //  suffix could be yyyy_mm_dd_hh_ii_ss_maintainer.php
        //  this fill will be required_once and should work "out of the box"

        //__process
        //check if current version is below example/version
        //@todo use Command_Validate_Version
        //backup existing data
        //@todo use Command_Update all
        //get version steps
        //move index.php to .index.php
        //create index.php with content "<?php\necho 'updating ...' . PHP_EOL;"
        //foreach version step, execute db deployment and file deployment when needed
        //  add changelog writing while updateing
        //move .index.php to index.php
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