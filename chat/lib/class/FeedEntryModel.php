<?php

/**
 * @petre tudor
 */
class FeedEntryModel
{
    private $feedConn;
    private $tableName;

    function __construct($feedConn, $tableName)
    {
        $this->feedConn = $feedConn;
        $this->tableName = $tableName;
    }

    public function insert($userID, $userName, $userRole, $channelID, $text, $ip=null, $mode=0)
    {
        $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
        $entryType = $this->getEntryTypeFromMode($mode);

        $entryData = json_encode([
            'userRole' => $this->feedConn->makeSafe($userRole),
            'channel' => $this->feedConn->makeSafe($channelID),
            'ip' => $this->feedConn->makeSafe($ip)
        ]);

		$sql = 'INSERT INTO '.$this->tableName.'(
                                id_code,
                                entry_time,
                                author_name,
                                author_code,
                                author_url,
                                author_image_url,
                                entry_types,
                                status_code,
                                entry_text,
                                entry_data,
                                last_update
                    )
				VALUES (
                    '.$this->feedConn->makeSafe($userID.'_'.microtime()).',
                    NOW(),
                    '.$this->feedConn->makeSafe($userName).',
					'.$this->feedConn->makeSafe($userID).',
                    "",
                    "",
                    '.$this->feedConn->makeSafe($entryType).',
                    "NEW",
                    '.$this->feedConn->makeSafe($text).',
                    '.$this->feedConn->makeSafe($entryData).',
					NOW()
				);';

		// Create a new SQL query:
		$result = $this->feedConn->sqlQuery($sql);

		// Stop if an error occurs:
		if($result->error()) {
			echo $result->getError();
			die();
		}
        return true;
    }

    private function getEntryTypeFromMode($mode)
    {
        // The $mode parameter is used for socket updates:
		// 0 = normal messages
		// 1 = channel messages (e.g. login/logout, channel enter/leave, kick)
		// 2 = messages with online user updates (nick)

        return 'MESSAGE';
    }
}
