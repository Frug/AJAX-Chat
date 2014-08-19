<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14
 */

/**
 * Class Command_Channel_List
 */
class Command_Channel_List extends Command_Channel_AbstractCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        $numberOfChannels = count($this->channels);
        $iterator = $numberOfChannels;

        $this->output->addLine('number of channels: ' . $numberOfChannels);

        //@todo implement output styling
        if ($numberOfChannels > 0) {
            $this->output->addLine();
            $this->output->addLine('id | name ');
            $this->output->addLine('--------');

            foreach ($this->channels as $id => $name) {
                $this->output->addLine(
                        implode(
                        ' | ',
                        array(
                            $id,
                            $name
                        )
                    )
                );
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
