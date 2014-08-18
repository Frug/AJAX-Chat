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

    //begin of command
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
     * @return Command_Channel
     */
    public function getChannelCommand()
    {
        if ($this->isNotInInstancePool('channel_command')) {
            $command = new Command_Channel();
            $command->setApplication($this);
            $command->setFilesystem($this->getFilesystem());
            $this->setToInstancePool(
                'channel_command',
                $command
            );
        }

        return $this->getFromInstancePool('channel_command');
    }

    /**
     * @return Command_Channel_Add
     */
    public function getChannelAddCommand()
    {
        if ($this->isNotInInstancePool('channel_add_command')) {
            $command = new Command_Channel_Add();
            $command->setChannelFile($this->getChannelFile());
            $command->setChannels($this->getChannels());
            $this->setToInstancePool(
                'channel_add_command',
                $command
            );
        }

        return $this->getFromInstancePool('channel_add_command');
    }

    /**
     * @return Command_Channel_Delete
     */
    public function getChannelDeleteCommand()
    {
        if ($this->isNotInInstancePool('channel_delete_command')) {
            $command = new Command_Channel_Delete();
            $command->setChannelFile($this->getChannelFile());
            $command->setChannels($this->getChannels());
            $this->setToInstancePool(
                'channel_delete_command',
                $command
            );
        }

        return $this->getFromInstancePool('channel_delete_command');
    }

    /**
     * @return Command_Channel_Edit
     */
    public function getChannelEditCommand()
    {
        if ($this->isNotInInstancePool('channel_edit_command')) {
            $command = new Command_Channel_Edit();
            $command->setChannelFile($this->getChannelFile());
            $command->setChannels($this->getChannels());
            $this->setToInstancePool(
                'channel_edit_command',
                $command
            );
        }

        return $this->getFromInstancePool('channel_edit_command');
    }

    /**
     * @return Command_Channel_List
     */
    public function getChannelListCommand()
    {
        if ($this->isNotInInstancePool('channel_list_command')) {
            $command = new Command_Channel_List();
            $command->setChannelFile($this->getChannelFile());
            $command->setChannels($this->getChannels());
            $this->setToInstancePool(
                'channel_list_command',
                $command
            );
        }

        return $this->getFromInstancePool('channel_list_command');
    }
    //end of command

    //begin of file
    public function getChannelFile()
    {
        if ($this->isNotInInstancePool('channel_file')) {
            $this->setToInstancePool(
                'channel_file',
                $this->getFile(
                    $this->getPathConfiguration()->getChatChannelsFilePath()
                )
            );
        }

        return $this->getFromInstancePool('channel_file');
    }
    //end of file

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
     * @param null|string $path
     * @return File
     */
    public function getFile($path = null)
    {
        return new File($path);
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