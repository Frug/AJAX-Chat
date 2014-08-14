<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Class AbstractCommand
 */
abstract class AbstractCommand implements CommandInterface
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