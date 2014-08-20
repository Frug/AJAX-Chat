<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Application_Cli
 */
class Application_Cli extends AbstractApplication
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        global $argv;

        $isNotCalledFromCommandLineInterface = (PHP_SAPI !== 'cli');

        if ($isNotCalledFromCommandLineInterface) {
            throw new Exception(
                'command line script only '
            );
        }
        $arguments = $argv;
        array_shift($arguments);
        $this->setArguments($arguments);
    }
}