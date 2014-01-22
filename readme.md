Blueimp's AJAX Chat
====================

A PHP, JavaScript and XML chat client written for deployment on most standard web servers.
Integration options are provided for a few forum systems including phpBB3, MyBB, SMF, punbb/fluxbb and xenforo.
The included integrations can be used as examples for how to integrate chat with your own forum or framework.

AJAX stands for "Asynchronous JavaScript and XML".
The AJAX Chat clients (the user browsers) use JavaScript to query the web server for updates.
Instead of delivering a complete HTML page only updated data is sent in XML format. 
By using JavaScript the chat page can be updated without having to reload the whole page.

Requirements
------------

| *Server-Side*          | *Client-Side*                | 
| ---------------------- | ---------------------------- |
| PHP >= 5               | Enabled JavaScript           |
| MySQL >= 4             | Enabled Cookies              |
| Ruby >= 1.8 (optional) | Flash Plugin >= 9 (optional) |


Features
--------
- Easy installation
- Usable as shoutbox
- Multiple channels
- Private messaging
- Private channels
- Invitation system
- Kick/Ban or Ignore offending Users
- Online users list with user menu
- Emoticons/Smilies
- Easy way to add custom emoticons
- BBCode support
- Optional Flash based sound support
- Optional visual update information (changing window title)
- Clickable Hyperlinks
- Splitting of long words to preserve chat layout
- Flood control
- Possibility to delete messages inside the chat
- IRC style commands
- Easy interface to add custom commands
- Possibility to define opening hours for the chat
- Possibility to enable/disable guest users
- Persistent client-side settings
- Multiple languages (auto-detection of ACCEPT_LANGUAGE browser setting)
- Multiple styles with easy layout customization through stylesheets (CSS) and templates
- Automatic adjustment of displayed time to local client timezone
- Standards compliance (XHTML 1.0 strict)
- Accepts any text input, including code and special characters
- Multiline input field with the possibility to enter line breaks
- Message length counter
- Realtime monitoring and logs viewer
- Support for unicode (UTF-8) and non-unicode content types
- Bandwidth saving update calls (only updated data is sent)
- Optional support to push updates over a Flash based socket connection (increased performance and responsiveness)
- Survives connection timeouts
- Easy integration into existing authentication systems
- Sample phpBB3, MyBB, PunBB, SMF and xenforo integrations included
- Separation of layout and code with well commented source code
- Developed with security as integral part - built to prevent code injections, SQL injections, cross-site scripting (XSS), session stealing and other attacks
- Tested successfully with Microsoft Internet Explorer, Mozilla Firefox, Opera and Safari - built to work with all modern browsers :)


Help
----
Essential documentation is contained in the attached readme files

For more documentation consult the github wiki: https://github.com/Frug/AJAX-Chat/wiki

For support questions use google groups: https://groups.google.com/forum/#!forum/ajax-chat

To report bugs use github issues: https://github.com/Frug/AJAX-Chat

License
-------
Blueimp's AJAX Chat is released under a Modified MIT License with an exception that the license does not extend to other projects or components that may be included.
You can find more details in the license.txt file.