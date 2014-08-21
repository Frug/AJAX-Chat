<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_Upgrade
 */
class Command_Upgrade extends Command_AbstractCommand
{
    /**
     * @var AbstractApplication
     */
    private $application;

    /**
     * @var File
     */
    private $changeLog;

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
     * @param File $file
     */
    public function setChangeLog(File $file)
    {
        $this->changeLog = $file;
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
        //__process
        $currentVersion = $this->application->getCurrentVersion();
        $finalVersion = $this->application->getExampleVersion();
        $releases = $this->fetchReleasesToProcess($currentVersion, $finalVersion);

        if (empty($releases)) {
            $this->output->addLine('nothing to do ...');
        } else {
            $pathToIndexPhp = $this->pathConfiguration->getChatPath() . DIRECTORY_SEPARATOR . 'index.php';
            $pathToBackupOfIndexPhp = $pathToIndexPhp . '.backup';

            $this->backup();
            $this->executePreUpdateTasks($pathToIndexPhp, $pathToBackupOfIndexPhp);
            $this->upgrade($releases);
            $this->executePostUpdateTasks($pathToIndexPhp, $pathToBackupOfIndexPhp);

            $this->output->addLine('upgraded from "' . $currentVersion . '" to "' . $finalVersion . '"');
        }
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
     * @throws Exception
     */
    private function backup()
    {
        $command = $this->application->getBackupCommand();

        $this->input->setArguments(array('--all'));

        $command->setInput($this->input);
        $command->setOutput($this->output);
        $command->verify();
        $command->execute();
    }

    /**
     * @param string $pathToIndexPhp
     * @param string $pathToBackupOfIndexPhp
     * @throws Exception
     */
    private function executePreUpdateTasks($pathToIndexPhp, $pathToBackupOfIndexPhp)
    {
        $this->filesystem->move($pathToIndexPhp, $pathToBackupOfIndexPhp);
        $file = $this->filesystem->createFile($pathToIndexPhp);
        $file->write(array(
            '<?php',
            'echo \'updating system, back soon\';'
        ));
    }

    /**
     * @param string $pathToIndexPhp
     * @param string $pathToBackupOfIndexPhp
     * @throws Exception
     */
    private function executePostUpdateTasks($pathToIndexPhp, $pathToBackupOfIndexPhp)
    {
        $this->filesystem->move($pathToBackupOfIndexPhp, $pathToIndexPhp);
        $this->filesystem->copy(
            $this->pathConfiguration->getExampleVersionFilePath(),
            $this->pathConfiguration->getChatVersionFilePath()
        );
    }

    private function upgrade(array $releases)
    {
        foreach ($releases as $release) {
            $path = $this->pathConfiguration->getReleasePath() . DIRECTORY_SEPARATOR . $release;
            $files = $this->fetchUpdatesFilesFromRelease($path);
            foreach ($files as $file) {
                $this->executeUpdateFile($file);
                $this->changeLog->append(
                    '[' . date('Y-m-d H:i:s', time()) . '] ' .
                    $file
                );
            }
        }
    }

    /**
     * @param string $currentVersion
     * @param string $finalVersion
     * @return array
     * @throws Exception
     */
    private function fetchReleasesToProcess($currentVersion, $finalVersion)
    {
        if (!is_dir($this->pathConfiguration->getReleasePath())) {
            throw new Exception(
                'no directory found:' . PHP_EOL .
                $this->pathConfiguration->getReleasePath()
            );
        }

        $releases = $this->filesystem->getDirectories(
            $this->pathConfiguration->getReleasePath(),
            array('upcoming'),
            false
        );
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
     * @param string $path
     * @return array
     * @throws Exception
     */
    private function fetchUpdatesFilesFromRelease($path)
    {
        if (!is_dir($path)) {
            throw new Exception(
                'provided release path is not a directory: ' . PHP_EOL . $path
            );
        }

        $files = $this->filesystem->getFiles($path);

        return $files;
    }

    /**
     * @param string $file
     * @throws Exception
     */
    private function executeUpdateFile($file)
    {
        if (!is_file($file)) {
            throw new Exception(
                'provided path "' . $file . '" is not a file'
            );
        }

        require $file;
    }
}