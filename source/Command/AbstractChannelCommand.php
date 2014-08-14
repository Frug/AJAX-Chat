<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

/**
 * Class AbstractChannelCommand
 */
abstract class AbstractChannelCommand extends AbstractCommand implements ChannelCommandInterface
{
    /**
     * @var File
     */
    protected $channelFile;

    /**
     * @var array
     */
    protected $channels;

    /**
     * @param array $channels
     */
    public function setChannels(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @param File $file
     */
    public function setChannelFile(File $file)
    {
        $this->channelFile = $file;
    }
}