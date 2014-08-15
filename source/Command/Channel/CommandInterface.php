<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

/**
 * Interface Command_Channel_Command_CommandInterface
 */
interface Command_Channel_Command_CommandInterface extends Command_CommandInterface
{
    /**
     * @param array $channels
     */
    public function setChannels(array $channels);

    /**
     * @param File $file
     */
    public function setChannelFile(File $file);
} 