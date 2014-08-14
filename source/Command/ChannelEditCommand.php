<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14
 */

/**
 * Class ChannelEditCommand
 */
class ChannelEditCommand extends AbstractChannelCommand
{
    /**
     * @var array
     */
    private $inputChannels;

    /**
     * @var string
     */
    private $inputName;

    /**
     * @var string
     */
    private $inputPassword;

    /**
     * @var string
     */
    private $inputRole;

    /**
     * @var int
     */
    private $inputId;

    /**
     * @throws Exception
     */
    public function execute()
    {
        reset($this->channels);

        $lines = $this->file->read();
        $content = array();
        $contentAfterCurrentChannel = array();
        $string = new String();

        $foundCurrentChannelLine = false;
        $linePrefixToSearchFor = '$channels[' . $this->inputId . ']';

        foreach ($lines as $line) {
            if ($string->startsWith($line, $linePrefixToSearchFor)) {
                $foundCurrentChannelLine = true;
            } else {
                if ($foundCurrentChannelLine) {
                    $contentAfterCurrentChannel[] = $line;
                } else {
                    $content[] = $line;
                }
            }
        }

        $content[] = '$channels[' . $this->inputId . '] = \'' . $this->inputName . '\';';

        foreach ($contentAfterCurrentChannel as $line) {
            $content[] = $line;
        }

        $this->file->write($content);
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            ' "id" "name"',
            '   available channels: ' . implode(',', array_keys($this->channels))
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) !== 4) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $name = trim($this->arguments[3]);
        $id = (int) $this->arguments[2];

        if (strlen($name) < 1) {
            throw new Exception(
                'invalid name "' . $name . '" provided'
            );
        }

        if (($id === 0)) {
            throw new Exception(
                'no id provided'
            );
        } else if (!isset($this->channels[$id])) {
            throw new Exception(
                'invalid id provided'
            );
        }

        $this->inputName = $name;
        $this->inputId = $id;
    }
}