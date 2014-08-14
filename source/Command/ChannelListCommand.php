<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14
 */

/**
 * Class ChannelListCommand
 */
class ChannelListCommand extends AbstractChannelCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        $numberOfChannels = count($this->channels);
        $iterator = $numberOfChannels;

        echo 'number of channels: ' . $numberOfChannels . PHP_EOL;

        //@todo implement output styling
        if ($numberOfChannels > 0) {
            echo PHP_EOL;
            echo 'id | name ' . PHP_EOL;
            echo '--------' . PHP_EOL;

            foreach ($this->channels as $id => $name) {
                echo implode(
                    ' | ',
                    array(
                        $id,
                        $name
                    )
                );

                if ($iterator > 0) {
                    echo PHP_EOL;
                    --$iterator;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array();
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
    }
}
