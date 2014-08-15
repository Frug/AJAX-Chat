<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Class Command_AbstractCommand
 */
abstract class Command_AbstractCommand implements Command_CommandInterface
{
    /**
     * @var array
     */
    protected $arguments;

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }
}