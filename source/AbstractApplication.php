<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class AbstractApplication
 * This class is doing a lot. See it as class that is the
 *  - Application
 *  - Factory
 *  - ServiceLocator
 */
abstract class AbstractApplication
{
    /**
     * @var array
     */
    private $arguments = array();

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
            $command->setInput($this->getInput());
            $command->setPathConfiguration($this->getPathConfiguration());
            $command->setOutput($this->getOutput());
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
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
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
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
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
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
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
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
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
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'channel_list_command',
                $command
            );
        }

        return $this->getFromInstancePool('channel_list_command');
    }

    /**
     * @return Command_Install
     */
    public function getInstallCommand()
    {
        if ($this->isNotInInstancePool('install_command')) {
            $command = new Command_Install();
            $command->setFilesystem($this->getFilesystem());
            $command->setInput($this->getInput());
            $command->setPathConfiguration($this->getPathConfiguration());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'install_command',
                $command
            );
        }

        return $this->getFromInstancePool('install_command');
    }

    /**
     * @return Command_Restore
     */
    public function getRestoreCommand()
    {
        if ($this->isNotInInstancePool('restore_command')) {
            $command = new Command_Restore();
            $command->setFilesystem($this->getFilesystem());
            $command->setInput($this->getInput());
            $command->setPathConfiguration($this->getPathConfiguration());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'restore_command',
                $command
            );
        }

        return $this->getFromInstancePool('restore_command');
    }

    /**
     * @return Command_Upgrade
     */
    public function getUpgradeCommand()
    {
        if ($this->isNotInInstancePool('upgrade_command')) {
            $command = new Command_Upgrade();
            $command->setApplication($this);
            $command->setChangeLog($this->getFile($this->getPathConfiguration()->getChangeLogFilePath()));
            $command->setFilesystem($this->getFilesystem());
            $command->setInput($this->getInput());
            $command->setPathConfiguration($this->getPathConfiguration());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'upgrade_command',
                $command
            );
        }

        return $this->getFromInstancePool('upgrade_command');
    }

    /**
     * @return Command_User
     */
    public function getUserCommand()
    {
        if ($this->isNotInInstancePool('user_command')) {
            $command = new Command_User();
            $command->setApplication($this);
            $command->setFilesystem($this->getFilesystem());
            $command->setInput($this->getInput());
            $command->setPathConfiguration($this->getPathConfiguration());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'user_command',
                $command
            );
        }

        return $this->getFromInstancePool('user_command');
    }

    /**
     * @return Command_User_Add
     */
    public function getUserAddCommand()
    {
        if ($this->isNotInInstancePool('user_add_command')) {
            $command = new Command_User_Add();
            $command->setChannels($this->getChannels());
            $command->setOutput($this->getOutput());
            $command->setRoles($this->getRoles());
            $command->setUserFile($this->getUserFile());
            $command->setUsers($this->getUsers());
            $this->setToInstancePool(
                'user_add_command',
                $command
            );
        }

        return $this->getFromInstancePool('user_add_command');
    }

    /**
     * @return Command_User_Edit
     */
    public function getUserEditCommand()
    {
        if ($this->isNotInInstancePool('user_edit_command')) {
            $command = new Command_User_Edit();
            $command->setChannels($this->getChannels());
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
            $command->setRoles($this->getRoles());
            $command->setUserFile($this->getUserFile());
            $command->setUsers($this->getUsers());
            $this->setToInstancePool(
                'user_edit_command',
                $command
            );
        }

        return $this->getFromInstancePool('user_edit_command');
    }

    /**
     * @return Command_User_Delete
     */
    public function getUserDeleteCommand()
    {
        if ($this->isNotInInstancePool('user_delete_command')) {
            $command = new Command_User_Delete();
            $command->setChannels($this->getChannels());
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
            $command->setRoles($this->getRoles());
            $command->setUserFile($this->getUserFile());
            $command->setUsers($this->getUsers());
            $this->setToInstancePool(
                'user_delete_command',
                $command
            );
        }

        return $this->getFromInstancePool('user_delete_command');
    }

    /**
     * @return Command_User_List
     */
    public function getUserListCommand()
    {
        if ($this->isNotInInstancePool('user_list_command')) {
            $command = new Command_User_List();
            $command->setChannels($this->getChannels());
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
            $command->setRoles($this->getRoles());
            $command->setUserFile($this->getUserFile());
            $command->setUsers($this->getUsers());
            $this->setToInstancePool(
                'user_list_command',
                $command
            );
        }

        return $this->getFromInstancePool('user_list_command');
    }

    /**
     * @return Command_VerifyInstallation
     */
    public function getVerifyInstallationCommand()
    {
        if ($this->isNotInInstancePool('validate_installation_command')) {
            $command = new Command_VerifyInstallation();
            $command->setApplication($this);
            $command->setInput($this->getInput());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'validate_installation_command',
                $command
            );
        }

        return $this->getFromInstancePool('validate_installation_command');
    }

    /**
     * @return Command_VerifyInstallation_LocalFiles
     */
    public function getVerifyInstallationLocalFilesCommand()
    {
        if ($this->isNotInInstancePool('validate_installation_local_files_command')) {
            $command = new Command_VerifyInstallation_LocalFiles();
            $command->setApplication($this);
            $command->setFilesystem($this->getFilesystem());
            $command->setInput($this->getInput());
            $command->setPathConfiguration($this->getPathConfiguration());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'validate_installation_local_files_command',
                $command
            );
        }

        return $this->getFromInstancePool('validate_installation_local_files_command');
    }

    /**
     * @return Command_VerifyInstallation_Version
     */
    public function getVerifyInstallationVersionCommand()
    {
        if ($this->isNotInInstancePool('validate_installation_version_command')) {
            $command = new Command_VerifyInstallation_Version();
            $command->setApplication($this);
            $command->setFilesystem($this->getFilesystem());
            $command->setInput($this->getInput());
            $command->setPathConfiguration($this->getPathConfiguration());
            $command->setOutput($this->getOutput());
            $this->setToInstancePool(
                'validate_installation_version_command',
                $command
            );
        }

        return $this->getFromInstancePool('validate_installation_version_command');
    }
    //end of command

    //begin of file
    /**
     * @return File
     */
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

    /**
     * @return File
     */
    public function getUserFile()
    {
        if ($this->isNotInInstancePool('user_file')) {
            $this->setToInstancePool(
                'user_file',
                $this->getFile(
                    $this->getPathConfiguration()->getChatUsersFilePath()
                )
            );
        }

        return $this->getFromInstancePool('user_file');
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
     * @return array
     */
    public function getChatConfiguration()
    {
        if (empty($this->chatConfiguration)) {
            $this->setPropertyFromFile(
                'chatConfiguration',
                'config',
                $this->getPathConfiguration()->getChatConfigurationFilePath()
            );
        }

        return $this->chatConfiguration;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        return require $this->getPathConfiguration()->getChatVersionFilePath();
    }

    /**
     * @return string
     */
    public function getExampleVersion()
    {
        return require $this->getPathConfiguration()->getExampleVersionFilePath();
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
     * @return Input
     */
    public function getInput()
    {
        if ($this->isNotInInstancePool('input')) {
            $this->setToInstancePool(
                'input',
                new Input($this->getString(), $this->arguments)
            );
        }

        return $this->getFromInstancePool('input');
    }

    /**
     * @return Output
     */
    public function getOutput()
    {
        if ($this->isNotInInstancePool('output')) {
            $this->setToInstancePool(
                'output',
                new Output()
            );
        }

        return $this->getFromInstancePool('output');
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
        $this->getChatConfiguration();
        if (empty($this->roles)) {
            if (defined('AJAX_CHAT_GUEST')) {
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
     * @return String
     */
    public function getString()
    {
        if ($this->isNotInInstancePool('string')) {
            $this->setToInstancePool('string', new String());
        }

        return $this->getFromInstancePool('string');
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        //@todo - fix this damn call, if we remove this the needed constants are not defined
        $this->getChatConfiguration();
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
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
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