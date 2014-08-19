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
     * @var AbstractApplication
     */
    private $application;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Configuration_Path
     */
    private $pathConfiguration;

    /**
     * @param AbstractApplication $application
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
    }

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
        //__general
        $releases = $this->fetchReleasesToProcess();

        foreach ($releases as $release) {
            $updateFiles = $this->fetchUpdatesFilesFromRelease($release);
            //@todo output per release
            foreach ($updateFiles as $updateFile) {
                //@todo output per file
                $this->executeUpdateFile($updateFile);
            }
        }
        //@todo implement changelog
        //update version

        //there should be a version directory per release
        //there should be a "not released" directory that contains all prepared updates
        //each version directory can contain multiple update php files
        //  prefix could be update_yyyy_mm_dd_hh_ii_ss_maintainer_-_$description.php
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

    /**
     * @return array
     */
    private function fetchReleasesToProcess()
    {
        $releases = $this->filesystem->getDirectories(
            $this->pathConfiguration->getReleasePath(),
            array('upcoming'),
            false
        );
//@todo replace by real value
$currentVersion = '0.8.8';
        $releasesToProcess = array();
        foreach ($releases as $release) {
            $isNewer = version_compare($release, $currentVersion, '>');
            if ($isNewer) {
                $releasesToProcess[] = $release;
            }
        }
        natsort($releasesToProcess);

        return $releasesToProcess;
    }

    /**
     * @param string $release
     * @return array
     * @todo add validation
     */
    private function fetchUpdatesFilesFromRelease($release)
    {
        $updateFiles = array();
        //@todo

        return $updateFiles;
    }

    /**
     * @todo add validation
     * @param string $file
     * @throws Exception
     */
    private function executeUpdateFile($file)
    {
        $application = $this->application;

        require $file;
    }
}