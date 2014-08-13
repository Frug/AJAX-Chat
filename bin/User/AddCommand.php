<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

/**
 * Class AddCommand
 */
class AddCommand extends AbstractCommand
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

        $content = $this->userFile->read();

        $content[] = '// added - ' . date('Y-m-d H:i:s');
        $content[] = '$users[' . $nextKey . '] = array();';
        $content[] = '$users[' . $nextKey . '][\'userRole\'] = ' . $this->roles[$this->inputRole] . ';';
        $content[] = '$users[' . $nextKey . '][\'userName\'] = \'' . $this->inputName . '\';';
        $content[] = '$users[' . $nextKey . '][\'password\'] = \'' . $this->inputPassword . '\';';
        $content[] = '$users[' . $nextKey . '][\'channels\'] = array(' . implode(',', $this->inputChannels) . ');';

        $this->userFile->write($content);
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            '"login name" "password" "role" "channels"',
            '   available channels: ' . implode(',', array_keys($this->channels)),
            '   available roles: ' . implode(',', array_keys($this->roles))
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) !== 6) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $channels = explode(',', trim($this->arguments[5]));
        $name = trim($this->arguments[2]);
        $password = trim($this->arguments[3]);
        $role = trim($this->arguments[4]);

        if (strlen($name) < 1) {
            throw new Exception(
                'invalid name "' . $name . '" provided'
            );
        }

        if (strlen($role) < 1) {
            throw new Exception(
                'invalid name "' . $role . '" provided'
            );
        } else {
            if (!isset($this->roles[$role])) {
                throw new Exception(
                    'invalid role "' . $role . '" provided'
                );
            }
        }

        if (strlen($password) < 1) {
            throw new Exception(
                'invalid name "' . $password . '" provided'
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
