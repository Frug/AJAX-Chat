<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-19
 */

/**
 * Interface OutputDependentInterface
 */
interface OutputDependentInterface
{
    /**
     * @return null|Output
     */
    public function getOutput();

    /**
     * @param Output $output
     */
    public function setOutput(Output $output);
}