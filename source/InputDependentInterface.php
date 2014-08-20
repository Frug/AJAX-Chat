<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-20 
 */

/**
 * Interface InputDependentInterface
 */
interface InputDependentInterface
{
    /**
     * @return Input
     */
    public function getInput();

    /**
     * @param Input $input
     */
    public function setInput(Input $input);
} 