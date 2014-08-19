<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

/**
 * Class Command_User_List
 */
class Command_User_List extends Command_User_AbstractCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        unset($this->users[0]);
        //remove initial user since we are not allowed to remove this user
        $numberOfUsers = count($this->users);
        $iterator = $numberOfUsers;

        $this->output->addLine('number of users: ' . $numberOfUsers);

        //@todo implement output styling
        if ($numberOfUsers > 0) {
            $this->output->addLine();
            $this->output->addLine('id | name | role | channels');
            $this->output->addLine('----------------');

            foreach ($this->users as $id => $user) {
                $this->output->addLine(
                    implode(
                        ' | ',
                        array(
                            $id,
                            $user['userName'],
                            $user['userRole'],
                            implode(',', $user['channels'])
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
