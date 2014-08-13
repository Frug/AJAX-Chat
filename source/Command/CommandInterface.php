<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Interface CommandInterface
 */
interface CommandInterface
{
    /**
     * @throws Exception
     */
    public function execute();

    /**
     * @return array
     */
    public function getUsage();

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments);

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

    /**
     * @throws Exception
     */
    public function verify();
}