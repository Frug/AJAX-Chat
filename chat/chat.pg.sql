DROP TABLE IF EXISTS ajax_chat_online;
CREATE TABLE ajax_chat_online (
	userID INT NOT NULL,
	userName VARCHAR(64) NOT NULL,
	userRole SMALLINT NOT NULL default 0,
	channel INT NOT NULL default 0,
	dateTime TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	ip varchar(255) NOT NULL,
	PRIMARY KEY (userID)
);
CREATE INDEX ajax_chat_online_userName_idx ON ajax_chat_online (userName);

DROP TABLE IF EXISTS ajax_chat_messages;
CREATE TABLE ajax_chat_messages (
	id SERIAL NOT NULL,
	userID INT NOT NULL,
	userName VARCHAR(64) NOT NULL,
	userRole INT NOT NULL default 0,
	channel INT NOT NULL default 0,
	dateTime TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	ip varchar(255) NOT NULL,
	text TEXT,
	PRIMARY KEY (id)
);
CREATE INDEX message_condition ON ajax_chat_messages (id, channel, dateTime);
CREATE INDEX ajax_chat_messages_dateTime_idx ON ajax_chat_messages (dateTime);

DROP TABLE IF EXISTS ajax_chat_bans;
CREATE TABLE ajax_chat_bans (
	userID INT NOT NULL,
	userName VARCHAR(64) NOT NULL,
	dateTime TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	ip varchar(255) NOT NULL,
	PRIMARY KEY (userID)
);
CREATE INDEX ajax_chat_bans_userName_idx ON ajax_chat_bans (userName);
CREATE INDEX ajax_chat_bans_dateTime_idx ON ajax_chat_bans (dateTime);

DROP TABLE IF EXISTS ajax_chat_invitations;
CREATE TABLE ajax_chat_invitations (
	userID INT NOT NULL,
	channel INT NOT NULL default 0,
	dateTime TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	PRIMARY KEY (userID, channel)
);
CREATE INDEX ajax_chat_invitations_dateTime_idx ON ajax_chat_invitations (dateTime);
