<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14
 */

/**
 * Class ChannelDeleteCommand
 */
class ChannelDeleteCommand extends AbstractChannelCommand
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
            'command: delete [channel id]',
            '   available channels: ' . implode(',', array_keys($this->channels))
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) !== 3) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $validIds = array_keys($this->channels);
        $inputId = (int) $this->arguments[2];

        if (!isset($validIds[$inputId])) {
            throw new Exception(
                'invalid name "' . $inputId . '" provided'
            );
        }

        $this->inputId = $inputId;
    }
}
