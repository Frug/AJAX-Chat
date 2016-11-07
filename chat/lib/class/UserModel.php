<?php

class UserModel
{
    private $userConn;
    private $tableName;

    function __construct($userConn, $tableName = 'user')
    {
        $this->userConn = $userConn;
        $this->tableName = $tableName;
    }

    public function getTeamUsers($client_id)
    {
        $sql = 'SELECT
					tm.user_id as user_id
				FROM
					team_member tm
				JOIN 
					team_client tc ON tc.team_id = tm.team_id
				WHERE
					tc.client_id = '.$client_id.' ;';
        // Create a new SQL query:
        $result = $this->userConn->sqlQuery($sql);
          // Stop if an error occurs:
        if($result->error()) {
            echo $result->getError();
            die();
        }

        return $result;
    }

}

