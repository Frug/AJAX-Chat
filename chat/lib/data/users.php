<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// List containing the registered chat users:
$users = array();
/*
    // Sample admin user:
    $users[1] = array();
    $users[1]['userRole'] = AJAX_CHAT_ADMIN;
    $users[1]['userName'] = 'admin';
    $users[1]['password'] = 'admin';
    $users[1]['channels'] = array(0,1);

    // Sample moderator user:
    $users[2] = array();
    $users[2]['userRole'] = AJAX_CHAT_MODERATOR;
    $users[2]['userName'] = 'moderator';
    $users[2]['password'] = 'moderator';
    $users[2]['channels'] = array(0,1);

    // Sample registered user:
    $users[3] = array();
    $users[3]['userRole'] = AJAX_CHAT_USER;
    $users[3]['userName'] = 'user';
    $users[3]['password'] = 'user';
    $users[3]['channels'] = array(0,1);
*/

// Default guest user (don't delete this one):
$users[0] = array();
$users[0]['userRole'] = AJAX_CHAT_GUEST;
$users[0]['userName'] = null;
$users[0]['password'] = null;
$users[0]['channels'] = array(0);

// added - 2014-08-13 22:39:00
$users[1] = array();
$users[1]['userRole'] = AJAX_CHAT_MODERATOR;
$users[1]['userName'] = 'edit user name foo';
$users[1]['password'] = 'foo edit password';
$users[1]['channels'] = array(0,1);

// added - 2014-08-13 22:41:00
$users[2] = array();
$users[2]['userRole'] = AJAX_CHAT_GUEST;
$users[2]['userName'] = 'user two';
$users[2]['password'] = 'password';
$users[2]['channels'] = array(0);