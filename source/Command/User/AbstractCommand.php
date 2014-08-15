<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

/**
 * Class Command__AbstractUserCommand
 */
abstract class Command__AbstractUserCommand extends Command_AbstractCommand implements Command_User_CommandInterface
{
    /**
     * @var array
     */
    protected $channels;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var File
     */
    protected $file;

    /**
     * @var array
     */
    protected $users;

    /**
     * @param array $channels
     */
    public function setChannels(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param \File $userFile
     */
    public function setUserFile(File $userFile)
    {
        $this->file = $userFile;
    }

    /**
     * @param array $users
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
    }
} 