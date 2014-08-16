<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_User
 */
class Command_User extends Command_AbstractCommand
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $commandToClassName = array(
        'add'       => 'Command_User_Add',
        'edit'      => 'Command_User_Edit',
        'delete'    => 'Command_User_Delete',
        'list'      => 'Command_User_List'
    );

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $roles;

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param \Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $pathToChannelsPhp = $this->configuration['public']['data']['path'] . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['channels'];
        $pathToUsersPhp = $this->configuration['public']['data']['path'] . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['users'];

        require_once $pathToChannelsPhp;
        require_once $pathToUsersPhp;

        $commandClass = $this->commandToClassName[$this->command];
        $fileToUsers = new File($pathToUsersPhp);

        /** @var Command_User_CommandInterface $command */
        $command = new $commandClass();

        //@todo move channels and users into setters
        $command->setArguments($this->arguments);
        $command->setChannels($channels);
        $command->setRoles($this->roles);
        $command->setUsers($users);
        $command->setUserFile($fileToUsers);

        try {
            $command->verify();
        } catch (Exception $exception) {
            throw new Exception($this->command . ' ' . implode("\n", $command->getUsage()));
        }
        $command->execute();
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            '[' . implode('|', array_keys($this->commandToClassName)) . ']'
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) < 2) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $command = trim($this->arguments[1]);

        if (!(isset($this->commandToClassName[$command]))) {
            throw new Exception(
                'invalid command provided'
            );
        }

        $this->command = $command;
    }
}