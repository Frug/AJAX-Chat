<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Interface Command_CommandInterface
 */
interface Command_CommandInterface extends InputDependentInterface, OutputDependentInterface
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
     * @throws Exception
     */
    public function verify();
}