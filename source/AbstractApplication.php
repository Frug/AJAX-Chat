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
    private $chatConfiguration = array();

    /**
     * @var array
     */
    private $channels = array();

    /**
     * @var array
     */
    private $instancePool;

    /**
     * @var array
     */
    private $roles = array();

    /**
     * @var array
     */
    private $users = array();

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
     * @return array
     */
    public function getChannels()
    {
        if (empty($this->channels)) {
            $this->setPropertyFromFile(
                'channels',
                'channels',
                $this->getPathConfiguration()->getChatChannelsFilePath()
            );
        }

        return $this->channels;
    }

    /**
     * @todo will create a notice until bootstrap.php is refactored
     * @return array
     */
    public function getChatConfiguration()
    {
        if (empty($this->chatConfiguration)) {
            $this->setPropertyFromFile(
                'chatConfiguration',
                'config',
                $this->getPathConfiguration()->getChatConfigFilePath()
            );
        }

        return $this->chatConfiguration;
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
     * @return array
     */
    public function getRoles()
    {
        if (empty($this->roles)) {
            if (defined(AJAX_CHAT_GUEST)) {
                $this->roles = array(
                    AJAX_CHAT_GUEST     => 'AJAX_CHAT_GUEST',
                    AJAX_CHAT_USER      => 'AJAX_CHAT_USER',
                    AJAX_CHAT_MODERATOR => 'AJAX_CHAT_MODERATOR',
                    AJAX_CHAT_ADMIN     => 'AJAX_CHAT_ADMIN',
                    AJAX_CHAT_CHATBOT   => 'AJAX_CHAT_CHATBOT',
                    AJAX_CHAT_CUSTOM    => 'AJAX_CHAT_CUSTOM',
                    AJAX_CHAT_BANNED    => 'AJAX_CHAT_BANNED'
                );
            }
        }

        return $this->roles;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        if (empty($this->users)) {
            $this->setPropertyFromFile(
                'users',
                'users',
                $this->getPathConfiguration()->getChatUsersFilePath()
            );
        }

        return $this->users;
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
     * @param string $propertyName
     * @param string $fileValueName
     * @param string $filePath
     * @throws Exception
     */
    private function setPropertyFromFile($propertyName, $fileValueName, $filePath)
    {
        if (!is_file($filePath)) {
            throw new Exception(
                'provided file path is not valid: ' . $filePath
            );
        }

        require $filePath;
        $this->$propertyName = $$fileValueName;
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