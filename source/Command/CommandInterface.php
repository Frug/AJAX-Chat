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
     * @throws Exception
     */
    public function verify();
}