<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

/**
 * Class UserDeleteCommand
 */
class UserDeleteCommand extends AbstractUserCommand
{
    /**
     * @var int
     */
    private $inputUserId;

    /**
     * @throws Exception
     */
    public function execute()
    {
        reset($this->users);

        $lines = $this->userFile->read();
        $content = array();

        foreach ($lines as $line) {
            if ($line == '$users[0][\'channels\'] = array(0);') {
                $content[] = $line;
                $content[] = '';
                break;
            } else {
                $content[] = $line;
            }
        }

        unset($this->users[0]);

        if (empty($this->users)) {
            throw new Exception(
                'nothing to delete'
            );
        } else {
            unset($this->users[$this->inputUserId]);
            $userIds = array_values($this->users);

            if (!empty($userIds)) {
                foreach ($userIds as $id => $user) {
                    $content[] = '// updated - ' . date('Y-m-d H:i:s');
                    $content[] = '$users[' . $id . '] = array();';
                    $content[] = '$users[' . $id . '][\'userRole\'] = ' . $this->roles[$user['userRole']] . ';';
                    $content[] = '$users[' . $id . '][\'userName\'] = \'' . $user['userName'] . '\';';
                    $content[] = '$users[' . $id . '][\'password\'] = \'' . $user['password'] . '\';';
                    $content[] = '$users[' . $id . '][\'channels\'] = array(' . implode(',', $user['channels']) . ');';
                }
            }

            $this->userFile->write($content);
        }
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            'command: delete [user id]',
            '   available users: ' . implode(',', array_keys($this->users))
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

        $validUserIds = array_keys($this->users);
        $inputUserId = (int) $this->arguments[2];

        if (!isset($validUserIds[$inputUserId])) {
            throw new Exception(
                'invalid name "' . $inputUserId . '" provided'
            );
        }

        $this->inputUserId = $inputUserId;
    }
}
