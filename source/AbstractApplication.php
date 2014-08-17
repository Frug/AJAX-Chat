<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class AbstractApplication
 */
abstract class AbstractApplication
{

    /**
     * @var array
     */
    private $instancePool;

    /**
     * @return Command_Backup
     */
    public function getBackupCommand()
    {
        if ($this->isNotInInstancePool('command_backup')) {
            $command = new Command_Backup();
            $command->setFilesystem($this->getFilesystem());
            $command->setPathConfiguration($this->getPathConfiguration());
            $this->setToInstancePool('command_backup', $command);
        }

        return $this->getFromInstancePool('command_backup');
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        if ($this->isNotInInstancePool('filesystem')) {
            $filesystem = new Filesystem();
            $this->setToInstancePool('filesystem', $filesystem);
        }

        return $this->getFromInstancePool('filesystem');
    }

    /**
     * @return Configuration_Path
     */
    public function getPathConfiguration()
    {
        if ($this->isNotInInstancePool('configuration_path')) {
            $configuration = new Configuration_Path('..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
            $this->setToInstancePool('configuration_path', $configuration);
        }

        return $this->getFromInstancePool('configuration_path');
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getFromInstancePool($key)
    {
        return $this->instancePool[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    private function isNotInInstancePool($key)
    {
        return (!(isset($this->instancePool[$key])));
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    private function setToInstancePool($key, $value)
    {
        $this->instancePool[$key] = $value;
    }
} 