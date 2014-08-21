<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

/**
 * Class Command_User_Delete
 */
class Command_User_Delete extends Command_User_AbstractCommand
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
        reset($this->users);

        $lines = $this->file->read();
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
            unset($this->users[$this->inputId]);
            $idToUser = array_values($this->users);

            if (!empty($idToUser)) {
                foreach ($idToUser as $id => $user) {
                    ++$id; //we have to increase by one since we have to prevent overwriting the "0" user
                    $content[] = '// updated - ' . date('Y-m-d H:i:s');
                    $content[] = '$users[' . $id . '] = array();';
                    $content[] = '$users[' . $id . '][\'userRole\'] = ' . $this->roles[$user['userRole']] . ';';
                    $content[] = '$users[' . $id . '][\'userName\'] = \'' . $user['userName'] . '\';';
                    $content[] = '$users[' . $id . '][\'password\'] = \'' . $user['password'] . '\';';
                    $content[] = '$users[' . $id . '][\'channels\'] = array(' . implode(',', $user['channels']) . ');';
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
        $users = $this->users;
        unset($users[0]);

        return array(
            'userid=<id>',
            '   available users: ' . implode(',', array_keys($users))
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

        $validIds = array_keys($this->users);
        $userId = $this->input->getParameterValue('user_id');

        if (!isset($validIds[$userId])) {
            throw new Exception(
                'invalid id "' . $userId . '" provided'
            );
        }

        if ($userId === 0) {
            throw new Exception(
                'you are not allowed to delete id "' . $userId . '"'
            );
        }

        $this->inputId = $userId;
    }
}
