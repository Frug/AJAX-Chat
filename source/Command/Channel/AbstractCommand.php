<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

/**
 * Class Command_Channel_AbstractCommand
 */
abstract class Command_Channel_AbstractCommand extends Command_AbstractCommand implements Command_Channel_CommandInterface
{
    /**
     * @var File
     */
    protected $file;

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
        $this->file = $file;
    }
}