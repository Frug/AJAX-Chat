<?php
// List containing the registered chat users.
// This is used by the Standalone version of chat. Integrations may use a database for
// user details.
$users = [];

// Default guest user (don't delete this one):
$users[0] = [];
$users[0]['userRole'] = AJAX_CHAT_GUEST;
$users[0]['userName'] = null;
$users[0]['password'] = null;
$users[0]['channels'] = [0];

// Sample admin user:
$users[1] = [];
$users[1]['userRole'] = AJAX_CHAT_ADMIN;
$users[1]['userName'] = 'admin';
$users[1]['password'] = 'admin';
$users[1]['channels'] = [0,1];

// Sample moderator user:
$users[2] = [];
$users[2]['userRole'] = AJAX_CHAT_MODERATOR;
$users[2]['userName'] = 'moderator';
$users[2]['password'] = 'moderator';
$users[2]['channels'] = [0,1];

// Sample registered user:
$users[3] = [];
$users[3]['userRole'] = AJAX_CHAT_USER;
$users[3]['userName'] = 'user';
$users[3]['password'] = 'user';
$users[3]['channels'] = [0,1];
