<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14
 */

/**
 * Class Command_Channel_Delete
 */
class Command_Channel_Delete extends Command_Channel_AbstractCommand
{
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

        foreach ($lines as $line) {
            if ($line == '// Sample channel list:') {
                $content[] = $line;
                break;
            } else {
                $content[] = $line;
            }
        }

        if (empty($this->channels)) {
            throw new Exception(
                'nothing to delete'
            );
        } else {
            unset($this->channels[$this->inputId]);
            $ids = array_values($this->channels);

            if (!empty($ids)) {
                foreach ($ids as $id => $name) {
                    $content[] = '$channels[' . $id . '] = \'' . $name . '\';';
                }
            }

            $this->file->write($content);
        }
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            'channel_id=<channel id>',
            '   available channels: ' . implode(',', array_keys($this->channels))
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

        $validIds = array_keys($this->channels);
        $inputId = $this->input->getParameterValue('channel_id');

        if (!isset($validIds[$inputId])) {
            throw new Exception(
                'invalid name "' . $inputId . '" provided'
            );
        }

        $this->inputId = $inputId;
    }
}
