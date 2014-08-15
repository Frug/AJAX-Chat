<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

/**
 * Interface Command_User_CommandInterface
 */
interface Command_User_CommandInterface extends Command_CommandInterface
{
    /**
     * @param array $channels
     */
    public function setChannels(array $channels);

    /**
     * @param array $roles
     */
    public function setRoles(array $roles);

    /**
     * @param \File $userFile
     */
    public function setUserFile(File $userFile);

    /**
     * @param array $users
     */
    public function setUsers(array $users);
}