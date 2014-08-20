<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-16 
 */

class Command_Channel extends Command_AbstractCommand
{
    /**
     * @var AbstractApplication
     */
    private $application;

    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $commands = array(
            'add',
            'edit',
            'delete',
            'list'
        );

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param AbstractApplication $application
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
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
        switch ($this->command) {
            case 'add':
                $command = $this->application->getChannelAddCommand();
                break;
            case 'edit':
                $command = $this->application->getChannelEditCommand();
                break;
            case 'delete':
                $command = $this->application->getChannelDeleteCommand();
                break;
            case 'list':
                $command = $this->application->getChannelListCommand();
                break;
            default:
                throw new Exception(
                    'unsupported command "' . $this->command . '"'
                );
        }

        $command->setInput($this->input);
        $command->setOutput($this->output);

        try {
            $command->verify();
        } catch (Exception $exception) {
            throw new Exception(
                $this->command . ' ' . implode("\n", $command->getUsage()) . PHP_EOL .
                PHP_EOL .
                $exception->getMessage()
            );
        }
        $command->execute();
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            '[--' . implode('|--', $this->commands) . ']'
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        $this->command = null;

        if ($this->input->getNumberOfArguments() < 1) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        foreach ($this->commands as $command) {
            if ($this->input->hasLongOption($command)) {
                $this->command = $command;
                break;
            }
        }

        if (is_null($this->command)) {
            throw new Exception(
                'invalid command provided'
            );
        }

        $this->input->removeLongOption($this->command);
    }
}