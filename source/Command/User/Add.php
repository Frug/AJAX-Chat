<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

/**
 * Class Command_User_Add
 */
class Command_User_Add extends Command_User_AbstractCommand
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
     * @throws Exception
     */
    public function execute()
    {
        end($this->users);
        $nextKey = (key($this->users) + 1);
        reset($this->users);

        $content = $this->file->read();

        $content[] = '// added - ' . date('Y-m-d H:i:s');
        $content[] = '$users[' . $nextKey . '] = array();';
        $content[] = '$users[' . $nextKey . '][\'userRole\'] = ' . $this->roles[$this->inputRole] . ';';
        $content[] = '$users[' . $nextKey . '][\'userName\'] = \'' . $this->inputName . '\';';
        $content[] = '$users[' . $nextKey . '][\'password\'] = \'' . $this->inputPassword . '\';';
        $content[] = '$users[' . $nextKey . '][\'channels\'] = array(' . implode(',', $this->inputChannels) . ');';

        $this->file->write($content);
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            'name="<name>" password="<password>" role=<id> channels="<id>[,<id>[...]]"',
            '   available channels: ' . implode(',', array_keys($this->channels)),
            '   available roles: ' . implode(',', array_keys($this->roles))
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if ($this->input->getNumberOfArguments() !== 4) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $channels = explode(',', $this->input->getParameterValue('channels', ''));
        $name = $this->input->getParameterValue('name');
        $password = $this->input->getParameterValue('password');
        $role = $this->input->getParameterValue('role');

        if (is_null($name)) {
            throw new Exception(
                'no name "' . $name . '" provided'
            );
        }

        if (is_null($role)) {
            throw new Exception(
                'no role "' . $role . '" provided'
            );
        } else {
            if (!isset($this->roles[$role])) {
                throw new Exception(
                    'invalid role "' . $role . '" provided'
                );
            }
        }

        if (is_null($password)) {
            throw new Exception(
                'no password "' . $password . '" provided'
            );
        }

        if (empty($channels)) {
            throw new Exception(
                'no channels provided'
            );
        }

        foreach ($channels as $channel) {
            if (!isset($this->channels[$channel])) {
                throw new Exception(
                    'invalid channel "' . $channel . '" provided'
                );
            }
        }

        $this->inputChannels = $channels;
        $this->inputName = $name;
        $this->inputPassword = $password;
        $this->inputRole = $role;
    }
}
