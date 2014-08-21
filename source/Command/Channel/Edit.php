<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14
 */

/**
 * Class Command_Channel_Edit
 */
class Command_Channel_Edit extends Command_Channel_AbstractCommand
{
    /**
     * @var string
     */
    private $inputName;

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
            'id=<id> name="<name>"',
            '   available channels: ' . implode(',', array_keys($this->channels))
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if ($this->input->getNumberOfArguments() !== 2) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $name = $this->input->getParameterValue('name');
        $id = $this->input->getParameterValue('id');

        if (is_null($name)) {
            throw new Exception(
                'no name provided'
            );
        }

        if (is_null($id)) {
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