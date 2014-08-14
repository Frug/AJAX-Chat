<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

/**
 * Interface ChannelCommandInterface
 */
interface ChannelCommandInterface extends CommandInterface
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