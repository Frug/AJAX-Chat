<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-16 
 */

class Command_Channel extends Command_AbstractCommand
{
    /**
     * @var array
     */
    private $channels;

    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $commandToClassName = array(
            'add'       => 'Command_Channel_Add',
            'edit'      => 'Command_Channel_Edit',
            'delete'    => 'Command_Channel_Delete',
            'list'      => 'Command_Channel_List'
        );

    /**
     * @param array $channels
     */
    public function setChannels(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $pathToChannelsPhp = $this->configuration['public']['data']['path'] . DIRECTORY_SEPARATOR . $this->configuration['public']['data']['file']['channels'];

        $commandClass = $this->commandToClassName[$this->command];
        $fileToChannels = new File($pathToChannelsPhp);

        /** @var Command_Channel_CommandInterface $command */
        $command = new $commandClass();

        $command->setArguments($this->arguments);
        $command->setChannels($this->channels);
        $command->setChannelFile($fileToChannels);

        try {
            $command->verify();
        } catch (Exception $exception) {
            throw new Exception($this->command . ' ' . implode("\n", $command->getUsage()));
        }
        $command->execute();
    }

    /**
     * @return array
     */
    public function getUsage()
    {

        return array(
            '[' . implode('|', array_keys($this->commandToClassName)) . ']'
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) < 2) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $inputCommand = trim($this->arguments[1]);

        if (!(isset($this->commandToClassName[$inputCommand]))) {
            throw new Exception(
                'invalid command provided'
            );
        }

        $this->command = $inputCommand;
    }
}