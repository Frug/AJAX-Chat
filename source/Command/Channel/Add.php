<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14
 */

/**
 * Class Command_Channel_Add
 */
class Command_Channel_Add extends Command_Channel_AbstractCommand
{
    /**
     * @var string
     */
    private $inputName;

    /**
     * @throws Exception
     */
    public function execute()
    {
        end($this->channels);
        $nextKey = (key($this->channels) + 1);
        reset($this->channels);

        $content = $this->file->read();

        $content[] = '// added - ' . date('Y-m-d H:i:s');
        $content[] = '$channels[' . $nextKey . '] = \'' . $this->inputName . '\';';

        $this->file->write($content);
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            'name="<channel name>"',
            '   available channels: ' . implode(',', array_keys($this->channels)),
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if ($this->input->getNumberOfArguments() !== 1) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $name = $this->input->getParameterValue('name');

        if (is_null($name)) {
            throw new Exception(
                'invalid name "' . $name . '" provided'
            );
        }

        $this->inputName = $name;
    }
}
