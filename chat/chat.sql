DROP TABLE IF EXISTS ajax_chat_online;
CREATE TABLE ajax_chat_online (
	userID INT(10) UNSIGNED NOT NULL,
	userName VARCHAR(64) NOT NULL,
	userRole INT(1) NOT NULL,
	channel INT(10) UNSIGNED NOT NULL,
	dateTime DATETIME NOT NULL,
	ip VARBINARY(16) NOT NULL,
	PRIMARY KEY (userID),
	INDEX (userName)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS ajax_chat_messages;
CREATE TABLE ajax_chat_messages (
	id INT(11) NOT NULL AUTO_INCREMENT,
	userID INT(10) UNSIGNED NOT NULL,
	userName VARCHAR(64) NOT NULL,
	userRole INT(1) NOT NULL,
	channel INT(10) UNSIGNED NOT NULL,
	dateTime DATETIME NOT NULL,
	ip VARBINARY(16) NOT NULL,
	text TEXT,
	PRIMARY KEY (id),
	INDEX message_condition (id, channel, dateTime),
	INDEX (dateTime)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS ajax_chat_bans;
CREATE TABLE ajax_chat_bans (
	userID INT(10) UNSIGNED NOT NULL,
	userName VARCHAR(64) NOT NULL,
	dateTime DATETIME NOT NULL,
	ip VARBINARY(16) NOT NULL,
	PRIMARY KEY (userID),
	INDEX (userName),
	INDEX (dateTime)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS ajax_chat_invitations;
CREATE TABLE ajax_chat_invitations (
	userID INT(10) UNSIGNED NOT NULL,
	channel INT(10) UNSIGNED NOT NULL,
	dateTime DATETIME NOT NULL,
	PRIMARY KEY (userID, channel),
	INDEX (dateTime)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
