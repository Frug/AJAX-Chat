AJAX Chat
=========

v 0.8.6 smf

This is the Simple Machines Forum integration version:
http://www.simplemachines.org/

AJAX stands for "Asynchronous JavaScript and XML".
The AJAX Chat clients (the user browsers) use JavaScript to query the web server for updates.
Instead of delivering a complete HTML page only updated data is send in XML format.
By using JavaScript the chat page can be updated without having to reload the whole page.


Requirements
============

Server-Side:

- PHP >= 4
- MySQL >= 4 or PostgreSQL >= 8.1
- Ruby >= 1.8 (optional)


Client-Side:

- Enabled JavaScript
- Enabled Cookies
- Flash Plugin >= 9 (optional)


Installation
============

1.	Upload to the server
	--------------------

	Upload the chat folder to your server into your SMF forum directory:
	e.g. http://example.org/forum/chat/


2.	Creation of database tables
	---------------------------

	Execute the provided installation script by visiting the following URL with your browser:
	http://example.org/forum/chat/install.php
	
	Replace "http://example.org/forum/chat/" with the real URL to your chat directory.


3.	Delete the installation script
	------------------------------
	
	Delete the file install.php from the chat directory on your server.


Ready! Just place a link to the chat directory on your forum. :)


Configuration files:
====================

AJAX Chat is fully customizable and contains two configuration files:

1.	lib/config.php
	--------------
	This file contains the server side (PHP) settings.

2.	js/config.js
	------------
	This file contains the client side (JavaScript) settings.

Each configuration option is explained with a comment prior to the setting assignment.


Customizing the layout:
=======================

The layout of AJAX Chat is fully customizable by using CSS (Cascaded Style Sheets).
AJAX Chat comes with a predefined set of styles. To add your own style, do the following:

1.	Add a new CSS file (e.g. mystyle.css) by copying one of the existing styles from the CSS directory.

2.	Edit your file (css/mystyle.css) and adjust the CSS settings to your liking.

3.	Add the name of your style without file extension to the available styles in lib/config.php:

	// Available styles:
	$config['styleAvailable'] = array('mystyle','beige','black','grey');
	// Default style:
	$config['styleDefault'] = 'mystyle';

To further customize the layout you can adjust the template files in lib/template/.

Make sure you are creating valid XHTML, else you will produce errors in modern browsers.
This is due to the page content-type served as "application/xhtml+xml".
Using this content-type improves performance when manipulating the Document Object Model (DOM).

If for some reason you cannot create valid XHTML you can force a HTML content-type.
Just edit lib/config.php and set the following option:

	$config['contentType'] = 'text/html';


Adjusting the language settings:
================================

AJAX Chat comes with two language file directories:

1.	js/lang/
	------------
	This directory contains the language files used for the chat messages localization.
	These are JavaScript files with the extension ".js".

2.	lib/lang/
	--------------
	This directory contains the language files used for the template output.
	These are PHP files with the extension ".php".


For each language, you need a file in each of these directories, with the language code as file name.
The language code is used following the ISO 639 standards.

The files for the english (language code "en") localization are the following:

	js/lang/en.js
	lib/lang/en.php

To enable a language, you need to add the language code in lib/config.php:

	$config['langAvailable'] = array('en');

For the language selection you also need to add the language name:

	$config['langNames'] = array('en'=>'English');

To avoid errors, you should follow these rules:

	1. Make sure you encode your localization files in UTF-8 (without Byte-order mark).
	2. Don't use HTML entities in your localization files.
	3. Don't remove any "%s" inside the JavaScript language files - these are filled with dynamic data.


Logs:
=====
	
By default, AJAX Chat stores all chat messages in the database.
To access the logs you have to add the GET parameter view=logs to your chat url:

	e.g. http://example.org/path/to/chat/?view=logs

If you are not already logged in, you have to login as administrator to access the logs.

The log view enables you to monitor the latest chat messages on all channels.
It is also possible to view the logs of private rooms and private messages.
You have the option to filter the logs by date, time and search strings.

The search filter accepts MySQL style regular expressions:

	http://dev.mysql.com/doc/refman/5.1/en/regexp.html

To search for IPs, use the following syntax:

	ip=127.0.0.1


Shoutbox:
=========
	
AJAX Chat is also usable as shoutbox - this is a short guide on how to set it up:


1. Shoutbox stylesheet
----------------------

Add the following line to the stylesheet (CSS) of all pages displaying the shoutbox:

@import url("http://example.org/path/to/chat/css/shoutbox.css");

Replace http://example.org/path/to/chat/ with the URL to the chat.
Modify css/shoutbox.css to your liking.


2. Shoutbox function
--------------------

Add the following function to your PHP code:

<?php
function getShoutBoxContent() {
	// URL to the chat directory:
	if(!defined('AJAX_CHAT_URL')) {
		define('AJAX_CHAT_URL', './chat/');
	}
	
	// Path to the chat directory:
	if(!defined('AJAX_CHAT_PATH')) {
		define('AJAX_CHAT_PATH', realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/chat').'/');
	}
	
	// Validate the path to the chat:
	if(@is_file(AJAX_CHAT_PATH.'lib/classes.php')) {
		
		// Include Class libraries:
		require_once(AJAX_CHAT_PATH.'lib/classes.php');
		
		// Initialize the shoutbox:
		$ajaxChat = new CustomAJAXChatShoutBox();
		
		// Parse and return the shoutbox template content:
		return $ajaxChat->getShoutBoxContent();
	}
	
	return null;
}
?>

Make sure AJAX_CHAT_URL and AJAX_CHAT_PATH point to the chat directory.


2. Shoutbox output
------------------

Display the shoutbox content using the shoutbox function:

<div style="width:200px;"><?php echo getShoutBoxContent(); ?></div>


Socket Server:
==============

Using the AJAX technology alone the chat clients have to permanently pull updates from the server.
This is due to AJAX being a web technology and HTTP being a stateless protocol.
Events pushed from server-side need a permanent or long-lasting socket connection between clients and server.
This requires either a custom HTTP server (called "comet") or another custom socket server.

AJAX Chat uses a JavaScript-to-Flash bridge to establish a permanent socket connection from client side.
The JavaScript-to-Flash bridge requires a Flash plugin >= 9 installed on the user browser.
Clients without this requirement will fall back to pull the server for updates.


1.	Installation
	------------

	The socket server coming with AJAX Chat is implemented in Ruby.
	You need to be able to run a Ruby script as a service to run the socket server.
	To be able to start the service, the script files in the socket/ directory have to be executable:

		$ chmod +x server
		$ chmod +x server.rb

	"server" is a simple bash script to start and stop a service.
	"server.rb" is the ruby socket server script.
	"server.conf" is a configuration file - each setting is explained with a comment.

	To start the service, execute the "server" script with the parameter "start":

		$ ./server start

	This will create two additional files:

	"server.pid" contains the process id of the service.
	"server.log" is filled with the socket server log.

	To monitor the socket server logs, you can use the "tail" command included in most GNU/Linux distributions:

		$ tail -f server.log

	By default only errors and start/stop of the server are logged.
	To get more detailed logs configure the log level by editing the configuration file.

	To stop the service, execute the "server" script with the parameter "stop":

		$ ./server stop

	If the socket server is running, you have to enable the following option in lib/config.php:

		$config['socketServerEnabled'] = true;
		
	This tells the server-side chat script to broadcast chat messages via the socket server.
	Chat clients will establish a permanent connection to the socket server to listen for chat messages.

	By default only local clients (127.0.0.1,::1) may broadcast messages.
	Clients allowed to broadcast messages may also handle the channel authentication.
	If your socket server is running on another host you should set the broadcast_clients option to the chat server IP.

	Using the socket server increases response time while improving server performance at the same time.


2.	Flash Permissions
	-----------------

	Since Flash 9.0.115.0 and all Flash 10 versions, permissions for creating sockets using Flash have changed. 
	Now an explicit permission (using xml-syntax) is required for creating socket connections. 
	In the current state, socket server won't work with the newest Flash versions. 
	You will get a "Flash security error" in the browser.

	A solution is to use a policy-files server which will listen to connections in port 843 in the server. 
	Each time a client tries to connect to the chat, the Flash client will request the policy authorization to the server. 
	The policy-files server is downloadable from http://ammonlauritzen.com/FlashPolicyService-09b.zip
	It works with FF3 and IE7 (not yet tested in other browsers).

	A more detailed explanation can be found here:

		* http://ammonlauritzen.com/blog/2007/12/13/new-flash-security-policies/
		* http://ammonlauritzen.com/blog/2008/04/22/flash-policy-service-daemon/


	Official Adobe documentation:

		* http://www.adobe.com/devnet/flashplayer/articles/fplayer9_security.html
		* http://www.adobe.com/devnet/flashplayer/articles/fplayer9_security_04.html
